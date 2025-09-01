<?php

namespace App\Core;

use App\Controllers\UserController;
use App\Controllers\DashboardController;
use App\Controllers\TaskController;
use App\Controllers\SubtaskController;
use PDO;

class Router {
    private PDO $db;

    public function __construct(PDO $db) {

        $this->db = $db;

        if (session_status() === PHP_SESSION_NONE) {

            session_start();

        }

    }

    public function handleRequest(): void {

        $uri = $_GET['page'] ?? 'login';

        switch ($uri) {

            /** ---------- AUTH ---------- */
            case 'login':

                $controller = new UserController($this->db);

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                    $controller->login($_POST);

                } else {

                    $controller->showLoginForm();

                }

            break;

            case 'register':

                $controller = new UserController($this->db);

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                    $controller->register($_POST);

                } else {

                    $controller->showRegisterForm();

                }

            break;

            case 'logout':

                $controller = new UserController($this->db);

                $controller->logout();

            break;

            /** ---------- DASHBOARD ---------- */
            case 'dashboard':

                if (!$this->isAuthenticated()) {

                    header('Location: /?page=login');

                    exit;

                }

                $controller = new DashboardController();

                $controller->index($this->db);

            break;

            /** ---------- TASKS ---------- */
            case 'tasks':

                if (!$this->isAuthenticated()) {

                    header('Location: /?page=login');

                    exit;

                }

                $taskController = new TaskController($this->db);
                $subtaskController = new SubtaskController($this->db);

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                    if (isset($_POST['action'])) {

                        switch ($_POST['action']) {

                            case 'create':

                                $taskController->create($_POST);

                            break;

                            case 'update':

                                $id = (int)($_POST['id'] ?? 0);

                                $taskController->update($id, $_POST);

                            break;

                            case 'updateStatus':

                                $id = (int)($_POST['id'] ?? 0);

                                $status = $_POST['status'] ?? 'created';

                                $taskController->updateStatus($id, $status);

                            break;

                            case 'delete':

                                $id = (int)($_POST['id'] ?? 0);

                                $taskController->delete($id);

                            break;

                            /** ---- SUBTASKS ---- */
                            case 'subtask_create':
                                $taskId = (int)($_POST['task_id'] ?? 0);
                                $subtaskController->createSubtask($taskId, $_POST);
                            break;

                            case 'subtask_update':
                                $id = (int)($_POST['id'] ?? 0);
                                $subtaskController->updateSubtask($id, $_POST);
                            break;

                            case 'subtask_delete':
                                $id = (int)($_POST['id'] ?? 0);
                                $taskId = (int)($_POST['task_id'] ?? 0);
                                $subtaskController->deleteSubtask($id, $taskId);
                            break;

                            case 'subtask_status':
                                $id = (int)($_POST['id'] ?? 0);
                                $taskId = (int)($_POST['task_id'] ?? 0);
                                $status = $_POST['status'] ?? 'created';
                                $subtaskController->updateSubtaskStatus($id, $status, $taskId);
                            break;

                            default:
                                // Action inconnue
                                header('Location: /?page=tasks');
                            exit;
                        }
                    }
                } else {
                    // GET requests
                    $subpage = $_GET['subpage'] ?? '';

                    switch ($subpage) {
                        case 'create':
                            $taskController->createForm();
                        break;

                        case 'edit':
                            $id = (int)($_GET['id'] ?? 0);
                            $taskController->editForm($id);
                        break;

                        case 'subtasks':
                            $taskId = (int)($_GET['id'] ?? 0);
                            $subtaskController->listSubtasks($taskId);
                        break;

                        default:
                            $taskController->getTasksForCurrentUser();
                        break;
                    }
                }
            break;

            /** ---------- 404 ---------- */
            default:
                header('HTTP/1.0 404 Not Found');
                echo "Page non trouvée";
            break;
        }
    }

    private function isAuthenticated(): bool {
        return isset($_SESSION['user_id']);
    }
}
