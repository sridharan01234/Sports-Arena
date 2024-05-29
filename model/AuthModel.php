<?php

/**
 *
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
     * @return bool
     *
     */
    public function check(string $email): bool
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
     * @return void
     *
     */
    public function create(array $data)
    {
        $this->db->insert('users', $data);
    }

    /**
     * Get user by email
     *
     * @param string $email
     * @return object|bool
     *
     */
    public function getUserByEmail(string $email): object|bool
    {
        return $this->db->get('users', ['email' => $email], []);
    }
}
