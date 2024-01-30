<?php

namespace core\routing;

require_once __DIR__ . "/methods.php";

//require_once __DIR__ . "/../methods.php";
//require "src/app/core/exceptions.php";

use controllers\StubController;
use core\exceptions\ControllerMissing;
use core\exceptions\IncludeParentRouter;
use core\exceptions\MethodNotAllowed;
use core\exceptions\RouterYetIncluded;
use core\protocols\Controller;

/*
 * Роутер
 * Основной класс маршрутизации запросов к контроллерам и их методам
 * Может включать в себя другие роутеры
 */
class Router {
    protected array $routes; // Массив эндпоинтов и соответсвующих методов
    protected array $routers; // Дочерние роутеры
    private array $parents; // Родительские роутеры
    protected array $exceptionHandlers;

    public function __construct(public ?Controller $controller = null, public string $prefix = "", public bool $login_required = false, public mixed $rights = false, public array $allowed_methods = HTTP_METHODS, public string $name = "") {
        $this->routes = [];
        $this->routers = [];
        $this->parents = [];
        $this->exceptionHandlers = [];
    }

    public function getParents(): array
    {
        return $this->parents;
    }

    public function register(string $method, string $path, callable | string $callback, ?Controller $controller = null, bool $login_required = false, mixed $rights = array()): void
    {
        if (in_array($method, $this->allowed_methods)) {
            $this->routes[$method][$this->prefix . $path] = [
                "controller" => $controller ?: $this->controller,
                "callback" => $callback,
                "login_required" => $login_required ?: $this->login_required,
                "rights" => $rights ?: $this->login_required
            ];
            if (!$this->routes[$method][$this->prefix . $path]["controller"]) {
                throw new ControllerMissing();
            }
        } else {
            throw new MethodNotAllowed("Method $method not allowed. Allowed methods: $this->allowed_methods");
        }
    }

    public function get(string $path, callable | string $callback, ?Controller $controller = null, bool $login_required = false, mixed $rights = array()): void
    {
        $this->register("get", $path, $callback, $controller, $login_required, $rights);
    }

    public function post(string $path, callable | string $callback, ?Controller $controller = null, bool $login_required = false, mixed $rights = array()): void
    {
        $this->register("post", $path, $callback, $controller, $login_required, $rights);
    }

    public function resolve(Request $request)
    {
        $path = $request->getPath();
        $method = $request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            if (!$this->routers) {
                return false;
            }
            foreach ($this->routers as $child) {
                $callback = $child->resolve($request);
                if ($callback) {
                    return $callback;
                }
            }
        }

        return $callback;
    }

    public function in(self $router): bool
    {
        if (!$this->routers) {
            return false;
        }
        if (in_array($router, $this->routers)) {
            return true;
        } else {
            foreach ($this->routers as $child) {
                if ($child->in($router)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function is_parent(self $router): bool
    {
        if (!$this->parents) {
            return false;
        }
        if (in_array($router, $this->parents)) {
            return true;
        } else {
            foreach ($this->parents as $parent) {
                if ($parent->is_parent($router)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function include_router(self $router, string $prefix = ""): void
    {
        if (!$router->prefix) {
            $router->prefix = $prefix;
        }
        if ($this->in($router)) {
            throw new RouterYetIncluded();
        }
        if ($this->is_parent($router)) {
            throw new IncludeParentRouter();
        }
        $this->routers[] = $router;
        $router->parents[] = $this;
    }
}