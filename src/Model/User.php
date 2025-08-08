<?php

namespace src\Model;

use PDO;

class User {
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function createUser(string $username, string $email, string $password): bool
    {
        $stmt = $this->db->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        return $stmt->execute([$username, $email, password_hash($password, PASSWORD_BCRYPT)]);
    }

    public function getUserByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }
}
