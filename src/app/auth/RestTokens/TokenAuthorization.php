<?php

namespace auth\RestTokens;

use core\protocols\Authorization;

class TokenAuthorization implements Authorization
{

    public function checkRights(mixed $rights, mixed $requiredRights): bool
    {
        // TODO: Implement checkRights() method.
    }
}