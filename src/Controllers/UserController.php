<?php

namespace App\Controllers;

use App\Model\User;
use PDO;

class UserController
{
    private User $userModel;

    public function __construct(PDO $db)
    {
        $this->userModel = new User($db);
    }

    /**
     * Affiche le formulaire de connexion
     */
    public function showLoginForm(): void
    {
        require __DIR__ . '/../../Views/auth/login.php';
    }

    /**
     * Affiche le formulaire d'inscription
     */
    public function showRegisterForm(): void
    {
        require __DIR__ . '/../../Views/auth/register.php';
    }

    /**
     * Inscription d'un nouvel utilisateur
     */
    public function register(array $data): void
    {
        $username = trim($data['username']);
        $email = trim($data['email']);
        $password = $data['password'];

        $success = $this->userModel->createUser($username, $email, $password);

        if ($success) {
            header('Location: /?page=login');
            exit;
        } else {
            echo "Erreur lors de l'inscription.";
        }
    }

    /**
     * Connexion d'un utilisateur
     */
    public function login(array $data): void
    {
        $email = trim($data['email']);
        $password = $data['password'];

        $user = $this->userModel->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: /?page=dashboard');
            exit;
        } else {
            echo "Email ou mot de passe incorrect.";
        }
    }

    /**
     * Déconnexion d'un utilisateur
     */
    public function logout(): void
    {
        session_destroy();
        header('Location: /?page=login');
        exit;
    }
}
