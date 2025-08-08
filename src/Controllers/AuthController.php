<?php

namespace App\Controllers;

use src\Model\User;
use PDO;

class UserController
{
    private User $userModel;

    public function __construct(PDO $db)
    {
        $this->userModel = new User($db);
    }

    public function showLoginForm()
    {
        require __DIR__ . '/../../Views/auth/login.php';
    }

    public function showRegisterForm()
    {
        require __DIR__ . '/../../Views/auth/register.php';
    }

    public function register(array $data)
    {
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];

        $success = $this->userModel->createUser($username, $email, $password);
        if ($success) {
            header('Location: /login');
        } else {
            echo "Erreur lors de l'inscription.";
        }
    }

    public function login(array $data)
    {
        $email = $data['email'];
        $password = $data['password'];

        $user = $this->userModel->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: /dashboard');
        } else {
            echo "Email ou mot de passe incorrect.";
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
    }
}
