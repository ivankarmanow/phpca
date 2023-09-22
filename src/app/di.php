<?php

use controllers\UserController;
use core\DIContainer;
use adapters\IniConfig;
use core\ViewsContainer;

$di = new DIContainer();
$di['Config'] = function (DIContainer $container) {
    return new IniConfig("config.ini");
};
$di['DbGateway'] = "MySqlGateway";
$di['ViewsContainer'] = function (DIContainer $container) {
    $views = new ViewsContainer();
    $views[UserController::class] = __DIR__ . "/views/user/";
    return $views;
};
