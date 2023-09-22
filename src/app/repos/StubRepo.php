<?php

namespace repos;

use core\protocols\DbGateway;
use core\protocols\Repo;

class StubRepo implements Repo
{
    public function __construct(
        public DbGateway $db,
    ) { }
}