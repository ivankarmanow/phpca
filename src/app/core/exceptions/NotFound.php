<?php

namespace core\exceptions;

use Exception;

class NotFound extends Exception
{
    public string $path;
}