<?php

use di_container\DIContainer;
use adapters\MySqlGateway;
use protocols\Config;

$di = new DIContainer();
$di['Config'] = function (DIContainer $container) {
    return new Config("config.ini");
};
$di['DbGateway'] = "MySqlGateway";
