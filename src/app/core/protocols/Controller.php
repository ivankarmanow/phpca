<?php

namespace protocols;

use protocols\Repo;
use core\ViewsContainer;

class Controller {
    public $views_dir;

    public function __construct(
        public Repo $repo,
        public ViewsContainer $views
    ) {
        $this->views_dir = $this->views[self::class];
    }
}