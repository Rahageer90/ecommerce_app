<?php
session_start();
require_once __DIR__ . '/../models/User.php';

$userModel = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists
    if ($userModel->findByEmail($email)) {
        $error = "Email already registered.";
    } else {
        // Register the user
        if ($userModel->register($name, $email, $password)) {
            $_SESSION['user_id'] = $userModel->findByEmail($email)['id'];
            $_SESSION['user_name'] = $name;
            header("Location: /");
            exit;
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}

require_once __DIR__ . '/../views/register.view.php';