<?php

use controllers\UserController;
use core\DIContainer;
use adapters\IniConfig;
use core\ViewsContainer;
use repos\UserRepo;
use views\user\ListUsersView;
use adapters\MySqlGateway;
use controllers\StubController;
use core\protocols\Config;
use core\protocols\DbGateway;

$di = new DIContainer();
$di[Config::class] = function (DIContainer $container) {
    return new IniConfig("config.ini");
};
$di[DbGateway::class] = MySqlGateway::class;
$di[ViewsContainer::class] = function (DIContainer $container) {
    $views = new ViewsContainer();
    $views[UserController::class] = [
        ListUsersView::class => new ListUsersView()
    ];
    return $views;
};
$di[UserController::class] = UserController::class;
$di[StubController::class] = StubController::class;
$di[UserRepo::class] = UserRepo::class;

//$di["Config"] = function (DIContainer $container) {
//    return new IniConfig("config.ini");
//};
//$di["DbGateway"] = MySqlGateway::class;
//$di["ViewsContainer"] = function (DIContainer $container) {
//    $views = new ViewsContainer();
//    $views[UserController::class] = [
//        ListUsersView::class => new ListUsersView()
//    ];
//    return $views;
//};
//$di["UserController"] = UserController::class;
//$di["StubController"] = StubController::class;