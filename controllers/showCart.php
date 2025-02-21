<?php
session_start();
require_once __DIR__ . '/../models/Cart.php';

$cartModel = new Cart();

// Handle cart operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $cartModel->addToCart($_POST['add'], 1);
    } elseif (isset($_POST['remove'])) {
        $cartModel->removeFromCart($_POST['remove']);
    }

    header("Location: /cart");
    exit;
}

// Fetch cart items and total bill calculation
$cartItems = $cartModel->getCartItems();
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Load the view
require_once __DIR__ . '/../views/showCart.view.php';
