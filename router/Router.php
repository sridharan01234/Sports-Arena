<?php

/**
 * This file contains request handling functionalities
 *
 * Author : sridharan
 * Email : sridharan01234@gmail.com
 * Last modified : 28/5/2024
 */

class Router
{
    private $routes = [];

    public function __construct()
    {
        $this->add("/register", ['Controller' => 'AuthController', 'action' => 'register']);
        $this->add("/login", ['Controller' => 'AuthController', 'action' => 'login']);
        $this->add("/user/verify", ['Controller' => 'AuthController', 'action' => 'verifyEmail']);
        $this->add("/logout", ['Controller' => 'AuthController', 'action' => 'logout']);
        $this->add("/password/reset", ['Controller' => 'AuthController', 'action' => 'resetPassword']);
    }

    /**
     * Add application paths
     *
     * @param string $path
     * @param array $param
     *
     * @return void
     */
    public function add(string $path, array $param): void
    {
        $this->routes[] = [
            "path" => $path,
            "params" => $param,
        ];
    }

    /**
     * Search path
     *
     * @param string $uri
     *
     * @return bool|array
     */
    public function findRoute(string $uri): bool | array
    {
        foreach ($this->routes as $path) {
            if ($path['path'] == $uri) {
                return $path['params'];
            }
        }
        return false;
    }
}
