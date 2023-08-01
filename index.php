<?php

require_once __DIR__ . '/init.php';

use App\Models\User;
use Core\Router;

require_once __DIR__ . '/web/routes.php';





$router = new Router();

$router->run();
