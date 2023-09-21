<?php

namespace models;

class User
{
    public function __construct(public string $name, public string $email, public string $password, public int $id = -1) {
    }
}