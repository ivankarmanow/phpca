# Шаблон чистой архитектуры на PHP

Данный шаблон представляет собой пример реализации чистой архитектуры на PHP

## Установка и запуск

Для запуска просто склонируйте репозиторий
```shell
git clone git@github.com:ivankarmanow/phpca.git
```
Либо скачайте репозиторий в виде ZIP-архива и распакуйте в любую папку.

Далее необходимо настроить базу данных и создать все необходимые таблицы. 
Данные для подключения к базе данных MySQL через PDO настраиваются в файле [`config.ini`](config.ini):

```ini
db_dsn = "mysql:dbname=test_database;host=localhost"
db_user = root
db_password = ""
```

Чтобы создать все необходимые таблицы, необходимо выполнить скрипт [`setup.php`](setup.php)

```shell
php setup.php
```

Далее запускайте проект через любой веб-сервер

## Структура

Одна из основных составляющих **ЧА (Чистой Архитектуры)** является упорядоченность и структурированность проекта.
Карта файлов проекта выглядит так:
```
|   .htaccess
|   config.ini
|   index.php
|   README.md
|   setup.php
|
└───src
    └───app
        │   di.php
        │
        ├───adapters
        │       IniConfig.php
        │       MySqlGateway.php
        │
        ├───controllers
        │       StubController.php
        │       UserController.php
        │
        ├───core
        │   │   DIContainer.php
        │   │   ViewsContainer.php
        │   │
        │   ├───exceptions
        │   │       DependencyNotFound.php
        │   │       DispatcherHasNotParents.php
        │   │       EmailExists.php
        │   │       FactoryAlreadyExists.php
        │   │       IncludeParentRouter.php
        │   │       MethodNotAllowed.php
        │   │       NotFound.php
        │   │       RouterYetIncluded.php
        │   │       ValueError.php
        │   │
        │   ├───models
        │   │       Cart.php
        │   │       CartElement.php
        │   │       Category.php
        │   │       Item.php
        │   │       User.php
        │   │
        │   ├───protocols
        │   │       Config.php
        │   │       Controller.php
        │   │       DbGateway.php
        │   │       Model.php
        │   │       Repo.php
        │   │       View.php
        │   │
        │   └───routing
        │           Dispatcher.php
        │           methods.php
        │           Request.php
        │           Router.php
        │
        ├───repos
        │       StubRepo.php
        │       UserRepo.php
        │
        └───views
            ├───templates
            │       footer.html
            │       header.html
            │
            └───user
                │   add.php
                │   delete.php
                │   get.php
                │   ListUsersView.php
                │   update.php
                │
                └───templates
                        add.html
                        delete.html
                        get.html
                        list.php
                        update.html
```

Теперь подробнее про каждый каталог и файл:
- [`.htaccess`](.htaccess) - настройка поведения веб-сервера. В данном случае используется для перенаправления всех запросов на файл [`index.php`](index.php), который производит маршрутизацию.
- [`config.ini`](config.ini) - настройки конфигурации приложения. Используется модулем [`IniConfig`](src/app/adapters/IniConfig.php).
- [`index.php`](index.php) - основной файл, точка входа. На него перенаправляются все входящие запросы, которые далее передаются в контроллеры. Файл содержит инициализацию и конфигурацию роутеров.
- [`setup.php`](setup.php) - скрипт для стартового создание таблиц для моделей БД.
- [`src/app/`](src/app/) - базовая директория проекта, содержит весь код. Далее рассмотрим её подробнее.

### [Core](src/app/core)
Базовые компоненты приложения, такие как модели, исключения и протоколы. При правильной настройке меняется редко, чаще всего только добавляются модели.
Думайте об этой директории как о скелете приложения, на котором основаны все компоненты.
- [`exceptions/`](src/app/core/exceptions/) - исключения, используемые приложением. Название каждого класса (файла) говорит само за себя.
- [`models/`](src/app/core/models/) - модели данных, упрощающие взаимодействие между различными компонентами приложения, к примеру упрощает доступ к данным из базы данных.
- [`DIContainer.php`](src/app/core/DIContainer.php) - **контейнер** зависимостей. В проекте используется концепция **Dependency Injection** (внедрение зависимостей). 
Благодаря ей класс не знает, как получить ту или иную зависимость (к примеру объект подключения к базе данных или конфиг), а лишь предоставляет список нужных ему компонентов.
Остальное происходит автоматически, все необходимые компоненты загружаются из контейнера.

  > [!NOTE]
  > Контейнер хранит не сами объекты, а фабрики для их создания, но сохраняет готовые объекты в кэш для ускорения повторного использования

- [`ViewsContainer.php`](src/app/core/ViewsContainer.php) - предоставляет простейшую реализацию массивоподобного контейнера, который хранит список представлений (View) необходимых каждому контроллеру.
Благодаря этому контроллерам не нужно искать нужное представление по файловой системе, достаточно загрузить нужный класс из контейнера, который также передаётся через DI.

