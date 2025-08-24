<?php

require_once __DIR__ . '/../src/config/bootstrap.php';
require_once __DIR__ . '/../src/config/config.php';

use App\Core\Router;

$router = new Router($db);
$router->handleRequest();
