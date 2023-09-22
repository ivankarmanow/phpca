<?php

namespace core;

use ArrayAccess;

/*
 * Контейнер представлений (Views)
 * Простая абстракция, реализованная как обычный массив
 * Иерархия представлений:
 * [
 *     Controller1:
 *         add
 *         get
 *         delete
 *     Controller2:
 *         update
 * ]
 */
class ViewsContainer implements ArrayAccess {
    public array $views = [];

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->views[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->views[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->views[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->views[$offset]);
    }
}