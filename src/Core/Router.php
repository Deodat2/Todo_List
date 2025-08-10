<?php

namespace App\Core;

use App\Controllers\UserController;
use App\Controllers\DashboardController;
use PDO;

class Router {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function handleRequest(): void {
        $uri = $_GET['page'] ?? 'login';

        switch ($uri) {
            case 'login':
                $controller = new UserController($this->db);
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->login($_POST);
                } else {
                    $controller->showLoginForm();
                }
                break;

            case 'register':
                $controller = new UserController($this->db);
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->register($_POST);
                } else {
                    $controller->showRegisterForm();
                }
                break;

            case 'logout':
                $controller = new UserController($this->db);
                $controller->logout();
                break;

            case 'dashboard':
                if (!$this->isAuthenticated()) {
                    header('Location: /?page=login');
                    exit;
                }
                $controller = new DashboardController();
                $controller->index();
                break;

            default:
                header('HTTP/1.0 404 Not Found');
                echo "Page non trouvée";
                break;
        }
    }

    private function isAuthenticated(): bool {
        return isset($_SESSION['user_id']);
    }
}