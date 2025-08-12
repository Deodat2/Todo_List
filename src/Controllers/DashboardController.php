<?php

namespace App\Controllers;

use App\Controllers\TaskController;
use PDO;

class DashboardController
{
    private PDO $db;

    public function index(PDO $db)
    {

        $this->db = $db;

        $controllerTask = new TaskController($this->db);

        $controllerTask->index();

        require __DIR__ . '/../View/dashboard.php';
    }
}