#### [Protocols](src/app/core/protocols)

В этой папке лежат **протоколы** - интерфейсы, абстрактные и обычные классы, не используемые напрямую, а лишь предоставляющие протокол взаимодействия с чем-либо
Это позволяет всем компонентам унифицировать доступ к другим, все связи происходят между протоколами, реализации которых остаются за кулисами.
- [`Config.php`](src/app/core/protocols/Config.php) - модель конфига приложения. Обратите внимание, что не указан способ загрузки данных, это возлагается на реализацию.
- [`Controller.php`](src/app/core/protocols/Controller.php) - базовый класс-контроллер. Не задаёт строго ничего кроме работы с представлениями, так как это общее для всех контроллеров.
- [`DbGateway.php`](src/app/core/protocols/DbGateway.php) - интерфейс абстракции базы данных от кода. Данный интерфейс предоставляет базовые функции работы с моделями, которые можно использовать в более высокоуровневых компонентах без учёта особеноостей работы с БД.

  > [!NOTE] 
  > Реализацией интерфейса может быть что угодно:
   >- SQL СУБД (MySQL, PostgreSQL)
   >- NoSQL СУБД (MongoDB, Redis)
   >- Файлы (CSV, JSON, YAML)
   >- Object storage, ex Amazon S3
  >
  > Использование интерфейса даёт свободу выбора системы хранения данных. Если вы решите поменять базу данных проекта, вам не придётся переписывать весь код, достаточно будет изменить лишь класс, предоставляющий интерфейс `DbGateway`.

- [`Model.php`](src/app/core/protocols/Model.php) - базовый класс для моделей данных. Устанавливает только два статических поля: `$tablename` и `$create_table`.
Первое хранит название таблицы в БД, которую представляет модель, а второе - код создания таблицы в БД для файла `setup.php`.
- [`Repo.php`](src/app/core/protocols/Repo.php) - самый простой интерфейс. Не делает ничего и служит чисто для метаданных, чтобы указать, что класс является репозиторием. Возможно в будущем обретёт свою функциональность.
- [`View.php`](src/app/core/protocols/View.php) - базовый класс представления. Реализует интерфейс `ArrayAccess`, что позволяет обращаться к экземплярам классов-реализаций как к массивам.
Также определяет метод `render`, производящий "отрисовку" страницы по шаблону. Если быть более точным, загружает шаблон и передаёт данные в него.

#### [Routing](/src/app/core/routing)

Очень интересный компонент проекта, так как написан полностью с нуля и без шаблона. 
Содержит три класса, реализующих **роутинг** - _маршрутизацию_ запросов:
- [`Request.php`](src/app/core/routing/Request.php) - простой класс абстракции HTTP-запроса. Даёт доступ к запрошенному пути, методу запроса и его параметрам. Объекты этого класса передаются в контроллеры, предоставляя доступ к данным, пришедшим с фронтенда.
- [`Router.php`](src/app/core/routing/Router.php) - класс, реализующий **роутинг** запросов. Для маршрутизации нужно создать его экземпляр, передать в него требуемый контроллер и префикс пути.
Затем с помощью функции `register` и её шорткатов для методов `GET` и `POST`: `get`, `post`. В функцию необходимо передать регистрируемый **эндпоинт** (путь) и метод контроллера, который будет обрабатывать данный запросы данного эндпоинта.

  > [!IMPORTANT]
  > Ещё есть интересная функция, сильно упрощающая вложенность эндпоинтов, `include_router`.
  > 
  > Она включает один роутер в другой, передавая управление дочерним роутером родительскому. Таким образом, сначала пытаться обработать запрос будет пытаться родительский роутер, а если не сможет, то передаст управление дочерним. 

Таким образом, запрос _рекурсивно_ обойдёт все роутеры, как по веткам **дерева**.
- [`Dispatcher.php`](src/app/core/routing/Dispatcher.php) - по сути является просто-напросто корневым роутером, принимающим запрос и передающим его далее вглубь. Не может быть включен в другой роутер, а также желательно не регистрировать обработчики напрямую через него, хотя это и не запрещается.
- [`methods.php`](src/app/core/routing/methods.php) - служебный файл, хранящий методы, обрабатывающиеся роутерами. По умолчанию все роутеры поддерживают только методы `GET` и `POST`.

### [Adapters](src/app/adapters)

**Адаптер** - _паттерн проектирования_, заключающийся в создании интерфейса одного компонента для других. В этом проекте адаптеры являются просто реализациями протоколов.
- [`MySqlGateway.php`](src/app/adapters/MySqlGateway.php) - реализация [`DbGateway`](src/app/core/protocols/DbGateway.php), использующая `MySQL` через `PDO`. Вся работа с базой данных происходит в этом классе.
- [`IniConfig.php`](src/app/adapters/IniConfig.php) - реализация протокола [`Config`](src/app/core/protocols/Config.php), использующая в качестве источника настроен конфигурации файл `.ini`. Вы легко можете создать другую реализацию для хранения настроек в другом формате, например `JSON` или в переменных окружения.

