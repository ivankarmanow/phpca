<?php

spl_autoload_register(function ($class) {
    $class_path = str_replace('\\', '/', $class);

    $file =  __DIR__ . '\\src\\app\\' . $class_path . '.php';

    if (file_exists($file)) {
        require $file;
    } else {
        echo $file;
    }
});

include "src/app/di.php";
global $di;

use controllers\StubController;
use core\routing\Dispatcher;
use core\routing\Request;
use controllers\UserController;
use core\routing\Router;

$dp = new Dispatcher($di[StubController::class]);

$user_router = new Router($di[UserController::class], "/user");
$user_router->get("/list", "list");

$dp->include_router($user_router);

//$user_router = new Router();

$dp->resolve(new Request());