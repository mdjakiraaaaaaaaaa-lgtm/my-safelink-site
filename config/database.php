<?php
// যেকোনো লুকানো এরর স্ক্রিনে দেখানোর কোড
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = getenv('DB_HOST') ?: '127.0.0.1';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';
$database = getenv('DB_NAME') ?: 'your_database_name';
$port = getenv('DB_PORT') ?: '3306';

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // অন্যান্য ফাইলের সাথে যেন কানেকশন মিসম্যাচ না হয় তাই ব্যাকআপ ভেরিয়েবল
    $pdo = $conn;
    $db = $conn;
    $link = $conn;
    
} catch(PDOException $e) {
    die("Database Connection Error: " . $e->getMessage());
}
?>
