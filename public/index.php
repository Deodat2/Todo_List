<?php

require_once __DIR__ . '/../src/config/bootstrap.php';

use App\Core\Router;

$router = new Router();
$router->handleRequest();
