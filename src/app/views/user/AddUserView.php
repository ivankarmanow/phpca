<?php

namespace views\user;

use core\protocols\View;

class AddUserView extends View {

    public bool $response_status = true;
    public string $error;

    public function __construct(
        protected string $templateFile = "",
        protected array $data = array()
    ) {
        parent::__construct($this->templateFile, $data);
    }

    public function render(array $data = array()): void
    {

        if ($this->response_status) {
            $data = [
                "status" => true,
                "responseCode" => 201,
                "message" => "User has been successfully created"
            ];
        } else {
             if ($this->error == "exists") {
                $data = [
                    "status" => false,
                    "responseCode" => 409,
                    "error" => $this->error,
                    "message" => "A user with this email already exists"
                ];
            } else if ($this->error == "bad_params") {
                $data = [
                    "status" => false,
                    "responseCode" => 400,
                    "error" => $this->error,
                    "message" => "Not enough parameters"
                ];
            } else {
                 $data = [
                     "status" => false,
                     "responseCode" => 400,
                     "error" => "unexpected",
                     "message" => $this->error
                 ];
             }
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }

}