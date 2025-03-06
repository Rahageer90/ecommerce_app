<?php
require_once __DIR__ . '/../Database.php';

class Wishlist {
    private $db;

    public function __construct() {
        $config = require __DIR__ . '/../config.php';
        $this->db = new Database($config['database']);
    }

    public function addToWishlist($bookId, $userId = null) {
        if ($userId) {
            // For logged-in users: Insert into database
            $sql = "INSERT INTO wishlists (user_id, book_id) VALUES (:user_id, :book_id)
                    ON DUPLICATE KEY UPDATE book_id = book_id";
            $this->db->query($sql, ['user_id' => $userId, 'book_id' => $bookId]);
        } else {
            // For guests: Store in session
            if (!isset($_SESSION['wishlist'])) {
                $_SESSION['wishlist'] = []; // Initialize the session array if it doesn't exist
            }
            if (!in_array($bookId, $_SESSION['wishlist'])) {
                $_SESSION['wishlist'][] = $bookId; // Add the book ID to the session array
            }
        }
    }

    public function removeFromWishlist($bookId, $userId = null) {
        if ($userId) {
            // For logged-in users: Remove from database
            $sql = "DELETE FROM wishlists WHERE user_id = :user_id AND book_id = :book_id";
            $this->db->query($sql, ['user_id' => $userId, 'book_id' => $bookId]);
        } else {
            // For guests: Remove from session
            if (!empty($_SESSION['wishlist'])) {
                $_SESSION['wishlist'] = array_diff($_SESSION['wishlist'], [$bookId]);
            }
        }
    }

    public function getWishlist($userId = null) {
        if ($userId) {
            // For logged-in users: Fetch from database
            return $this->db->fetchAll("SELECT books.* FROM wishlists 
                                       JOIN books ON wishlists.book_id = books.id 
                                       WHERE wishlists.user_id = :user_id", ['user_id' => $userId]);
        } else {
            // For guests: Fetch from session
            if (!empty($_SESSION['wishlist'])) {
                $placeholders = implode(',', array_fill(0, count($_SESSION['wishlist']), '?'));
                return $this->db->fetchAll("SELECT * FROM books WHERE id IN ($placeholders)", $_SESSION['wishlist']);
            }
            return [];
        }
    }

    public function transferGuestWishlistToUser($userId) {
        if (!$userId || empty($_SESSION['wishlist'])) {
            return; // Exit if no user ID or session wishlist is empty
        }

        foreach ($_SESSION['wishlist'] as $bookId) {
            // Check if the book is already in the user's wishlist
            $existingWishlistItem = $this->db->fetch("SELECT id FROM wishlists WHERE user_id = :user_id AND book_id = :book_id", [
                ':user_id' => $userId,
                ':book_id' => $bookId
            ]);

            if (!$existingWishlistItem) {
                // Insert new wishlist item
                $this->db->query("INSERT INTO wishlists (user_id, book_id) VALUES (:user_id, :book_id)", [
                    ':user_id' => $userId,
                    ':book_id' => $bookId
                ]);
            }
        }

        // Clear guest session wishlist after transfer
        unset($_SESSION['wishlist']);
    }
}