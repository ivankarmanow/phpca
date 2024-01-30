<?php

namespace core\protocols;

use core\routing\Request;

interface Authentication {
    public function auth(Request $request): bool;
}