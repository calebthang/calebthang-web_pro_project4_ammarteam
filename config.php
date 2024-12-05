<?php
function connectDB() {
    $host = 'localhost';
    $dbname = 'kle53';
    $username = 'kle53';
    $password = 'kle53';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
?>
