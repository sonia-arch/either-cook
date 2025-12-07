<?php
require_once __DIR__ . '/../config/Database.php';

$db = new Database();

$dsn = "mysql:host={$db->host};charset=utf8mb4";
$pdo = new PDO($dsn, $db->username, $db->password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$pdo->exec("CREATE DATABASE IF NOT EXISTS {$db->databaseName}");
    
$pdo->exec("USE {$db->databaseName}");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        name VARCHAR(255) NOT NULL,
        url_image TEXT NOT NULL,
        description TEXT NOT NULL
    );
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_id INT NULL,
        name VARCHAR(255) NOT NULL,
        url_image TEXT NOT NULL,
        description TEXT NOT NULL,
        recommendation TINYINT(1) DEFAULT 0,
        price DECIMAL(10,2) NOT NULL DEFAULT 0.0,
        available TINYINT(1) DEFAULT 1,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
    );
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS variants (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NULL,
        name VARCHAR(255) NOT NULL,
        max INT NULL,
        min INT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON CASCADE 
    );
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS variant_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        variant_id INT NULL,
        name VARCHAR(255) NOT NULL,
        FOREIGN KEY (variant_id) REFERENCES variants(id) ON CASCADE 
    )
");

echo "database created";
