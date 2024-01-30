<?php

namespace auth\RestTokens;

use core\protocols\Authentication;
use core\routing\Request;

class TokenAuthentication implements Authentication
{

    public function auth(Request $request): bool
    {
        // TODO: Implement auth() method.
    }
}