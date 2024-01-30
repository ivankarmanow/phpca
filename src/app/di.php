<?php

use adapters\IniConfig;
use adapters\MySqlGateway;
use controllers\IndexController;
use controllers\StubController;
use controllers\UserController;
use core\DIContainer;
use core\protocols\Config;
use core\protocols\DbGateway;
use core\protocols\View;
use repos\UserRepo;
use views\RESTView;

/*
 * Загрузка зависимостей в контейнер
 */

$di = new DIContainer();
$di[Config::class] = function (DIContainer $container) {
    return new IniConfig("config.ini");
};
$di[DbGateway::class] = MySqlGateway::class;
//$di[ViewsContainer::class] = function (DIContainer $container) {
//    $views = new ViewsContainer();
//    $views[UserController::class] = [
//        ListUsersView::class => new ListUsersView(),
//        AddUserView::class => new AddUserView(),
//        GetUserView::class => new GetUserView(),
//    ];
//    return $views;
//};
$di[View::class] = RESTView::class;
$di[UserController::class] = UserController::class;
$di[IndexController::class] = IndexController::class;
$di[StubController::class] = StubController::class;
$di[UserRepo::class] = UserRepo::class;