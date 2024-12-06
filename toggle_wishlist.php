<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';
header('Content-Type: application/json');

try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User not logged in');
    }

    // Check if this is a POST request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $pdo = connectDB();
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Validate input data
    if (!$input || !$data || !isset($data['property_id'])) {
        throw new Exception('Invalid input data');
    }

    $propertyId = $data['property_id'];
    $userId = $_SESSION['user_id'];

    // Check if property is already in wishlist
    $stmt = $pdo->prepare("SELECT id FROM wishlists WHERE user_id = ? AND property_id = ?");
    $stmt->execute([$userId, $propertyId]);
    $exists = $stmt->rowCount() > 0;

    if ($exists) {
        // Remove from wishlist
        $stmt = $pdo->prepare("DELETE FROM wishlists WHERE user_id = ? AND property_id = ?");
        $stmt->execute([$userId, $propertyId]);
        echo json_encode([
            'success' => true,
            'action' => 'removed',
            'message' => 'Property removed from wishlist'
        ]);
    } else {
        // Add to wishlist
        $stmt = $pdo->prepare("INSERT INTO wishlists (user_id, property_id) VALUES (?, ?)");
        $stmt->execute([$userId, $propertyId]);
        echo json_encode([
            'success' => true,
            'action' => 'added',
            'message' => 'Property added to wishlist'
        ]);
    }

} catch (Exception $e) {
    error_log("Wishlist error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>