<?php
require_once __DIR__ . '/../Database.php';

class User {
    private $db;

    public function __construct() {
        $config = require __DIR__ . '/../config.php';
        $this->db = new Database($config['database']);
    }

    // Register a new user
    public function register($name, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        return $this->db->query($sql, [
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword
        ]);
    }

    // Find user by email
    public function findByEmail($email) {
        return $this->db->fetch("SELECT * FROM users WHERE email = :email", [':email' => $email]);
    }

    // Login user
    public function login($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}