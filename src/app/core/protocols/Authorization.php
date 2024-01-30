<?php

namespace core\protocols;

use core\routing\Request;

interface Authorization {
    public function checkRights(mixed $rights, mixed $requiredRights): bool;
}