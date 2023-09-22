<?php

namespace core\models;

/*
 * Модель категории товаров
 * Включает в себя список товаров Item
 * Реализует интерфейс массива
 */
class Category implements \ArrayAccess
{

    public array $items = [];

    public function __construct(
        public string $name,
        public int $id = -1
    ) {
        
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }
}