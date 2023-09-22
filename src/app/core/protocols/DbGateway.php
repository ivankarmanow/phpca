<?php

namespace core\protocols;

use core\models\CartElement;
use core\models\Category;
use core\models\User;
use core\models\Item;

/*
 * Интерфейс доступа к БД, Gateway
 * Абстрагирует работу с данными в БД с помощью моделей, загрузка данных в модели реализуется через репозитории (папка repos)
 */
interface DbGateway {
    public function create_user(User $user): void;

    public function get_user(int $id): User;

    public function get_user_by_email(string $email): User;

    public function check_user(string $email, string $password): bool;

    public function update_user(User $user): void;

    public function delete_user(int $id): void;

//    public function create_item(Item $item): void;
//
//    public function get_item(int $id): Item;
//
//    public function update_item(int $id, Item $item): void;
//
//    public function delete_item(int $id): void;
//
//    public function create_category(Category $category): void;
//
//    public function get_category(int $id): Category;
//
//    public function update_category(int $id, Category $category): void;
//
//    public function delete_category(int $id): void;
//
//    public function add_to_cart(CartElement $cart_element): void;
//
//    public function del_from_cart(int $user_id, int $element_id): void;
//
//    public function buy_cart(int $user_id): void;
//
//    public function clear_cart(int $user_id): void;
//
//    public function list_items(): array;
//
//    public function list_categories(): array;
//
    public function list_users(): array;
//
//    public function list_cart(int $user_id): array;
//
//    public function attach_item_to_category(Item $item, Category $category): void;
//
//    public function deattach_item_from_category(int $item_id, int $category_id): void;
}