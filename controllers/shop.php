<?php
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Wishlist.php';
require_once __DIR__ . '/../models/Cart.php';

session_start();

$bookModel = new Book();
$wishlistModel = new Wishlist();
$cartModel = new Cart();

// Search and category filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Pagination
$page = max(1, (int) ($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

// Fetch books
$books = $bookModel->fetchBooks($search, $category, $limit, $offset);
$totalBooks = $bookModel->countBooks($search, $category);
$totalPages = ceil($totalBooks / $limit);

// Wishlist
$userId = $_SESSION['user_id'] ?? null;
$wishlistBooks = $wishlistModel->getWishlist($userId);
$wishlistIds = array_column($wishlistBooks, 'id'); // For button state

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $bookId = $_POST['book_id'];
    $quantity = (int) ($_POST['quantity'] ?? 1);

    if ($userId) {
        $cartModel->addToCart($bookId, $quantity);
    } else {
        $_SESSION['cart'][$bookId] = ($_SESSION['cart'][$bookId] ?? 0) + $quantity;
    }

    header("Location: /");
    exit;
}

require_once __DIR__ . '/../views/shop.view.php';
