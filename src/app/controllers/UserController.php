<?php

namespace controllers;

use core\protocols\Controller;
use repos\StubRepo;
use repos\UserRepo;
use core\routing\Request;
use core\ViewsContainer;
use views\user\ListUsersView;

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