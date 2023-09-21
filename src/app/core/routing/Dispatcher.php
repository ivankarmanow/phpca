<?php

namespace routing;

require_once "Router.php";
require_once __DIR__ . "/../exceptions.php";

use exceptions\DispatcherHasNotParents;

class Dispatcher extends Router {

    public function __construct(public string $prefix = "", public array $allowed_methods = HTTP_METHODS) {
        $this->routes = [];
        $this->routers = [];
        $this->name = "root";
    }
    
    public function getParents()
    {
        throw new DispatcherHasNotParents();
    }

    public function resolve(Request $request)
    {
        $path = $request->getPath();
        $method = $request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            if (!$this->routers) {
                return "404";
            }
            foreach ($this->routers as $child) {
                $callback = $child->resolve($request);
                if ($callback) {
                    break;
                }
            }
        }

        if ($callback === false) {
            return "404";
        } else {
            call_user_func($callback, $request);
        }
    }

    public function is_parent(Router $router)
    {
        return false;
    }
}