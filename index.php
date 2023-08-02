<?php

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/web/routes.php';

use Core\Router;


$router = new Router();

$router->run();
