<?php

namespace core\models;

use ArrayAccess;
use core\protocols\Model;

/*
 * Модель категории товаров
 * Включает в себя список товаров Item
 * Реализует интерфейс массива
 */
class Category extends Model implements ArrayAccess
{

    public static string $tablename = "categories";
    public static string $create_table = "";
    public array $items = [];
    public string $name;
    public int $id = -1;

    public function __construct(
        string $name = null,
        int $id = -1
    ) {
        $this->name = $name;
        $this->id = $id;
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