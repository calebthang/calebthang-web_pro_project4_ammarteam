<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';
header('Content-Type: application/json');

try {
    $pdo = connectDB();
    $userId = $_SESSION['user_id'] ?? 0;
    
    $tab = $_GET['tab'] ?? 'all';
    $search = $_GET['search'] ?? '';
    $type = $_GET['type'] ?? '';
    $price = $_GET['price'] ?? '';
    $beds = $_GET['beds'] ?? '';

    error_log("Loading tab: " . $tab); // Debug log

    if ($tab === 'wishlist') {
        $sql = "SELECT DISTINCT p.* 
                FROM properties p 
                INNER JOIN wishlists w ON p.id = w.property_id 
                WHERE w.user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
    } else {
        $sql = "SELECT * FROM properties";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
    error_log("Found " . count($properties) . " properties"); // Debug log
    error_log("SQL: " . $sql);

    echo json_encode($properties);

} catch(Exception $e) {
    error_log("Error in get_properties: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}
?>