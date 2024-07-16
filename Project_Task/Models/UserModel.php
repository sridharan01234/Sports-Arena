<?php
require_once './Database/database.php';

class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database(); 
    }

    public function createUser(array $details): bool {
        try {
            $query = "INSERT INTO users (Username, Email, Password) VALUES (:username, :email, :password)";
            $stmt = $this->db->prepare($query);

            $hashedPassword = password_hash($details['password'], PASSWORD_DEFAULT);
            $stmt->bindParam(':username', $details['username'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $details['email'], PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                throw new Exception("Failed to create user.");
            }

            return true;
        } catch (Exception $e) {
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }

    public function isUserExists(string $username, string $email): bool {
        try {
            $query = "SELECT COUNT(*) FROM users WHERE Username = :username AND Email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            
            $count = $stmt->fetchColumn();
            
            return $count > 0;
        } catch (PDOException $e) {
            error_log("Error checking if user exists: " . $e->getMessage());
            return false;
        }
    }    

    public function updateUser(int $userId, array $userData): bool {
        try {
            $query = "UPDATE users SET username = :username, email = :email, password = :password WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $userData['username']);
            $stmt->bindParam(':email', $userData['email']);
            if ($userData['password']) {
                $stmt->bindParam(':password', $userData['password']);
            } else {
                $stmt->bindParam(':password', $this->getUserPassword($userId));
            }
            $stmt->bindParam(':id', $userId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }

    private function getUserPassword($userId): ?string {
        try {
            $query = "SELECT Password FROM users WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $password = $stmt->fetchColumn();
            return $password ?: null;
        } catch (PDOException $e) {
            error_log("Error fetching user password: " . $e->getMessage());
            return null;
        }
    }

    public function deleteUser(int $userId): bool {
        try {
            $query = "DELETE FROM users WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $userId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }

    public function getUserById($userId): ?array {
        try {
            $query = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ?: null;
        } catch (PDOException $e) {
            error_log("Error fetching user by ID: " . $e->getMessage());
            return null;
        }
    }

    public function listUsers(): array {
        try {
            $query = "SELECT * FROM users";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching users: " . $e->getMessage());
            return [];
        }
    }
}
?>
