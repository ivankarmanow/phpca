<?php

namespace core\protocols;

/*
 * Базовый класс представления (View)
 * Должен быть переопределён
 * Загружает файл по пути src/app/views/$ControllerGroup/templates/$templateFile.php
 * Прокидывает в шаблон данные через массив $data, а также другие поля, определяемые в дочерних классах
 * Реализует интерфейс массива
 */
class View implements \ArrayAccess
{
    public function __construct(
        protected string $templateFile,
        protected array $data = array()
    ) {

    }

    public function render(array $data = array()): void
    {
        if (empty($data)) {
            $data = $this->data;
        }
        require __DIR__ . "/templates/" . $this->templateFile . ".php";
    }

    public function set_data(array $data): void
    {
        $this->data = $data;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->data[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }
}