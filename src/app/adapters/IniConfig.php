<?php

namespace adapters;

use core\protocols\Config;

/*
 * Реализация загрузчика конфига из .ini файла
 */
class IniConfig extends Config {
    public function __construct(string $file_name) {
        $data = parse_ini_file($file_name);
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}