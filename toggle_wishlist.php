<?php
require_once 'auth_middleware.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = connectDB();
        $data = json_decode(file_get_contents('php://input'), true);
        $propertyId = $data['property_id'];
        $userId = $_SESSION['user_id'];

        // Check if property is already in wishlist
        $stmt = $pdo->prepare("SELECT id FROM wishlists WHERE user_id = ? AND property_id = ?");
        $stmt->execute([$userId, $propertyId]);

        if ($stmt->rowCount() > 0) {
            // Remove from wishlist
            $stmt = $pdo->prepare("DELETE FROM wishlists WHERE user_id = ? AND property_id = ?");
            $stmt->execute([$userId, $propertyId]);
        } else {
            // Add to wishlist
            $stmt = $pdo->prepare("INSERT INTO wishlists (user_id, property_id) VALUES (?, ?)");
            $stmt->execute([$userId, $propertyId]);
        }

        echo json_encode(['success' => true]);
    } catch(Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>