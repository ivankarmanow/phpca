<?php

namespace core\models;

/*
 * Модель корзины покупок
 * Привязана к 1 пользователю, а также к нескольким моделям CartElement
 */
class Cart
{
    public User $user;

    public function __construct(
        public int $user_id,
        public array $elements
    ) {

    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }
}