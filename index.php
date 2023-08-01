<?php

require_once __DIR__ . '/vendor/autoload.php';

use Core\Router;

require_once __DIR__ . '/web/routes.php';

$router = new Router();

$router->run();
