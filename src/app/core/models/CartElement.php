<?php

namespace core\models;

use core\protocols\Model;

/*
 * Модель позиции в корзине
 * Связана с одним пользователем и одним товаром
 */
class CartElement extends Model
{

    public static string $tablename = "cart_elements";
    public static string $create_table = "";
    public User $user;
    public Item $item;
    public int $user_id;
    public int $item_id;
    public int $quantity = 1;

    public function __construct(
        int $user_id = null,
        int $item_id = null,
        int $quantity = 1
    ) {
        $this->user_id = $user_id;
        $this->item_id = $item_id;
        $this->quantity = $quantity;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setItem(Item $item): void
    {
        $this->item = $item;
    }
}