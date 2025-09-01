<?php

namespace App\Model;

use PDO;

class Task
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Récupère toutes les tâches d'un utilisateur avec leurs tags
    public function getAllByUser(int $userId): array
    {
        // Récupérer tâches
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $userId]);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Pour chaque tâche, récupérer les tags associés
        foreach ($tasks as &$task) {
            $task['tags'] = $this->getTagsByTaskId((int)$task['id']);
        }
        return $tasks;
    }

    // Récupérer tags d'une tâche
    public function getTagsByTaskId(int $taskId): array
    {
        $stmt = $this->db->prepare("
            SELECT t.name 
            FROM tags t
            JOIN task_tags tt ON t.id = tt.tag_id
            WHERE tt.task_id = :task_id
        ");
        $stmt->execute(['task_id' => $taskId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Créer une tâche avec ses tags
    public function create(int $userId, string $title, ?string $description, array $tags): bool
    {
        $this->db->beginTransaction();

        try {
            // Insérer la tâche
            $stmt = $this->db->prepare("INSERT INTO tasks (user_id, title, description) VALUES (:user_id, :title, :description)");
            $stmt->execute([
                'user_id' => $userId,
                'title' => $title,
                'description' => $description
            ]);
            $taskId = (int)$this->db->lastInsertId();

            // Gérer les tags
            $this->attachTagsToTask($taskId, $tags);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Mettre à jour une tâche et ses tags
    public function update(int $taskId, string $title, ?string $description, array $tags): bool
    {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare("UPDATE tasks SET title = :title, description = :description WHERE id = :id");
            $stmt->execute([
                'title' => $title,
                'description' => $description,
                'id' => $taskId
            ]);

            // Supprimer anciens tags liés
            $stmtDel = $this->db->prepare("DELETE FROM task_tags WHERE task_id = :task_id");
            $stmtDel->execute(['task_id' => $taskId]);

            // Réattacher les nouveaux tags
            $this->attachTagsToTask($taskId, $tags);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function updateStatus(int $taskId, string $status): bool
    {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare("UPDATE tasks SET status = :status WHERE id = :id");
            $stmt->execute([
                'status' => $status,
                'id' => $taskId
            ]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Supprimer une tâche (et cascade via FK)
    public function delete(int $taskId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = :id");
        return $stmt->execute(['id' => $taskId]);
    }

    // Attacher plusieurs tags à une tâche, créer tag si inexistant
    private function attachTagsToTask(int $taskId, array $tags): void
    {
        foreach ($tags as $tagName) {
            $tagName = trim($tagName);
            if ($tagName === '') continue;

            // Vérifier si le tag existe déjà
            $stmt = $this->db->prepare("SELECT id FROM tags WHERE name = :name");
            $stmt->execute(['name' => $tagName]);
            $tagId = $stmt->fetchColumn();

            // Si inexistant, créer
            if (!$tagId) {
                $stmtInsert = $this->db->prepare("INSERT INTO tags (name) VALUES (:name)");
                $stmtInsert->execute(['name' => $tagName]);
                $tagId = (int)$this->db->lastInsertId();
            }

            // Lier tag à la tâche
            $stmtLink = $this->db->prepare("INSERT INTO task_tags (task_id, tag_id) VALUES (:task_id, :tag_id)");
            $stmtLink->execute(['task_id' => $taskId, 'tag_id' => $tagId]);
        }
    }

    // Récupérer une tâche par son id (avec tags)
    public function getById(int $taskId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = :id");
        $stmt->execute(['id' => $taskId]);
        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$task) {
            return null;
        }

        $task['tags'] = $this->getTagsByTaskId($taskId);
        return $task;
    }
}
