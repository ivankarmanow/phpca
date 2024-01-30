<?php

namespace controllers;

use core\protocols\Controller;
use core\protocols\Repo;
use core\protocols\View;
use core\routing\Request;
use views\RESTView;

class IndexController extends Controller
{
    public function __construct(
        protected View $view
    ) {
        parent::__construct($view);
    }

    public function index(Request $request): void
    {
        $this->view->render(["It's work"]);
    }
}