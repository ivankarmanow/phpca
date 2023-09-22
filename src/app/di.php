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

/*
 * Загрузка зависимостей в контейнер
 */

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