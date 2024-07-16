<?php
require_once './Models/UserModel.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function createUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            $response = [];
    
            try {
                if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
                    throw new Exception("Username, email, and password are required.");
                }
                $details = [
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                ];
    
                if ($this->userModel->isUserExists($details['username'], $details['email'])) {
                    throw new Exception("User with this username or email already exists.");
                }
    
                $userId = $this->userModel->createUser($details);
                if (!$userId) {
                    throw new Exception("Failed to create user.");
                }
    
                $response = [
                    'status' => 'success',
                    'message' => 'User registered successfully.',
                    'user_id' => $userId
                ];
                http_response_code(200);
                
            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
                http_response_code(500);
            }
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed.']);
        }
    }
    
    public function updateUser($userId) {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $data = json_decode(file_get_contents("php://input"), true);
            $response = [];
    
            try {
                if (!isset($data['username']) || !isset($data['email'])) {
                    throw new Exception("Username and email are required.");
                }
                $details = [
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password' => isset($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : null,
                ];
    
                $result = $this->userModel->updateUser($userId, $details);
                if (!$result) {
                    throw new Exception("Failed to update user.");
                }
    
                $response = [
                    'status' => 'success',
                    'message' => 'User updated successfully.'
                ];
                http_response_code(200);
                
            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
                http_response_code(500);
            }
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed.']);
        }
    }

    public function deleteUser($userId) {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $response = [];
    
            try {
                $result = $this->userModel->deleteUser($userId);
                if (!$result) {
                    throw new Exception("Failed to delete user.");
                }
    
                $response = [
                    'status' => 'success',
                    'message' => 'User deleted successfully.'
                ];
                http_response_code(200);
                
            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
                http_response_code(500);
            }
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed.']);
        }
    }

    public function get($userId) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $response = [];
    
            try {
                $user = $this->userModel->getUserById($userId);
                if (!$user) {
                    throw new Exception("User not found.");
                }
    
                $response = [
                    'status' => 'success',
                    'data' => $user
                ];
                http_response_code(200);
                
            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
                http_response_code(500);
            }
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed.']);
        }
    }

    public function listUsers() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $response = [];
    
            try {
                $users = $this->userModel->listUsers();
                $response = [
                    'status' => 'success',
                    'data' => $users
                ];
                http_response_code(200);
                
            } catch (Exception $e) {
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
                http_response_code(500);
            }
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed.']);
        }
    }
}
?>