### [Repos](src/app/repos)

Репозитории представляют слой абстракции между [`DbGateway`](src/app/core/protocols/DbGateway.php) и контроллерами. Это нужно для того, чтобы [`DbGateway`](src/app/core/protocols/DbGateway.php) не заботился о валидации и преобразовании поступающих от пользователя данных в модели,
а контроллерам не нужно было работать с моделями. Через данный слой вы можете, к примеру, добавить дополнительные проверки для методов.
- [`StubRepo.php`](src/app/repos/StubRepo.php) - служебный контроллер, который нигде напрямую не используется и не имеет никаких методов и полей.

  > [!WARNING]
  > Нужен он для того, чтобы обойти особенность PHP при работе с типизацией в сигнатурах наследуемых методов.
  > 
  > Если базовый класс [`Controller`](src/app/core/protocols/Controller.php) будет в конструкторе зависеть от интерфейса [`Repo`](src/app/core/protocols/Repo.php), то в дочерних от [`Controller`](src/app/core/protocols/Controller.php) классах не получится использовать в качестве того же аргумента классы, унаследованные или реализующие интерфейс [`Repo`](src/app/core/protocols/Repo.php).
  > 
  > Поэтому создана эта _заглушка_.

- [`UserRepo.php`](src/app/repos/UserRepo.php) - репозиторий для работы с пользователями. Содержит несколько функций, реализующих нужную функциональность на основе базовых методов [`DbGateway`](src/app/core/protocols/DbGateway.php). Используется контроллером для доступа к БД.

### [Controllers](src/app/repos)

В общих чертах, задача контроллера - получать данные от пользователя, передавать их в репозиторий, а также отдавать данные из репозитория в представление, передавая управление ему. 
При грамотной архитектуре контроллеры - связующее звено между всеми компонентами, однако их код самый маленький, за счёт абстракции многих действий в другие слои и компоненты.
- [`StubController.php`](src/app/controllers/StubController.php) - используется для той же цели, что и [`StubRepo`](src/app/repos/StubRepo.php), однако также используется в качестве контроллера диспетчера, так как диспетчер не должен сам обрабатывать что-либо.
- [`UserController.php`](src/app/controllers/UserController.php) - основной контроллер, реализующий все действия надо пользователями. На данный момент реализованы только методы `add` и `list`.

### [Views](src/app/views)

Представления, более известные как **View** (_вьюшки_) - классы, отображающие интерфейс пользователя и передающие в него данные из контроллеров и моделей.
- [`templates/`](src/app/views/templates) - каталог базовых HTML шаблонов страниц, которые используются всеми остальными представлениями.
- [`user/`](src/app/views/user) - группа представлений для работы с пользователями, связаны с контроллером [`UserController`](src/app/controllers/UserController.php).
  - [`user/templates/`](src/app/views/user/templates) - HTML страницы, используемые представлениями `user`.
  - [`user/ListUsersView.php`](src/app/views/user/ListUsersView.php) - единственная пока реализованная вьюшка для представления списка зарегистрированных пользователей. Прокидывает на страницу список пользователей, полученный от контроллера.

### [di.php](src/app/di.php)

Данный файл хочу выделить в отдельный раздел, так как он связывает всё воедино, загружая все зависимости.

Хоть объём кода и не очень большой, значение этого файла очень большое. 
В нём определяются используемые реализации протоколов, используемые контроллеры и представления. 
Если вы захотите изменить реализацию какого-либо компонента, менять его нужно будет именно здесь.

Наш [`DIContainer`](src/app/core/DIContainer.php) написан таким образом, что может принимать два типа фабрик: классовые и функциональные.
1. В первом случае фабрикой выступает оператор `new`, а зависимости загружаются исходя из типов параметров конструктора класса.
   > К примеру, если у класса есть аргумент `UserRepo $repo` в конструкторе, то контейнер вместе с классом загрузит также и [`UserRepo`](src/app/repos/UserRepo.php), передав его в конструктор класса, но для этого в контейнере обязательно должна быть фабрика и для класса [`UserRepo`](src/app/repos/UserRepo.php).
2. Фабрики в виде функций позволяют реализовать больше функционала. Если в качестве фабрики передана функция, то контейнер передаёт экземпляр себя в качестве аргумента. Через этот аргумент функция может получить доступ к любой зависимости, уже хранящейся в контейнере.

   > [!IMPORTANT]
   > Таким образом, функции являются более гибким вариантом фабрики и позволяют кастомизировать процесс создания экземпляра зависимомсти, к примеру передать в неё заранее подготовленные элементы, как это делается в случае с [`ViewsContainer`](src/app/core/ViewsContainer.php).

## Заключение

На основе данного шаблона вы можете строить свои приложения, дополняя функциональность контроллерами, представлениями и моделями.

Также вы можете добавить свои протоколы и другие компоненты, к примеру для реализации кэширования или интеграции с внешними API.