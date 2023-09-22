<?php

namespace core\protocols;

use core\protocols\Repo;
use core\ViewsContainer;

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