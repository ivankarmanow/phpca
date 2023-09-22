<?php

spl_autoload_register(function ($class) {
    $class_path = str_replace('\\', '/', $class);

    $file =  __DIR__ . '/src/app/' . $class_path . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

include "src/app/di.php";
global $di;

use routing\Dispatcher;
use routing\Request;
use controllers\UserController;
use routing\Router;

$dp = new Dispatcher($di['StubController']);

//$user_router = new Router();

$dp->resolve(new Request());