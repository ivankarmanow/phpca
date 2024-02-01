<?php

namespace controllers;

use core\exceptions\EmailExists;
use core\protocols\Controller;
use core\protocols\Repo;
use core\protocols\View;
use core\routing\Request;
use Exception;
use repos\UserRepo;
use views\RESTView;

/*
 * Контроллер User
 * Реализует методы, схожие с репозиторием
 * Получает данные от пользователя и отдаёт представлениям (view)
 */
class UserController extends Controller {

//    public UserRepo $repo;
//    public RESTView $view;

    public function __construct(
        protected View $view,
        protected UserRepo $repo
    ) {
        parent::__construct($view);
    }

    public function add(Request $request): void
    {
        $params = $request->getParams();
//        if (array_key_exists("back", $params)) {
//            $back = $params['back'];
//        } else {
//            $back = $_SERVER['HTTP_REFERER'] ?? "/";
//        }
        try {
            $user_id = $this->repo->create_user(...$params);
            $this->view->render([
                "message" => "User created",
                "user" => $this->repo->get_user($user_id)
            ]);
        } catch (EmailExists) {
            $this->view->render([],false, 402);
        } catch (Exception $e) {
            $this->view->render([], false, 500);
        }
    }

    public function get(Request $request): void
    {
        $selector =
        $user = $this->repo->get_user(...$request->getParams("id", "email"));
        $this->view->render([
            "user" => $user
        ]);
    }

    public function list(Request $request): void
    {
        $this->view->render([
            "users" => $this->repo->list_users()
        ]);
    }

    public function update(Request $request)
    {
        
    }

    public function delete(Request $request)
    {

    }
}