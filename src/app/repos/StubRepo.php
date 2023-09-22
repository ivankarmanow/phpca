<?php

namespace repos;

use core\protocols\DbGateway;
use core\protocols\Repo;

/*
 * Заглушка репозитория
 * Для обхода особенности типизации при наследовании PHP
 */
class StubRepo implements Repo
{
    public function __construct(
        public DbGateway $db,
    ) { }
}