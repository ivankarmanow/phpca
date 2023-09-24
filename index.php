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

/*
 * Создание корневого диспетчера с контроллером-заглушкой
 */
$dp = new Dispatcher($di[StubController::class]);

/*
 * Роутер операций над пользователями
 * Регистрируется контроллер UserController
 * На эндпоинт /user/list вешается UserController->list
 */
$user_router = new Router($di[UserController::class], "/user");
$user_router->get("/list", "list");
$user_router->post("/add", "add");

/*
 * Включение роутера в основной обработчик событий
 */
$dp->include_router($user_router);

/*
 * Маршрутизация запроса через диспетчер, выполняется рекурсивно по всем роутерам
 */
$dp->resolve(new Request());