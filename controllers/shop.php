<?php
require_once 'models/Book.php';
require_once 'models/Wishlist.php';

session_start();

$bookModel = new Book();
$wishlistModel = new Wishlist();

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

require_once __DIR__ . '/../views/shop.view.php';
