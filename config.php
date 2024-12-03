<?php
function connectDB() {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=web_pro_project4_ammarteam", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        error_log("Connection failed: " . $e->getMessage());
        die("Connection failed: " . $e->getMessage());
    }
}
?>