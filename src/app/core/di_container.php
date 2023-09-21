<?php

namespace di_container;

require_once __DIR__ . "/exceptions.php";

use exceptions\DependencyNotFound, exceptions\FactoryAlreadyExists, exceptions\ValueError;

class DIContainer implements ArrayAccess {

    private array $factories = [];
    private array $objects = [];
    public function register(string $name, callable $factory)
    {
        if (is_null($name)) {
            $this->factories[] = $factory;
        } else {
            throw new FactoryAlreadyExists();
        }
    }

    private function resolve_dependencies(callable $class): object
    {
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
            if (function_exists($this->factories[$name])) {
                $obj = $this->factories[$name]($this);
            } else if (class_exists($this->factories[$name])) {
                $obj = $this->resolve_dependencies($this->factories[$name]());
            }
            $this->objects[$name] = $obj;
            return $obj;
        }
    }

    public function offsetSet($name, $factory): void {
        if (!is_string($name) or !is_callable($factory)) {
            throw new ValueError();
        }
        $this->register($name, $factory);
    }

    public function offsetExists($name): bool {
        return isset($this->factories[$name]);
    }

    public function offsetUnset($name): void {
        unset($this->factories[$name]);
        unset($this->objects[$name]);
    }

    public function offsetGet($name): mixed {
        if (!is_string($name)) {
            return null;
        }
        return $this->get($name);
    }
}