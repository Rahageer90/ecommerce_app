<?php

require_once __DIR__ . '/Core/Router.php';
require_once __DIR__ . '/Core/functions.php';

use Core\Router;

$router = new Router();

// Shop routes
$router->get('/', 'controllers/shop.php');
$router->post('/', 'controllers/shop.php');

// Cart routes
$router->get('/cart', 'controllers/showCart.php');
$router->post('/cart', 'controllers/showCart.php');

// Wishlist routes
$router->get('/wishlist', 'controllers/wishlist.php');
$router->post('/wishlist/add', 'controllers/wishlist.php');
$router->post('/wishlist/remove', 'controllers/wishlist.php');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$controller = $router->route($uri, $method);

if ($controller && file_exists($controller)) {
    require $controller;
} else {
    abort();
}