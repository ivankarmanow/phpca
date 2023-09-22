<?php

namespace core\models;

class Cart
{

    public User $user;

    public function __construct(public int $user_id, public array $elements) {

    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }
}