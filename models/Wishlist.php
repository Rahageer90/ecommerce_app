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
            $sql = "INSERT INTO wishlists (user_id, book_id) VALUES (:user_id, :book_id)
                    ON DUPLICATE KEY UPDATE book_id = book_id";
            $this->db->query($sql, ['user_id' => $userId, 'book_id' => $bookId]);
        } else {
            $_SESSION['wishlist'][] = $bookId;
            $_SESSION['wishlist'] = array_unique($_SESSION['wishlist']);
        }
    }

    public function removeFromWishlist($bookId, $userId = null) {
        if ($userId) {
            $sql = "DELETE FROM wishlists WHERE user_id = :user_id AND book_id = :book_id";
            $this->db->query($sql, ['user_id' => $userId, 'book_id' => $bookId]);
        } else {
            if (!empty($_SESSION['wishlist'])) {
                $_SESSION['wishlist'] = array_diff($_SESSION['wishlist'], [$bookId]);
            }
        }
    }

    public function getWishlist($userId = null) {
        if ($userId) {
            return $this->db->fetchAll("SELECT books.* FROM wishlists 
                                        JOIN books ON wishlists.book_id = books.id 
                                        WHERE wishlists.user_id = :user_id", ['user_id' => $userId]);
        } else {
            if (!empty($_SESSION['wishlist'])) {
                $placeholders = implode(',', array_fill(0, count($_SESSION['wishlist']), '?'));
                return $this->db->fetchAll("SELECT * FROM books WHERE id IN ($placeholders)", $_SESSION['wishlist']);
            }
            return [];
        }
    }

    public function transferWishlistToUser($userId) {
        if (!empty($_SESSION['wishlist'])) {
            foreach ($_SESSION['wishlist'] as $bookId) {
                $this->addToWishlist($bookId, $userId);
            }
            unset($_SESSION['wishlist']);
        }
    }
}
