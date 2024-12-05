<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';
header('Content-Type: application/json');

try {
    $pdo = connectDB();
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($_SESSION['user_id']) || !isset($data['property_id'])) {
        throw new Exception('Missing required data');
    }

    $userId = $_SESSION['user_id'];
    $propertyId = $data['property_id'];

    // Check if property is already in wishlist
    $stmt = $pdo->prepare("SELECT id FROM wishlists WHERE user_id = ? AND property_id = ?");
    $stmt->execute([$userId, $propertyId]);
    $exists = $stmt->rowCount() > 0;

    if ($exists) {
        // Remove from wishlist
        $stmt = $pdo->prepare("DELETE FROM wishlists WHERE user_id = ? AND property_id = ?");
        $stmt->execute([$userId, $propertyId]);
        echo json_encode(['success' => true, 'action' => 'removed']);
    } else {
        // Add to wishlist
        $stmt = $pdo->prepare("INSERT INTO wishlists (user_id, property_id) VALUES (?, ?)");
        $stmt->execute([$userId, $propertyId]);
        echo json_encode(['success' => true, 'action' => 'added']);
    }

} catch(Exception $e) {
    error_log("Wishlist error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>