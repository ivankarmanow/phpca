# Расширение функциональности

Пример расширения функциональности шаблона под свои потребности. 
В проекте уже реализованы многие части, не использованные в итоговой функциональности (не написаны контроллеры и вьюшки).
Здесь будет описан пример добавления функции получения пользователя по ID, начиная с добавления метода в DbGateway и заканчивая представлением (view)

## Обзор необходимых изменений

1. `DbGateway` - добавить сигнатуру `get_user_by_id` и `get_user_by_email`
2. `MySqlGateway` - добавить реализацию этих методов через PDO
3. `UserRepo` - добавить абстракцию методов из Gateway для использования в контроллерах
4. `GetUserView` - создать представение получения пользователя и соответствующий шаблон `get.php`
5. `UserController` - добавить метод get_user, который будет использовать репозиторий
6. `di.php` - добавить в ViewsContainer новое представление
7. `index.php` - добавить маршрут для созданной функции


### DbGateway

Данный интерфейс унифицирет доступ к базе данных и хранит лишь сигнатуры методов. Так как это низкоуровневая абстракция, добавим 2 сигнатуры:
```php
public function get_user_by_id(int $id): User | bool;
public function get_user_by_email(string $email): User | bool;
```
Возвращаемое значение задаём в User (модель) и bool для возвращения ошибок и пустых значений

### MySqlGateway

Теперь реализуем методы интерфейса:
```php
public function get_user_by_id(int $id): User | bool
{
    $sth = $this->dbh->prepare("SELECT :class, users.* FROM users WHERE id = :user_id");
    $sth->bindValue(":class", User::class);
    $sth->bindValue(":user_id", $id);
    $sth->execute();
    return $sth->fetch();
}

public function get_user_by_email(string $email): User | bool
{
    $sth = $this->dbh->prepare("SELECT :class, users.* FROM users WHERE email = :email");
    $sth->bindValue(":class", User::class);
    $sth->bindValue(":email", $email);
    $sth->execute();
    return $sth->fetch();
}
```
Функции выполняют простейшие `SELECT`-запросы через PDO с использованием модификаторов `PDO::FETCH_CLASS | PDO::FETCH_CLASSTYPE`. 
Это позволяет автоматически парсить результат запросов в модель User. Чтобы PDO понял, в какой класс надо преобразовать результат, мы указываем параметр :class, в который потом подставляется название класса User.

### UserRepo

В репозитории будет уже не два метода а один - принимающий на вход **либо** id, **либо** email. Тем самым мы сократим код контроллера для различных входных данных и абстрагируем выбор ключа выборки в репозиторие, а не в контроллере
```php
public function get_user(int $id = null, string $email = null): User
{
if (!empty($id)) {
    $user = $this->db->get_user_by_id($id);
} else if (!empty($email)) {
    $user = $this->db->get_user_by_email($email);
} else {
    throw new ValueError();
}
return $user;
}
```
Функция крайне простая. Весь её смысл - выбрать не пустой переданный параметр и вызвать соответствующую функцию слоя базы данных.

### GetUserView

Логично было бы после репозитория написать контроллер, но логика контроллера полностью завязана на представлении, поэтому сначала напишем его.

```php
<?php

namespace views\user;

use core\protocols\View;

class GetUserView extends View
{
    public array $user;

    public function __construct(
        protected string $templateFile = "get",
        protected array $data = array()
    ) {
        parent::__construct($this->templateFile, $data);
    }

    public function render(array $data = array()): void
    {
        if (!isset($data['user'])) {
            $user = $this->user;
        } else {
            $user = $data['user'];
        }
        if (empty($data)) {
            $data = $this->data;
        }
        require __DIR__ . "/templates/" . $this->templateFile . ".php";
    }
}
```
Представление имеет такую же структуру как и другие (ListUserView, AddUserView).
В качестве передаваемых данных выступает переменная $user, в которую передаётся полученный контролером объект модели пользователя.
В итоге загружается страница templates/get.php:
```php
<?php require __DIR__ . "/../../templates/header.html" ?>
<?php if (isset($user)) { ?>
    <b>ID: </b> <?= $user->id ?>
    <b>Name: </b> <?= $user->name ?>
    <b>Email: </b> <?= $user->email ?>
<?php } else {?>
    <b>User not found!</b>
<?php }; require __DIR__ . "/../../templates/footer.html" ?>
```

### UserController

В контроллер надо добавить лишь одну небольшую функцию: 
```php
public function get(Request $request)
{
    $user = $this->repo->get_user(...$request->getParams("id", "email"));
    $view = $this->views[GetUserView::class];
    $view->user = $user;
    $view->render();
}
```
Она получает объект модели пользователя через репозиторий и передаёт его в представление

### di.php

В этот файл добавляем лишь новое представление:
```php
$di[ViewsContainer::class] = function (DIContainer $container) {
    $views = new ViewsContainer();
    $views[UserController::class] = [
        ListUsersView::class => new ListUsersView(),
        AddUserView::class => new AddUserView(),
        GetUserView::class => GetUserView(),
    ];
    return $views;
};
```

### index.php

И наконец финальное действие - добавляем маршрут для созданного действия:
```php
$user_router->get("/get", "get");
```

## Заключение

Это весь код, который надо изменить или создать для добавления новой функции получения пользователя по ID или Email.
Потребуется больше кода, если новый функционал потребует новые модели или новые контроллеры (и соответственно репозитории), однако даже в этом случае количество кода, которое необходимо изменить, стремится к минимуму.
