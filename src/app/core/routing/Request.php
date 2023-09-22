<?php

namespace core\routing;

/*
 * Запрос
 * Абстрагирует получение пути, метода и параметров
 * Передаётся в контроллеры из роутеров
 */
class Request
{
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');

        if ($position === false) return $path;
        return substr($path, 0, $position);
    }

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getParams()
    {
        switch ($this->getMethod()) {
            case "get":
                return $_GET;
            case "post":
                return $_POST;
            default:
                return null;
        }
    }
}