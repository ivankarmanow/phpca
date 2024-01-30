<?php

namespace core\models;

use core\protocols\Model;

/*
 * Модель товара
 * Связана с одной категорией
*/
class Item extends Model
{
    public static string $tablename = "items";
    public static string $create_table = "";
    public Category $category;
    public string $name;
    public int $price;
    public int $quantity = 0;
    public int $category_id = -1;
    public int $id = -1;

    public function __construct(
        string $name = null,
        int $price = null,
        int $quantity = 0,
        int $category_id = -1,
        int $id = -1
    ) {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->category_id = $category_id;
        $this->id = $id;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }
}