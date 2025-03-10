<?php
session_start();
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Cart.php'; // Include Cart model
require_once __DIR__ . '/../models/Wishlist.php'; // Include Wishlist model

$userModel = new User();
$cartModel = new Cart(); // Initialize Cart model
$wishlistModel = new Wishlist(); // Initialize Wishlist model

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $userModel->login($email, $password);
    if ($user) {
        // Login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        // Transfer session cart and wishlist to the user's account
        $cartModel->transferGuestCartToUser($user['id']);
        $wishlistModel->transferGuestWishlistToUser($user['id']);

        header("Location: /");
        exit;
    } else {
        // Login failed
        $error = "Invalid email or password.";
    }
}

require_once __DIR__ . '/../views/login.view.php';