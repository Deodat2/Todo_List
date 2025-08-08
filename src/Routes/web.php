<?php

use App\Controllers\UserController;

global $db; // récupère le PDO défini dans config.php
$controller = new UserController($db);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

session_start();

switch ($uri) {
    case '/login':
        if ($method === 'GET') $controller->showLoginForm();
        if ($method === 'POST') $controller->login($_POST);
    break;

    case '/register':
        if ($method === 'GET') $controller->showRegisterForm();
        if ($method === 'POST') $controller->register($_POST);
    break;

    case '/logout':
        $controller->logout();
    break;

    default:
        echo "Page non trouvée.";
    break;
}