<?php

require_once "router/Router.php";
require_once "helper/JWTHelper.php";

$requestUri = strtok($_SERVER['REQUEST_URI'], '?');

$route = new Router();

$routeParams = $route->findRoute($requestUri);
if (!$routeParams) {
    echo json_encode(
        [
            'status'=> 'error',
            'message'=> 'Invalid Request'
        ]
    );
    exit;
}

if (!isset($_SESSION['user_id']) && !in_array($requestUri, ['/login', '/register', '/user/verify', '/password/reset', '/otp/verify', '/password/change', '/email/verify', '/product/all', '/product'])) {
    $jwt = new JWTHelper();
    $jwt->verifyJWT();
}

$controllerName = $routeParams['Controller'];
$actionName = $routeParams['action'];

require_once sprintf("controller/%s.php", $controllerName);

$controllerObject = new $controllerName($requestUri);
$controllerObject->$actionName();
