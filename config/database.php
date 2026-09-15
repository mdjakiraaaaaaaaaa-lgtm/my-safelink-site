<?php
$host = getenv('DB_HOST') ?: 'sql.freedb.tech';
$username = getenv('DB_USER') ?: 'u_t45VEn';
$password = getenv('DB_PASS') ?: 'xE0Wv4MGavlc';
$database = getenv('DB_NAME') ?: 'freedb_9yhkHXTI';
$port = getenv('DB_PORT') ?: '3306';

try {
    // এখানে $pdo ভেরিয়েবলটিই সবচেয়ে গুরুত্বপূর্ণ
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // অন্যান্য ফাইলে কানেকশন যেন ঠিক থাকে তাই ব্যাকআপ
    $conn = $pdo;
    $db = $pdo;
    $link = $pdo;
} catch(PDOException $e) {
    die("ডাটাবেজ কানেকশন এরর: " . $e->getMessage());
}
?>
