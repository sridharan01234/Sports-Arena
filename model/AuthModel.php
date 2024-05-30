<?php

/**
 * AuthModel
 *
 * @author Sridharan
 * Email : sridharan01234@gmail.com
 * LastModified : 29-05-2024
 */

require './database/Database.php';

class AuthModel extends Database
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Check if user exists
     *
     * @param string $email
     *
     * @return bool
     */
    public function checkEmail(string $email): bool
    {
        $user = $this->db->get('users', ['email' => $email], []);
        if ($user) {
            return true;
        }
        return false;
    }

    /**
     * Create a new user
     *
     * @param array $data
     *
     * @return bool
     */
    public function create(array $data): bool
    {
        return $this->db->insert('users', $data);
    }

    /**
     * Get user by email
     *
     * @param string $email
     *
     * @return object|bool
     */
    public function getUserByEmail(string $email): object|bool
    {
        return $this->db->get('users', ['email' => $email], []);
    }

    public function changePassword(string $email, string $password): bool
    {
        return $this->db->update('users', ['password' => $password], ['email' => $email]);
    }

    public function getUserById(int $id): object|bool
    {
        return $this->db->get('users', ['id' => $id], []);
    }
}
