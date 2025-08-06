<?php

namespace App\Core;

class Router {
    public function handleRequest() {

        $uri = $_GET['page'] ?? 'home';

        switch ($uri) {

            case 'login':
                require_once __DIR__ . '/../Controller/AuthController.php';
                $controller = new \App\Controller\AuthController();
                $controller->login();
            break;

            case 'home':
            default:
                require_once __DIR__ . '/../Controller/HomeController.php';
                $controller = new \App\Controller\HomeController();
                $controller->index();
            break;
        }
    }
}
