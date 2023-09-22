<?php

namespace core\models;

class Item
{

    public Category $category;

    public function __construct(public string $name, public int $price, public int $quantity = 0, public int $category_id = -1, public int $id = -1) {

    }

    public function setCategory(Category $category) {
        $this->category = $category;
    }
}