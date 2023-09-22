<?php

namespace core\protocols;

/*
 * Базовый протокол для файлов конфигурации
 * Должен быть переопределён с реализацией конструктора или функции load
 */
class Config
{
    public string $db_dsn;
    public string $db_user;
    public string $db_password;
}