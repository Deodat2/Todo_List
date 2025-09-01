<?php

namespace App\Model;

use PDO;

class Subtask {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Récupère une sous-tâche par son ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM subtasks WHERE id = ?");
        $stmt->execute([(int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère toutes les sous-tâches d'une tâche
     */
    public function getByTaskId($taskId) {
        $stmt = $this->db->prepare("SELECT * FROM subtasks WHERE task_id = ? ORDER BY id DESC");
        $stmt->execute([(int)$taskId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crée une sous-tâche
     */
    public function createSubtasks($taskId, $title, $description, $duration) {
        $stmt = $this->db->prepare("INSERT INTO subtasks (task_id, title, description, duration, status) 
                                    VALUES (?, ?, ?, ?, 'created')");
        return $stmt->execute([(int)$taskId, $title, $description, $duration]);
    }

    /**
     * Met à jour une sous-tâche (titre, description, statut)
     */
    public function updateSubtasks($id, $title, $description) {
        $stmt = $this->db->prepare("UPDATE subtasks SET title = ?, description = ? WHERE id = ?");
        return $stmt->execute([$title, $description, (int)$id]);
    }

    /**
     * Met à jour uniquement le statut d’une sous-tâche
     */
    public function updateSubtasksStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE subtasks SET status = ? WHERE id = ?");
        return $stmt->execute([$status, (int)$id]);
    }

    /**
     * Supprime une sous-tâche
     */
    public function deleteSubtasks($id) {
        $stmt = $this->db->prepare("DELETE FROM subtasks WHERE id = ?");
        return $stmt->execute([(int)$id]);
    }
}
