<?php

require_once __DIR__ . '/Core/Router.php';
require_once __DIR__ . '/Core/functions.php';

use Core\Router;

$router = new Router();

// Shop routes
$router->get('/shop', 'controllers/shop.php');
$router->post('/shop', 'controllers/shop.php'); // Handles Add to Cart requests

// Cart routes
$router->get('/cart', 'controllers/showCart.php');
$router->post('/cart', 'controllers/showCart.php');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$controller = $router->route($uri, $method);

if ($controller && file_exists($controller)) {
    require $controller;
} else {
    abort();
}
