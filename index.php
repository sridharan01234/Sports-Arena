<?php

require_once "router/Router.php";
require_once "helper/SessionHelper.php";

$requestUri = strtok($_SERVER['REQUEST_URI'], '?');

$route = new Router();

$routeParams = $route->findRoute($requestUri);
if (!$routeParams) {
    require_once './view/pageNotFound.php';
    exit;
}

if (!isset($_SESSION['user_id']) && !in_array($requestUri, ['/login', '/register', '/user/verify', '/password/reset', '/otp/verify', '/password/change'])) {
    echo json_encode([
        'status' => false,
        'error' => 'Unauthorized access'
    ]);
    exit;
}

$controllerName = $routeParams['Controller'];
$actionName = $routeParams['action'];

require_once sprintf("controller/%s.php", $controllerName);

$controllerObject = new $controllerName($requestUri);
$controllerObject->$actionName();
