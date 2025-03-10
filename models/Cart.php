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

    // Add book to cart (update quantity if already exists)
    public function addToCart($bookId, $quantity = 1) {
        if (!$bookId) {
            return false;
        }
    
        $this->sessionId = session_id(); // Ensure session_id is fetched properly
    
        $params = [
            ':book_id' => $bookId,
            ':user_id' => $this->userId,
            ':session_id' => $this->userId ? null : $this->sessionId,
        ];
    
        // 🔍 DEBUG: Print query parameters before execution
        echo "<pre>";
        print_r($params);
        echo "</pre>";
    
        // Check if item already exists in cart
        $existing = $this->db->fetch("SELECT * FROM cart WHERE book_id = :book_id AND (user_id = :user_id OR session_id = :session_id)", $params);
    
        if ($existing) {
            // 🔍 DEBUG: Print existing cart entry
            echo "Existing cart item found: ";
            print_r($existing);
        } else {
            // 🔍 DEBUG: Print message before inserting
            echo "Inserting new cart item...<br>";
        }
    
        // If item exists, update quantity
        if ($existing) {
            $this->db->query("UPDATE cart SET quantity = quantity + :quantity WHERE id = :cart_id", [
                ':quantity' => $quantity,
                ':cart_id' => $existing['id']
            ]);
        } else {
            // Insert new item
            $insert = $this->db->query("INSERT INTO cart (user_id, session_id, book_id, quantity, created_at) 
                VALUES (:user_id, :session_id, :book_id, :quantity, NOW())", [
                ':user_id' => $this->userId,
                ':session_id' => $this->userId ? null : $this->sessionId,
                ':book_id' => $bookId,
                ':quantity' => $quantity
            ]);
    
            // 🔍 DEBUG: Check if insert was successful
            if ($insert) {
                echo "✅ Insert successful!<br>";
            } else {
                echo "❌ Insert failed!<br>";
            }
        }
    
        return true;
    }
    

    // Fetch items
    public function getCartItems() {
        if ($this->userId) {
            // Fetch from database for logged-in users
            $params = [':user_id' => $this->userId];
            return $this->db->fetchAll("SELECT cart.id, cart.book_id, cart.quantity, books.title, books.price, books.image 
                                        FROM cart JOIN books ON cart.book_id = books.id 
                                        WHERE cart.user_id = :user_id", $params);
        } else {
            // Fetch from session for guests
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
    

    // Remove item
    public function removeFromCart($bookId) {
        if ($this->userId) {
            // Remove from database for logged-in users
            return $this->db->query("DELETE FROM cart WHERE user_id = :user_id AND book_id = :book_id", [
                ':user_id' => $this->userId,
                ':book_id' => $bookId
            ]);
        } else {
            // Remove from session for guests
            if (isset($_SESSION['cart'][$bookId])) {
                unset($_SESSION['cart'][$bookId]);
            }
        }
    }   
    // Update cart after login
    public function transferGuestCartToUser($userId) {
        if (!$userId || empty($_SESSION['cart'])) return;
    
        foreach ($_SESSION['cart'] as $bookId => $quantity) {
            // Check if the item already exists in the user's cart
            $existingCartItem = $this->db->fetch("SELECT id, quantity FROM cart WHERE user_id = :user_id AND book_id = :book_id", [
                ':user_id' => $userId,
                ':book_id' => $bookId
            ]);
    
            if ($existingCartItem) {
                // Update the quantity if the book is already in the cart
                $newQuantity = $existingCartItem['quantity'] + $quantity;
                $this->db->query("UPDATE cart SET quantity = :quantity WHERE id = :cart_id", [
                    ':quantity' => $newQuantity,
                    ':cart_id' => $existingCartItem['id']
                ]);
            } else {
                // Insert new cart item
                $this->db->query("INSERT INTO cart (user_id, book_id, quantity) VALUES (:user_id, :book_id, :quantity)", [
                    ':user_id' => $userId,
                    ':book_id' => $bookId,
                    ':quantity' => $quantity
                ]);
            }
        }
    
        // Clear guest session cart after transfer
        unset($_SESSION['cart']);
    }
    
}
