<?php

namespace core\routing;

require_once __DIR__ . "/methods.php";

//require_once "Router.php";
//require_once __DIR__ . "/../exceptions.php";

use controllers\StubController;
use core\exceptions\DispatcherHasNotParents;
use core\exceptions\NotFound;
use core\protocols\Controller;


class Dispatcher extends Router {

    public function __construct(public Controller $controller, public string $prefix = "", public array $allowed_methods = HTTP_METHODS) {
        parent::__construct($this->controller, $this->prefix, $this->allowed_methods, "root");
    }
    
    public function getParents()
    {
        throw new DispatcherHasNotParents();
    }

    public function resolve(Request $request): void
    {
        $path = $request->getPath();
        $method = $request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            if (!$this->routers) {
                throw new NotFound();
            }
            foreach ($this->routers as $child) {
//                var_dump($child->routes);
                $callback = $child->resolve($request);
                $controller = $child->controller;
                if ($callback) {
                    break;
                }
            }
        }

        if ($callback === false) {
            throw new NotFound();
        } else {
//            var_dump($controller);
            $controller->$callback($request);
        }
    }

    public function is_parent(Router $router)
    {
        return false;
    }
}