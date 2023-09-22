<?php

spl_autoload_register(function ($class) {
    $class_path = str_replace('\\', '/', $class);

    $file =  __DIR__ . '\\src\\app\\' . $class_path . '.php';

    if (file_exists($file)) {
        require $file;
    } else {
        echo $file;
    }
});

use adapters\MySqlGateway;
use adapters\IniConfig;
use core\models;

$config = new IniConfig("config.ini");
$db = new MySqlGateway($config);

$models = [
    models\User::class,
];

foreach ($models as $model) {
    $db->create_model($model);
}