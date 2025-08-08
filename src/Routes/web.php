<?php

use src\Controllers\AuthController;

// Connexion à la base (à adapter selon ta config)
$pdo = new PDO('mysql:host=localhost;dbname=todoapp', getenv('DB_USER'), getenv('DB_PASS'));
$controller = new AuthController($pdo);

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

    // dashboard à faire plus tard
    default:
        echo "Page non trouvée.";
    break;
}
