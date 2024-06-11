<?php

/**
 * This file contains request handling functionalities
 *
 * @author Sridharan sridharan01234@gmail.com
 * Last modified : 28/5/2024
 */

class Router
{
    private $routes = [];

    public function __construct()
    {
        $this->add("/tournament/get", ['Controller' => 'TournamentController', 'action' => 'getTournament']);
        $this->add("/tournament/add", ['Controller' => 'TournamentController', 'action' => 'addTournament']);
        $this->add("/tournament/register", ['Controller' => 'TournamentController', 'action' => 'registerTournament']);
        $this->add("/register", ['Controller' => 'AuthController', 'action' => 'register']);
        $this->add("/login", ['Controller' => 'AuthController', 'action' => 'login']);
        $this->add("/user/verify", ['Controller' => 'AuthController', 'action' => 'verifyEmail']);
        $this->add("/email/verify", ['Controller' => 'AuthController', 'action' => 'EmailConfirmation']);
        $this->add("/logout", ['Controller' => 'AuthController', 'action' => 'logout']);
        $this->add("/password/reset", ['Controller' => 'AuthController', 'action' => 'resetPassword']);
        $this->add("/otp/verify", ['Controller' => 'AuthController', 'action' => 'verifyOTP']);
        $this->add("/jwt/verify", ['Controller' => 'AuthController', 'action' => 'verifyToken']);
        $this->add("/password/change", ['Controller' => 'AuthController', 'action' => 'changePassword']);
        $this->add("/user/profile", ['Controller' => 'UserController', 'action' => 'userProfile']);
        $this->add("/user/update", ['Controller' => 'UserController', 'action' => 'userUpdate']);
        $this->add("/user/update/profile", ['Controller' => 'UserController', 'action' => 'updateProfile']);
        $this->add("/user/delete", ['Controller' => 'UserController', 'action' => 'userDelete']);
        $this->add("/product/all", ['Controller' => 'ProductController', 'action' => 'getAll']);
        $this->add("/product", ['Controller' => 'ProductController', 'action' => 'getBYId']);
        $this->add("/cart/add", ['Controller' => 'CartController', 'action' => 'updateCart']);
        $this->add("/cart/get", ['Controller' => 'CartController', 'action' => 'getCart']);
        $this->add("/cart/remove", ['Controller' => 'CartController', 'action' => 'removeCart']);
        $this->add("/cart/clear", ['Controller' => 'CartController', 'action' => 'clearCart']);
        $this->add('/countries/get', ['Controller' => 'UserController', 'action' => 'getCountries']);
        $this->add('/states/get', ['Controller' => 'UserController', 'action' => 'getStates']);
        $this->add('/cities/get', ['Controller' => 'UserController', 'action' => 'getCities']);
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
