<?php
// db.php
$host = '127.0.0.1';   // or 'localhost'
$user = 'root';        // XAMPP default
$pass = 'MySQL';            // XAMPP default is empty password
$db   = 'exdata';
$port = 3307;   

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
