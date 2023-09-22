<?php

namespace views\user;

use core\protocols\View;

/*
 * Представление (View) списка пользователей
 * Отдаёт страницу list.php и прокидывает в неё название страницы $title и список пользователей $users
 */
class ListUsersView extends View {
//    protected string $templateFile = "list";
    public string $title = "Список пользователей";
    public array $users;

    public function __construct(
        protected string $templateFile = "list",
        protected array $data = array()
    ) {
        parent::__construct($this->templateFile, $data);
    }

    public function render(array $data = array()): void
    {
        if (!isset($data['title'])) {
            $title = $this->title;
        }
        if (!isset($data['users'])) {
            $users = $this->users;
        }
        if (empty($data)) {
            $data = $this->data;
        }
        require __DIR__ . "/templates/" . $this->templateFile . ".php";
    }
}