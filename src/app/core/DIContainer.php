<?php

namespace core;

use ArrayAccess;
use core\exceptions\DependencyNotFound, core\exceptions\FactoryAlreadyExists, core\exceptions\ValueError;

/*
 * DI контейнер, или контейнер зависимостей
 * Хранит массив фабрик и готовых объектов
 * Выдаёт зависимости, создавая нужные объекты или получая их из кэша
 * Разрешает все зависимости класса через получение типов аргументов консттруктора и поиска их в контейнере
 * Реализует интерфейс массива
 */
class DIContainer implements ArrayAccess {

    private array $factories = [];
    private array $objects = [];
    public function register(string $name, string | callable $factory)
    {
        if (!isset($this->factories[$name])) {
            $this->factories[$name] = $factory;
        } else {
            throw new FactoryAlreadyExists();
        }
    }

    private function resolve_dependencies(string $class): object
    {
        if (!class_exists($class)) {
            throw new ValueError();
        }
        $classReflector = new \ReflectionClass($class);

        $constructReflector = $classReflector->getConstructor();
        if (empty($constructReflector)) {
            return new $class;
        }

        $constructArguments = $constructReflector->getParameters();
        if (empty($constructArguments)) {
            return new $class;
        }

        $args = [];
        foreach ($constructArguments as $argument) {
            $argumentType = $argument->getType()->getName();
            $args[$argument->getName()] = $this->get($argumentType);
        }

        return new $class(...$args);
    }

    public function get(string $name)
    {
        if (isset($this->objects[$name])) {
            return $this->objects[$name];
        } else {
//            var_dump($this->factories);
            if (is_callable($this->factories[$name])) {
                $obj = $this->factories[$name]($this);
            } else if (class_exists($this->factories[$name])) {
                $obj = $this->resolve_dependencies($this->factories[$name]);
            }
            $this->objects[$name] = $obj;
            return $obj;
        }
    }

    public function offsetSet($offset, $value): void {
        if (!is_callable($value) and !class_exists($value)) {
            echo $value;
            throw new ValueError();
        }
        $this->register($offset, $value);
    }

    public function offsetExists($offset): bool {
        return isset($this->factories[$offset]);
    }

    public function offsetUnset($offset): void {
        unset($this->factories[$offset]);
        unset($this->objects[$offset]);
    }

    public function offsetGet($offset): mixed {
        return $this->get($offset);
    }
}