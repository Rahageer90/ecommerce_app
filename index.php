<?php

require_once __DIR__ . '/config.php';  // Load database configuration
require_once __DIR__ . '/Database.php'; // Load Database class

$config = require __DIR__ . '/config.php';


if (!$config || !isset($config['database'])) {
    die("Error: config.php failed to load or is invalid.");
}

// Pass the entire database config to the Database class
$db = new Database($config['database']);

require_once __DIR__ . '/route.php'; // Load routes

