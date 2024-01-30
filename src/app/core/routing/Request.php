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

    public function __construct()
    {
        $this->path = $this->getPath();
        $this->method = $this->getMethod();
        $this->params = $this->getParams();
        $this->authorization = $this->getAuthorization();
    }

    public function getPath()
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
                return $paramsArray[$key] ?? null;
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