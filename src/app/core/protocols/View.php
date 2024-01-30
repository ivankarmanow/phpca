<?php

namespace core\protocols;

/*
 * Базовый класс представления (View)
 * Должен быть переопределён
 * Загружает файл по пути src/app/views/$ControllerGroup/templates/$templateFile.php
 * Прокидывает в шаблон данные через массив $data, а также другие поля, определяемые в дочерних классах
 * Реализует интерфейс массива
 */

interface View {
    public function render(array $data);
}