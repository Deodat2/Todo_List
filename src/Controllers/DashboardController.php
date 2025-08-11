<?php

namespace App\Controllers;

class DashboardController
{
    public function index()
    {
        // Ici tu peux récupérer les données nécessaires pour afficher dans le dashboard,
        // comme les tâches, stats, etc. Pour l'instant, on affiche juste la vue.

        require __DIR__ . '/../View/dashboard.php';
    }
}
