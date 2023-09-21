<?php

spl_autoload_register(function ($class) {
    $class_path = str_replace('\\', '/', $class);

    $file =  __DIR__ . '/src/app/' . $class_path . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

global $home;
//require_once "src/app/core/routing/Dispatcher.php";
//require_once "src/app/core/routing/Request.php";
//
//require_once "src/app/controllers/home.php";

use routing\Dispatcher;
use routing\Request;
//use controllers\home;

$dp = new Dispatcher();
$dp->include_router($home);

$dp->resolve(new Request());