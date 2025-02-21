<?php
require_once 'models/Book.php';
require_once 'models/Wishlist.php';
require_once 'models/Cart.php';

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

// Fetch books based on search and category filters
$books = $bookModel->fetchBooks($search, $category, $limit, $offset);
$totalBooks = $bookModel->countBooks($search, $category);
$totalPages = ceil($totalBooks / $limit);

// Fetch wishlist
$userId = $_SESSION['user_id'] ?? null;
$wishlist = $wishlistModel->getWishlist($userId);

// Handle Add to Cart request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $bookId = $_POST['book_id'];
    $quantity = $_POST['quantity'] ?? 1;

    if ($userId) {
        // If user is logged in, store in database
        $cartModel->addToCart($bookId, $quantity);
    } else {
        // If user is guest, store in session
        $_SESSION['cart'][$bookId] = ($_SESSION['cart'][$bookId] ?? 0) + $quantity;
    }

    // Redirect back to shop
    header("Location: /shop");
    exit;
}
require_once __DIR__ . '/../views/shop.view.php';
