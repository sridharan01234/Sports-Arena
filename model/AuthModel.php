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
     * Verify if the email is already registered
     * 
     * @param string $email
     * @return bool
     * 
     */
    public function verify(string $email)
    {
        if ($this->db->get('users', ['email' => $email], [])) {
            return true;
        }
        return false;
    }

    /**
     * Login the user
     * 
     * @param string $email
     * @param string $password
     * @return bool
     * 
     */
    public function login(string $email, string $password)
    {
        $user = $this->db->get('users', ['email' => $email], []);
        if (!$user) {
            return false;
        }
        if (!password_verify($password, $user->password)) {
            return false;
        }
        return true;
    }

    /**
     * Logout the user
     * 
     * @return void
     * 
     */
    public function logout(): void
    {

    }

    /**
     * Register the user
     * 
     * @param string $username
     * @param string $password
     * @return void
     * 
     */
    public function register($username, $password)
    {

    }
}