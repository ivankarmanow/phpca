<?php

namespace views;

use core\protocols\View;

class RESTView implements View
{
    public static array $errors = [
        404 => "Endpoint not found",
        401 => "Unauthorized",
        403 => "Forbidden",
        402 => "Yet Exists",
        500 => "Server Error"
    ];

    public function render(
        array $data = array(),
        bool $status = true,
        int $error = null
    ): void
    {
        $response = array();
        $response["status"] = $status;
        if (!$status) {
            $response["error"] = [
                "code" => $error,
                "message" => self::$errors[$error]
            ];
        } else {
            $response['data'] = $data;
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }
}