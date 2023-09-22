<?php

namespace core;

use ArrayAccess;

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
        if (isset($this->views[$offset])) {
            $this->views[$offset] = $value;
        } else {
            $this->views[] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->views[$offset]);
    }
}