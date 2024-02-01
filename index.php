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

use controllers\IndexController;
use controllers\StubController;
use core\exceptions\NotFound;
use core\routing\Dispatcher;
use core\routing\Request;
use controllers\UserController;
use core\routing\Router;

/*
 * Создание корневого диспетчера
 * Обработка корневого маршрута /
 * Обработка исключения 404 not found с помощью замыкания
 */
$dp = new Dispatcher();
$dp->get("/", "index", $di[IndexController::class]);
$dp->exception(NotFound::class, function (Request $request) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        "status" => false,
        "error" => [
            [
                "code" => 404,
                "message" => "Page Not Found"
            ]
        ]
    ]);
});

/*
 * Роутер операций над пользователями
 * Регистрируется контроллер UserController
 * На эндпоинт /user/list вешается UserController->list
 */
$user_router = new Router($di[UserController::class], "/user");
$user_router->get("/list", "list");
$user_router->post("/add", "add");
$user_router->get("/get", "get");
$user_router->get("/get/{id}", "get");

/*
 * Включение роутера в основной обработчик событий
 */
$dp->include_router($user_router);

/*
 * Маршрутизация запроса через диспетчер, выполняется рекурсивно по всем роутерам
 * Если возникает исключение, запускается поиск его обработчика в диспетчере и если найдён, то управление передаётся ему
 */
$request = new Request();
try {
    $dp->resolve($request);
} catch (Exception $exception) {
    $dp->handle($exception, $request);
}
