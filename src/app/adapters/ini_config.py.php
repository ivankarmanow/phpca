<?php

use protocols\Config;


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