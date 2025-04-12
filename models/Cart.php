<?php

require_once __DIR__ . '/../Database.php';

class Cart {
    private $db;
    private $userId;
    private $sessionId;

    public function __construct() {
        $config = include __DIR__ . '/../config.php';
        $this->db = new Database($config['database']);

        $this->userId = $_SESSION['user_id'] ?? null;
        $this->sessionId = session_id();
    }

    public function addToCart($bookId, $quantity = 1) {
        if (!$bookId) {
            return false;
        }

        $this->sessionId = session_id();

        $params = [
            ':book_id' => $bookId,
            ':user_id' => $this->userId,
            ':session_id' => $this->userId ? null : $this->sessionId,
        ];

        $existing = $this->db->fetch("SELECT * FROM cart WHERE book_id = :book_id AND (user_id = :user_id OR session_id = :session_id)", $params);

        if ($existing) {
            $this->db->query("UPDATE cart SET quantity = quantity + :quantity WHERE id = :cart_id", [
                ':quantity' => $quantity,
                ':cart_id' => $existing['id']
            ]);
        } else {
            $this->db->query("INSERT INTO cart (user_id, session_id, book_id, quantity, created_at) 
                VALUES (:user_id, :session_id, :book_id, :quantity, NOW())", [
                ':user_id' => $this->userId,
                ':session_id' => $this->userId ? null : $this->sessionId,
                ':book_id' => $bookId,
                ':quantity' => $quantity
            ]);
        }

        return true;
    }

    public function getCartItems() {
        if ($this->userId) {
            $params = [':user_id' => $this->userId];
            return $this->db->fetchAll("SELECT cart.id, cart.book_id, cart.quantity, books.title, books.price, books.image 
                                        FROM cart JOIN books ON cart.book_id = books.id 
                                        WHERE cart.user_id = :user_id", $params);
        } else {
            $cartItems = [];
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $bookId => $quantity) {
                    $book = $this->db->fetch("SELECT id, title, price, image FROM books WHERE id = :book_id", 
                                             [':book_id' => $bookId]);
                    if ($book) {
                        $book['quantity'] = $quantity;
                        $cartItems[] = $book;
                    }
                }
            }
            return $cartItems;
        }
    }

    public function removeFromCart($id) {
        if ($this->userId) {
            // Use cart item ID for logged-in users
            return $this->db->query("DELETE FROM cart WHERE id = :id AND user_id = :user_id", [
                ':id' => $id,
                ':user_id' => $this->userId
            ]);
        } else {
            // For guests, remove using book ID from session cart
            if (isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
            }
        }
    }

    public function transferGuestCartToUser($userId) {
        if (!$userId || empty($_SESSION['cart'])) return;

        foreach ($_SESSION['cart'] as $bookId => $quantity) {
            $existingCartItem = $this->db->fetch("SELECT id, quantity FROM cart WHERE user_id = :user_id AND book_id = :book_id", [
                ':user_id' => $userId,
                ':book_id' => $bookId
            ]);

            if ($existingCartItem) {
                $newQuantity = $existingCartItem['quantity'] + $quantity;
                $this->db->query("UPDATE cart SET quantity = :quantity WHERE id = :cart_id", [
                    ':quantity' => $newQuantity,
                    ':cart_id' => $existingCartItem['id']
                ]);
            } else {
                $this->db->query("INSERT INTO cart (user_id, book_id, quantity) VALUES (:user_id, :book_id, :quantity)", [
                    ':user_id' => $userId,
                    ':book_id' => $bookId,
                    ':quantity' => $quantity
                ]);
            }
        }

        unset($_SESSION['cart']);
    }
}
