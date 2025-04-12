<?php
session_start();
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Wishlist.php';

$userModel = new User();
$cartModel = new Cart();
$wishlistModel = new Wishlist();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $user = $userModel->login($email, $password);
        if ($user) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            // Transfer session cart and wishlist to the user's account
            if (isset($_SESSION['cart'])) {
                $cartModel->transferGuestCartToUser($user['id']);
                unset($_SESSION['cart']); // Clear guest cart session
            }

            if (isset($_SESSION['wishlist'])) {
                $wishlistModel->transferGuestWishlistToUser($user['id']);
                unset($_SESSION['wishlist']); // Clear guest wishlist session
            }

            header("Location: /"); // Redirect to dashboard or homepage
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Please enter both email and password.";
    }
}

require_once __DIR__ . '/../views/login.view.php';
