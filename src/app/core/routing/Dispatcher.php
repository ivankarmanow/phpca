<?php

namespace core\routing;

require_once __DIR__ . "/methods.php";

//require_once "Router.php";
//require_once __DIR__ . "/../exceptions.php";

use core\exceptions\DispatcherHasNotParents;
use core\exceptions\NotFound;
use core\exceptions\ValueError;
use core\protocols\Controller;
use Exception;

/*
 * Диспетчер запросов
 * Является по сути корневым роутером, не имеющим родителей
 * Выполняет не только поиск соответствующего запросу контроллера и метода, но и передачу им управления
 */

class Dispatcher extends Router
{

    public function __construct(
        public ?Controller $controller = null,
        public string      $prefix = "",
        public bool        $login_required = false,
        public mixed       $rights = false,
        public array       $allowed_methods = HTTP_METHODS
    )
    {
        parent::__construct($this->controller, $this->prefix, $this->login_required, $this->login_required, $this->allowed_methods, "root");
    }

    public function getParents(): array
    {
        throw new DispatcherHasNotParents();
    }

    public function exception(string $exception, callable $handler)
    {
        if (!class_exists($exception)) {
            throw new ValueError();
        }
        $this->exceptionHandlers[] = [
            "exception" => $exception,
            "handler" => $handler
        ];
    }

    public function resolve(Request $request): void
    {
        $path = $request->getPath();
        $method = $request->getMethod();
        $callback = false;
        if (!empty($this->routes[$method])) {
            foreach ($this->routes[$method] as $route => $action) {
                if ($this->isMatch($route, $path)) {
                    $callback = $action;
                    $request->extractParams($route, $path);
                    break;
                }
            }
        }

        if ($callback === false) {
            if (!$this->routers) {
                throw new NotFound();
            }
            foreach ($this->routers as $child) {
//                var_dump($child->routes);
                $callback = $child->resolve($request);
                if ($callback) {
                    break;
                }
            }
        }

        if ($callback === false) {
            throw new NotFound($path);
        } else {
//            var_dump($controller);
            if ($callback["login_required"]) {

            }
            [$callback["controller"], $callback["callback"]]($request);
        }
    }

    public function handle(Exception $exception, Request $request)
    {
        foreach ($this->exceptionHandlers as $handler) {
            if ($handler["exception"] == $exception::class) {
                $handler["handler"]($request);
                break;
            }
        }
    }

    public function is_parent(Router $router): bool
    {
        return false;
    }
}