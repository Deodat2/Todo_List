<?php

namespace App\Controllers;

use App\Model\Task;
use PDO;

class TaskController
{
    private PDO $db;
    private Task $taskModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->taskModel = new Task($db);
    }

    // Affiche la liste des tâches de l'utilisateur connecté
    public function getTasksForCurrentUser(): array
    {
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {

            header('Location: /?page=login');

            exit;

        }

        return $this->taskModel->getAllByUser($userId);
        
    }

    // Affiche le formulaire de création de tâche
    public function createForm(): void
    {
        require_once __DIR__ . '/../View/task/create.php';
    }

    // Traite la création de tâche
    public function create(array $data): void
    {
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {

            header('Location: /?page=login');

            exit;

        }

        $title = trim($data['title'] ?? '');

        $description = trim($data['description'] ?? '');

        $tagsInput = $_POST['tags'] ?? [];

        // S'assurer que c'est bien un tableau
        if (!is_array($tagsInput)) {

            $tagsInput = [$tagsInput];

        }

        // Nettoyer chaque tag
        $tags = array_map(function($tag) {

            return trim($tag);

        }, $tagsInput);

        // Supprimer les tags vides
        $tags = array_filter($tags, function($tag) {

            return $tag !== '';

        });

        if ($title === '') {

            $error = "Le titre est obligatoire.";

            require_once __DIR__ . '/../View/task/create.php';

            return;

        }

        // Vérification qu’il y a au moins un tag
        if (empty($tags)) {

            $_SESSION['error'] = "Chaque tâche doit avoir au moins un tag.";

            header("Location: /?page=tasks&subpage=create");

            exit;
        }

        $success = $this->taskModel->create($userId, $title, $description, $tags);

        if ($success) {

            header('Location: /?page=dashboard');

            exit;

        } else {

            $error = "Erreur lors de la création de la tâche.";

            require_once __DIR__ . '/../View/task/create.php';

        }

    }

    // Affiche le formulaire d'édition d'une tâche
    public function editForm(int $id): void
    {
        $task = $this->taskModel->getById($id);

        if (!$task) {

            header('HTTP/1.0 404 Not Found');

            echo "Tâche non trouvée";

            exit;

        }

        // Sécurité : vérifier que la tâche appartient à l'utilisateur connecté
        if ($task['user_id'] !== ($_SESSION['user_id'] ?? 0)) {

            header('HTTP/1.0 403 Forbidden');

            echo "Accès refusé";

            exit;

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

            exit;

        }

        $title = trim($data['title'] ?? '');

        $description = trim($data['description'] ?? '');

        $tagsInput = $_POST['tags'] ?? [];

        // S'assurer que c'est bien un tableau
        if (!is_array($tagsInput)) {

            $tagsInput = [$tagsInput];

        }

        // Nettoyer chaque tag
        $tags = array_map(function($tag) {

            return trim($tag);

        }, $tagsInput);

        // Supprimer les tags vides
        $tags = array_filter($tags, function($tag) {

            return $tag !== '';

        });

        if ($title === '') {

            $error = "Le titre est obligatoire.";

            require_once __DIR__ . '/../View/task/edit.php';

            return;

        }

        $success = $this->taskModel->update($id, $title, $description, $tags);

        if ($success) {

            header('Location: /?page=dashboard');

            exit;

        } else {

            $error = "Erreur lors de la mise à jour de la tâche.";

            require_once __DIR__ . '/../View/task/edit.php';

        }

    }

    // Supprime une tâche
    public function delete(int $id): void
    {
        $task = $this->taskModel->getById($id);

        if (!$task || $task['user_id'] !== ($_SESSION['user_id'] ?? 0)) {

            header('HTTP/1.0 403 Forbidden');

            echo "Accès refusé";

            exit;

        }

        $this->taskModel->delete($id);

        header('Location: /?page=dashboard');

        exit;

    }

}
