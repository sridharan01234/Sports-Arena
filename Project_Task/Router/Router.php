<?php
require_once './Controllers/UserController.php';

class Router {
    
    private $db;

    public function route() {
        $route = $_GET['route'] ?? '';

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                if ($route === 'users') {
                    $controller = new UserController($this->db);
                    if (isset($_GET['id'])) {
                        $controller->get((int)$_GET['id']);
                    } else {
                        $controller->listUsers();
                    }
                }
                break;
            case 'POST':
                if ($route === 'users') {
                    $controller = new UserController($this->db);
                    $controller->createUser();
                }
                break;
            case 'PUT':
                if ($route === 'users' && isset($_GET['id'])) {
                    $controller = new UserController($this->db);
                    $controller->updateUser($_GET['id']);
                }
                break;
            case 'DELETE':
                if ($route === 'users' && isset($_GET['id'])) {
                    $controller = new UserController($this->db);
                    $controller->deleteUser($_GET['id']);
                }
                break;
            default:
                http_response_code(405); 
                echo json_encode(['message' => 'Method Not Allowed']);
                break;
        }
    }
}
