<?php

namespace core\routing;

/*
 * Запрос
 * Абстрагирует получение пути, метода и параметров
 * Передаётся в контроллеры из роутеров
 */
class Request
{
    public string $path;
    public string $method;
    public array $params;
    public ?string $authorization;

    public array $pathParams;

    public function __construct()
    {
        $this->path = $this->getPath();
        $this->method = $this->getMethod();
        $this->params = $this->getParams();
        $this->authorization = $this->getAuthorization();
    }

    public function extractParams(string $route, string $uri): void {
        $pattern = preg_replace('#\{([a-zA-Z0-9_]+)}#', '([a-zA-Z0-9_]+)', $route);
        $pattern = '#^' . $pattern . '$#';
        preg_match($pattern, $uri, $matches);
//        var_dump($pattern, $uri);
        array_shift($matches);

        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '{$1}', $route);
        $paramNames = [];
        preg_match_all('/\{([a-zA-Z0-9_]+)\}/', $pattern, $paramNames);
        $paramNames = $paramNames[1];

        $assocParams = [];
        foreach ($paramNames as $index => $name) {
            $assocParams[$name] = $matches[$index];
        }

        $this->pathParams = $assocParams;
    }

    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');

        if ($position === false) return $path;
        return substr($path, 0, $position);
    }

    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getParams(...$keys): ?array
    {
        $method = $this->getMethod();

        if (!in_array($method, ['get', 'post'])) {
            return null;
        }

        $paramsArray = ($method === "get") ? $_GET : $_POST;
        if (!empty($keys)) {
            return array_map(function ($key) use ($paramsArray) {
                return $this->pathParams[$key] ?? ($paramsArray[$key] ?? null);
            }, $keys);
        } else {
            return $paramsArray;
        }
    }
    
    public function getParam(string $key): mixed
    {
        return $this->params[$key] ?? null;
    }

    public function getAuthorization(): ?string
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (isset(apache_request_headers()['Authorization'])) {
            $headers = apache_request_headers()['Authorization'];
        }
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}