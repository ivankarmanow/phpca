<?php

namespace views;


use core\protocols\View;

class HTMLView implements View
{
    public function __construct(
        protected array $templates
    ) { }

    public function render(
        array $data = array(),
        ?string $template = null
    ): void
    {
        require __DIR__ . "/templates/" . $this->templates[$template] . ".php";
    }
}