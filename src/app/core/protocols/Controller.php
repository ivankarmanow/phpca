<?php

namespace core\protocols;

use views\RESTView;

/*
 * Базовый класс контроллера
 * Должен быть переопределён
 * Каждый контроллер включает массив views представлений, берущийся из контейнера ViewsContainer для каждого контроллера
 */
class Controller {
    public function __construct(
        protected View $view
    ) { }

//    public function load_views(string $class): void
//    {
//        $this->views = $this->views_container[$class];
////        var_dump($this->views);
//    }
}