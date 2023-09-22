<?php

namespace core\models;

use core\protocols\Model;

/*
 * Модель корзины покупок
 * Привязана к 1 пользователю, а также к нескольким моделям CartElement
 */
class Cart extends Model
{
    public static string $tablename = "carts";
    public static string $create_table = "";
    public User $user;
    public int $user_id;
    public array $elements;

    public function __construct(
        int $user_id = -1,
        array $elements = array()
    ) {
        if (!empty($user_id)) {
            $this->user_id = $user_id;
        }
        if (!empty($elements)) {
            $this->elements = $elements;
        }
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }
}