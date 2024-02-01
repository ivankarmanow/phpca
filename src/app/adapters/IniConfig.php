<?php

namespace adapters;

use core\config\Config;
use ReflectionClass;

/*
 * Реализация загрузчика конфига из .ini файла
 */
class IniConfig extends Config {
    public function __construct(string $file_name) {
        $data = parse_ini_file($file_name, true);
        foreach ($data as $section => $params) {
            if (property_exists($this, $section)) {
                if (is_array($params)) {
                    $reflector = new ReflectionClass($this::class);
                    $property = $reflector->getProperty($section);
                    $type = $property->getType()->getName();
                    if (class_exists($type)) {
                        $this->$section = new $type();
                        foreach ($params as $key => $value) {
                            if (property_exists($this->$section, $key)) {
                                $this->$section->$key = $value;
                            }
                        }
                    }
                } else {
                    $this->$key = $value;
                }
            }
        }
    }
}