<?php

/**
 * AuthController class
 * 
 * @author Sridharan
 */

 class AuthController {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }
    public function login($username, $password) {
        if($this->db->get('users', ['username'=> $username,'password'=> $password], []))
        {
            
        }
    }
}