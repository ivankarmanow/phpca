<?php

namespace core\models;

/*
 * Модель пользователя
 */
class User
{

    public string $name;
    public string $email;
    public string $password;
    public int $id;

}

//    public function __construct(
//        public string $name,
//        public string $email,
//        public string $password,
//        public int $id = -1) {
//    }