<?php

namespace App\Controllers;

use App\Model\Subtask;
use PDO;

class SubtaskController
{
    private PDO $db;
    private Subtask $subtaskModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->subtaskModel = new Subtask($db);
    }

    /** ------- SUBTASKS ------- */

    /**
     * Liste toutes les sous-tâches d’une tâche donnée
     */
    public function listSubtasks(int $taskId): void
    {
        // Charger la tâche associée
        $taskModel = new \App\Model\Task($this->db);
        $task = $taskModel->getById($taskId);

        if (!$task) {
            $_SESSION['error'] = "Tâche introuvable.";
            header("Location: /?page=dashboard");
            exit;
        }

        // Charger les sous-tâches
        $subtasks = $this->subtaskModel->getByTaskId($taskId);

        // Afficher la vue
        require __DIR__ . '/../View/partials/task-view.php';
    }

    /**
     * Ajoute une sous-tâche
     */
    public function createSubtask(int $taskId, array $data): void
    {
        $title = trim($data['title'] ?? '');
        $description = trim($data['description'] ?? '');
        $duration = trim($data['duration'] ?? '');

        if ($title === '') {
            $_SESSION['error'] = "Le titre de la sous-tâche est obligatoire.";
            header("Location: /?page=tasks&subpage=subtasks&id={$taskId}");
            exit;
        }

        if ($duration === '') {
            $_SESSION['error'] = "La durée de la sous-tâche est obligatoire.";
            header("Location: /?page=tasks&subpage=subtasks&id={$taskId}");
            exit;
        }

        $success = $this->subtaskModel->createSubtasks($taskId, $title, $description, $duration);

        if ($success) {
            $_SESSION['success'] = "Sous-tâche ajoutée avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de l’ajout de la sous-tâche.";
        }

        header("Location: /?page=tasks&subpage=subtasks&id={$taskId}");
        exit;
    }

    /**
     * Met à jour une sous-tâche
     */
    public function updateSubtask(int $id, array $data): void
    {
        $title = trim($data['title'] ?? '');
        $description = trim($data['description'] ?? '');

        if ($title === '') {
            $_SESSION['error'] = "Le titre est obligatoire.";
            header("Location: /?page=subtasks&subpage=edit&id={$id}");
            exit;
        }

        $success = $this->subtaskModel->updateSubtasks($id, $title, $description);

        if ($success) {
            $_SESSION['success'] = "Sous-tâche mise à jour avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour.";
        }

        header("Location: /?page=subtasks&subpage=edit&id={$id}");
        exit;
    }

    /**
     * Supprime une sous-tâche
     */
    public function deleteSubtask(int $id, int $taskId): void
    {
        $this->subtaskModel->deleteSubtasks($id);
        $_SESSION['success'] = "Sous-tâche supprimée.";
        header("Location: /?page=tasks&subpage=subtasks&id={$taskId}");
        exit;
    }

    /**
     * Met à jour uniquement le statut
     */
    public function updateSubtaskStatus(int $id, string $status, int $taskId): void
    {
        $allowed = ['created', 'in_progress', 'paused', 'suspended', 'abandoned'];

        if (!in_array($status, $allowed, true)) {
            $_SESSION['error'] = "Statut invalide.";
            header("Location: /?page=tasks&subpage=subtasks&id={$taskId}");
            exit;
        }

        $this->subtaskModel->updateSubtasksStatus($id, $status);
        $_SESSION['success'] = "Statut de la sous-tâche mis à jour.";
        header("Location: /?page=tasks&subpage=subtasks&id={$taskId}");
        exit;
    }
}
