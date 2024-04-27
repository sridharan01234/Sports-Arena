<?php
require_once '/var/www/html/Config/db-connection.php';

class UserModel 
{
    private $db;

    public function __construct() 
    {
        $this->db = new Database("localhost", "root", "", "aspire");
        $this->db->getConnection();
    }
    
    /**
     * Add a new user to the database. 
     * 
     * @param string $username
     * @param string $hashedpassword
     * @param string $email
     * 
     * @return bool
    */
    public function addUser(string $username, string $hashedPassword, string $email): bool
    {   
        $query = $this->db->conn->prepare("INSERT INTO `users` (username, password, email) VALUES (?, ?, ?)");
        $query->bind_param("sss", $username, $hashedPassword, $email);
        $result = $query->execute();

        return $result;
    }
    
    /**
     * Get user by email.
     * 
     * @param string $email
     * 
     * @return null
     */
    public function getUserByEmail(string $username): array | null
    {
        $query = $this->db->conn->prepare("SELECT email FROM `users` WHERE email = ?");
        $query->bind_param("s",$username);
        $query->execute();
        $result = $query->get_result()->fetch_assoc();

        return $result;
    }
    
    /**
     * Get user by email and password,if a user with the provided email exists and the password matches the hashed password in the database.
     * If no user is found or if the password doesn't match, it returns null.
     * 
     * @param string $email
     * @param string $password
     * 
     * @return array | null
    */
    public function getUserByEmailPassword(string $email, string $password): array | null
    {
        $query = $this->db->conn->prepare("SELECT username, password FROM `users` WHERE email = ?");
        $query->bind_param("s",$email);
        $query->execute();
        $result = $query->get_result();
       
        if($result->num_rows === 1) 
        {
            $user = $result->fetch_assoc();
           
            if (password_verify($password, $user['password'])) 
            {
                return $user;
            }
        }
        
        return null;
    }
    
    /**
     * Update user by email.
     * 
     * @param string $newUsername
     * @param string $newEmail
     * @param string $email
     * 
     * @return bool 
     */
    public function updateUsername(string $newUsername, string $email): bool
    {
        $query = $this->db->conn->prepare("UPDATE `users` SET username = ? WHERE email = ?");
        $query->bind_param("ss", $newUsername, $email);
        $result = $query->execute();

        return $result;
    }
    
    /**
     * Delete user by email.
     *
     * @param string $email
     * 
     * @return bool
     */
    public function removeUser(string $email): bool 
    {
        $query = $this->db->conn->prepare("DELETE FROM `users` WHERE email = ?");
        $query->bind_param("s", $email);
        $success = $query->execute();

        return $success;
    }

    /**
     * Update user profile by username.
     * 
     * @param string $newimage
     * @param string $email
     * @param string $username
     *
     * @return bool
    */
    public function updateUserProfile(string $newimage, string $email, string $username): bool
    {
        $query = $this->db->conn->prepare("UPDATE `users` SET image = ?, email = ? WHERE username = ?");
        $query->bind_param("sss", $newimage, $email, $username);
        $result = $query->execute();

        return $result;
    }

    /**
     * Get user profile by email.
     * 
     * @param string $newimage
     * 
     * @return string | null
     */
    public function userProfile(string $newimage): string | null
    {
        $query = $this->db->conn->prepare("SELECT image,username FROM `users` WHERE email = ?");
        $query->bind_param("s", $newimage);
        $query->execute();
        $result = $query->get_result()->fetch_assoc();

        return $result ? $result['image'] : null;
    }

    public function __destruct() 
    {
        $this->db->closeConnection();
    }
}
?>
