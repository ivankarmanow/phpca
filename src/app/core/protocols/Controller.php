<?php

namespace core\protocols;

use core\protocols\Repo;
use core\ViewsContainer;

/*
 * Базовый класс контроллера
 * Должен быть переопределён
 * Каждый контроллер включает массив views представлений, берущийся из контейнера ViewsContainer для каждого контроллера
 */
class Controller {
    public $views;

    public function __construct(
//        protected Repo $repo,
        public ViewsContainer $views_container
    ) { }

    public function load_views(string $class)
    {
        $this->views = $this->views_container[$class];
//        var_dump($this->views);
    }
}