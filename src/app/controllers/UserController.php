<?php

namespace controllers;

use core\exceptions\EmailExists;
use core\protocols\Controller;
use repos\StubRepo;
use repos\UserRepo;
use core\routing\Request;
use core\ViewsContainer;
use views\user\AddUserView;
use views\user\ListUsersView;

use core\exceptions\MethodNotAllowed;

/*
 * Контроллер User
 * Реализует методы, схожие с репозиторием
 * Получает данные от пользователя и отдаёт представлениям (view)
 */
class UserController extends Controller {

    public function __construct(
        public ViewsContainer $views_container,
        public UserRepo $repo,
    ) {
        parent::__construct($this->views_container);
        $this->load_views(self::class);
    }
    public function add(Request $request) {
        if ($request->getMethod() != "post") {
            throw new MethodNotAllowed();
        }
        $params = $request->getParams();
//        if (array_key_exists("back", $params)) {
//            $back = $params['back'];
//        } else {
//            $back = $_SERVER['HTTP_REFERER'] ?? "/";
//        }
        $view = $this->views[AddUserView::class];
        try {
            $this->repo->create_user(...$params);
        } catch (EmailExists $e) {
            $view->response_status = false;
            $view->error = "exists";
        } catch (\Exception $e) {
            $view->response_status = false;
            $view->error = $e->getMessage();
//            var_dump($e->getTrace());
        } finally {
            $view->render();
        }
    }

    public function get(Request $request)
    {

    }

    public function list(Request $request)
    {
        $view = $this->views[ListUsersView::class];
//        var_dump($this->views_container);
//        var_dump($this->repo->list_users());
        $view->users = $this->repo->list_users();
        $view->render();
    }

    public function update(Request $request)
    {
        
    }

    public function delete(Request $request)
    {

    }
}