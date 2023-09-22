<?php

namespace core\models;

use core\protocols\Model;

/*
 * Модель пользователя
 */
class User extends Model
{
    public static string $tablename = "users";
    public static string $create_table = "
        CREATE TABLE IF NOT EXISTS `users` (
            `id` int NOT NULL AUTO_INCREMENT,
            `email` varchar(256) NOT NULL,
            `name` varchar(256) NOT NULL,
            `password` varchar(256) NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `email` (`email`)
        )
    ";
    public string $name;
    public string $email;
    public string $password;
    public int $id;

    public function __construct(
        string $name = "",
        string $email = "",
        string $password = "",
        int $id = -1
    ) {
        if (!empty($name)) {
            $this->name = $name;
        }
        if (!empty($email)) {
            $this->email = $email;
        }
        if (!empty($password)) {
            $this->password = $password;
        }
        if (!empty($id)) {
            $this->id = $id;
        }
    }
}

//    public function __construct(
//        public string $name,
//        public string $email,
//        public string $password,
//        public int $id = -1) {
//    }