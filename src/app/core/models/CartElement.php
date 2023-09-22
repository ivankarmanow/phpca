<?php

namespace core\models;

/*
 * Модель позиции в корзине
 * Связана с одним пользователем и одним товаром
 */
class CartElement
{

    public User $user;
    public Item $item;

    public function __construct(
        public int $user_id,
        public int $item_id,
        public int $quantity = 1
    ) {

    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function setItem(Item $item)
    {
        $this->item = $item;
    }
}