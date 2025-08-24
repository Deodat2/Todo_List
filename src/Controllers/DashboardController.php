<?php

namespace App\Controllers;

use App\Controllers\TaskController;
use PDO;

class DashboardController
{
    private PDO $db;

    // DashboardController.php
    public function index(PDO $db)
    {
        $this->db = $db;

        $controllerTask = new TaskController($this->db);

        // Récupère les tâches depuis TaskController
        $tasks = $controllerTask->getTasksForCurrentUser();

        require __DIR__ . '/../View/dashboard.php';
    }
    
}
