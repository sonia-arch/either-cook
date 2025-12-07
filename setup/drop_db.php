<?php

require_once __DIR__ . '/../config/Database.php';

$db = new Database();

$dsn = "mysql:host={$db->host};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $db->username, $db->password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    $dbName = $db->databaseName;

    $pdo->exec("DROP DATABASE IF EXISTS $dbName;");

    echo "Database '$dbName' berhasil dihapus.";
} catch (PDOException $e) {
    echo "Gagal menghapus database: " . $e->getMessage();
}