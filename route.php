<?php

require_once __DIR__ . '/Core/Router.php';
require_once __DIR__ . '/Core/functions.php';

use Core\Router;

$router = new Router();

// Define routes
$router->get('/shop', 'controllers/shop.php');

// Normalize URI
$uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$method = $_SERVER['REQUEST_METHOD'];

// Route request
$controller = $router->route($uri, $method);

if ($controller && file_exists($controller)) {
    require $controller;
} else {
    abort(); // Ensure this function is defined in functions.php
}
