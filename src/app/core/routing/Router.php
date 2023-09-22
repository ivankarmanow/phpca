<?php

namespace routing;

require_once __DIR__ . "/../methods.php";
require "src/app/core/exceptions.php";

use exceptions\IncludeParentRouter;
use exceptions\MethodNotAllowed;
use exceptions\RouterYetIncluded;
use protocols\Controller;

class Router {
    protected array $routes;
    protected array $routers;
    private array $parents;

    public function __construct(public Controller $controller, public string $prefix = "", public array $allowed_methods = HTTP_METHODS, public string $name = "") {
        $this->routes = [];
        $this->routers = [];
        $this->parents = [];
    }

    public function getParents()
    {
        return $this->parents;
    }

    public function register(string $method, string $path, callable $callback)
    {
        if (in_array($method, $this->allowed_methods)) {
            $this->routes[$method][$this->prefix . $path] = $callback;
        } else {
            throw new MethodNotAllowed("Method $method not allowed. Allowed methods: $this->allowed_methods");
        }
    }

    public function get(string $path, callable $callback)
    {
        $this->register("get", $path, $callback);
    }

    public function post(string $path, callable $callback)
    {
        $this->register("get", $path, $callback);
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

    public function in(self $router)
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

    public function is_parent(self $router) {
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

    public function include_router(self $router, string $prefix = "")
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