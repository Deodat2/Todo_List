<?php

namespace App\Controllers;

use App\Model\Task;
use App\Model\Subtask;
use PDO;

class TaskController
{
    private PDO $db;
    private Task $taskModel;
    private Subtask $subtaskModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->taskModel = new Task($db);
        $this->subtaskModel = new Subtask($db);
    }

    /** ------- Connected USER ------- */
    private function requireLogin(): int
    {
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            header('Location: /?page=login');
            die();
        }

        return $userId; // retourne l'ID si connecté
    }

    /** ------- TASKS ------- */

    /**
     * Affiche la liste des tâches de l'utilisateur connecté
    **/
    public function getTasksForCurrentUser(): array
    {
        $userId = $this->requireLogin();

        return $this->taskModel->getAllByUser($userId);

    }

    /**
     * Affiche le formulaire de création de tâche
    **/
    public function createForm(): void
    {
        require_once __DIR__ . '/../View/task/create.php';
    }

    // Traite la création de tâche
    public function create(array $data): void
    {
        $userId = $this->requireLogin();

        $title = trim($data['title'] ?? '');

        $description = trim($data['description'] ?? '');

        $tags = $this->sanitizeTags($data['tags'] ?? []);

        if ($title === '') {

            $_SESSION['error'] = "Le titre est obligatoire.";

            header("Location: /?page=tasks&subpage=create");

            die();

        }

        if (empty($tags)) {

            $_SESSION['error'] = "Chaque tâche doit avoir au moins un tag.";

            header("Location: /?page=tasks&subpage=create");
            
            die();

        }

        $success = $this->taskModel->create($userId, $title, $description, $tags);

        if ($success) {

            $_SESSION['success'] = "Tâche créée avec succès.";

            header('Location: /?page=dashboard');

            die();

        } else {

            $_SESSION['error'] = "Erreur lors de la création de la tâche.";

            header("Location: /?page=tasks&subpage=create");

            die();

        }

    }

    // Affiche le formulaire d'édition d'une tâche
    public function editForm(int $id): void
    {
        $task = $this->taskModel->getById($id);

        if (!$task) {

            header('HTTP/1.0 404 Not Found');

            echo "Tâche non trouvée";

            die();

        }

        if ($task['user_id'] !== ($_SESSION['user_id'] ?? 0)) {

            header('HTTP/1.0 403 Forbidden');

            echo "Accès refusé";

            die();

        }

        require_once __DIR__ . '/../View/task/edit.php';

    }

    // Traite la mise à jour d'une tâche
    public function update(int $id, array $data): void
    {
        $task = $this->taskModel->getById($id);

        if (!$task || $task['user_id'] !== ($_SESSION['user_id'] ?? 0)) {

            header('HTTP/1.0 403 Forbidden');

            echo "Accès refusé";

            die();

        }

        $title = trim($data['title'] ?? '');

        $description = trim($data['description'] ?? '');

        $tags = $this->sanitizeTags($data['tags'] ?? []);

        if ($title === '') {

            $_SESSION['error'] = "Le titre est obligatoire.";

            header("Location: /?page=tasks&subpage=edit&id={$id}");

            die();

        }

        $success = $this->taskModel->update($id, $title, $description, $tags);

        if ($success) {

            $_SESSION['success'] = "Tâche mise à jour avec succès.";

            header('Location: /?page=dashboard');

            die();

        } else {

            $_SESSION['error'] = "Erreur lors de la mise à jour de la tâche.";

            header("Location: /?page=tasks&subpage=edit&id={$id}");

            die();

        }

    }

    public function updateStatus(int $id, string $status): void
    {
        $task = $this->taskModel->getById($id);

        if (!$task || $task['user_id'] !== ($_SESSION['user_id'] ?? 0)) {

            header('HTTP/1.0 403 Forbidden');

            echo "Accès refusé";

            die();

        }

        $allowed = ['created', 'in_progress', 'paused', 'suspended', 'abandoned', 'complete'];

        if (!in_array($status, $allowed, true)) {
            $_SESSION['error'] = "Statut invalide.";
            header("Location: /?page=tasks&subpage=edit&id={$id}");
            exit;
        }

        $success = $this->taskModel->updateStatus($id, $status);

        if ($success) {

            $_SESSION['success'] = "Tâche mise à jour avec succès.";

            header('Location: /?page=dashboard');

            die();

        } else {

            $_SESSION['error'] = "Erreur lors de la mise à jour de la tâche.";

            header("Location: /?page=tasks&subpage=edit&id={$id}");

            die();

        }

    }

    // Supprime une tâche
    public function delete(int $id): void
    {
        $task = $this->taskModel->getById($id);

        if (!$task || $task['user_id'] !== ($_SESSION['user_id'] ?? 0)) {

            header('HTTP/1.0 403 Forbidden');

            echo "Accès refusé";

            die();

        }

        $this->taskModel->delete($id);

        $_SESSION['success'] = "Tâche supprimée avec succès.";

        header('Location: /?page=dashboard');

        die();

    }

    /**
     * Nettoie et valide les tags envoyés
     */
    private function sanitizeTags(array|string $tagsInput): array
    {
        if (!is_array($tagsInput)) {

            $tagsInput = [$tagsInput];

        }

        $tags = array_map('trim', $tagsInput);

        $tags = array_filter($tags, fn($tag) => $tag !== '');

        return $tags;
    }

}