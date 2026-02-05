<?php

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'T#9758@qlph');
define('DB_NAME', 'construction_db');

try {
    // Create PDO connection
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO(
        $dsn,
        DB_USER,
        DB_PASS,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        )
    );
    
    // Connection successful
    // echo "Connected to database successfully!";
    
} catch (PDOException $e) {
    // Handle connection error
    die('Database connection failed: ' . $e->getMessage());
}

?>
