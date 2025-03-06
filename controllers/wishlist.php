<?php
session_start(); // Ensure session is started
require_once __DIR__ . '/../models/Wishlist.php';
require_once __DIR__ . '/../models/Cart.php'; // Include Cart model

$wishlistModel = new Wishlist();
$cartModel = new Cart(); // Initialize Cart model
$userId = $_SESSION['user_id'] ?? null;

// Handle Add/Remove Wishlist Requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['book_id'])) {
        $bookId = $_POST['book_id'];
        if (isset($_POST['add'])) {
            echo "Adding book $bookId to wishlist...<br>";
            $wishlistModel->addToWishlist($bookId, $userId);

            // Redirect based on the current page
            $currentUri = $_SERVER['REQUEST_URI'];
            if ($currentUri === '/wishlist') {
                header("Location: /wishlist");
            } else {
                header("Location: /");
            }
            exit;
        } elseif (isset($_POST['remove'])) {
            echo "Removing book $bookId from wishlist...<br>";
            $wishlistModel->removeFromWishlist($bookId, $userId);

            // Redirect to the wishlist page after removal
            header("Location: /wishlist");
            exit;
        } elseif (isset($_POST['add_to_cart'])) {
            // Handle Add to Cart request
            $quantity = $_POST['quantity'] ?? 1;
            if ($userId) {
                // If user is logged in, store in database
                $cartModel->addToCart($bookId, $quantity);
            } else {
                // If user is guest, store in session
                $_SESSION['cart'][$bookId] = ($_SESSION['cart'][$bookId] ?? 0) + $quantity;
            }

            // Redirect to the wishlist page after adding to cart
            header("Location: /wishlist");
            exit;
        }
    }
}

// Fetch Wishlist Items
$wishlistItems = $wishlistModel->getWishlist($userId);

require_once __DIR__ . '/../views/wishlist.view.php';