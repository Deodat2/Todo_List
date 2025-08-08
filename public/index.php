<?php

require_once __DIR__ . '/../src/config/bootstrap.php';
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/Routes/web.php';

use App\Core\Router;

$router = new Router();
$router->handleRequest();
