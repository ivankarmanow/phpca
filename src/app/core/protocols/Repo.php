<?php

namespace protocols;

class Repo
{
    public function __construct(
        public DbGateway $db,
    ) { }
}