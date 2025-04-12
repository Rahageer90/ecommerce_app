<?php
session_start();
require_once __DIR__ . '/../models/Wishlist.php';
require_once __DIR__ . '/../models/Cart.php';

$wishlistModel = new Wishlist();
$cartModel = new Cart();
$userId = $_SESSION['user_id'] ?? null;

// Handle Add/Remove Wishlist Requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['book_id'])) {
        $bookId = $_POST['book_id'];

        // Use HTTP_REFERER for redirect fallback
        $redirectBack = $_SERVER['HTTP_REFERER'] ?? '/';

        if (isset($_POST['add'])) {
            $wishlistModel->addToWishlist($bookId, $userId);
            header("Location: $redirectBack");
            exit;
        } elseif (isset($_POST['remove'])) {
            $wishlistModel->removeFromWishlist($bookId, $userId);
            header("Location: $redirectBack");
            exit;
        } elseif (isset($_POST['add_to_cart'])) {
            $quantity = $_POST['quantity'] ?? 1;
            if ($userId) {
                $cartModel->addToCart($bookId, $quantity);
            } else {
                $_SESSION['cart'][$bookId] = ($_SESSION['cart'][$bookId] ?? 0) + $quantity;
            }
            header("Location: $redirectBack");
            exit;
        }
    }
}

// Fetch Wishlist Items
$wishlistItems = $wishlistModel->getWishlist($userId);

require_once __DIR__ . '/../views/wishlist.view.php';
