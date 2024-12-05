<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';
header('Content-Type: application/json');

try {
    $pdo = connectDB();
    
    $tab = $_GET['tab'] ?? 'all';
    $search = $_GET['search'] ?? '';
    $type = $_GET['type'] ?? '';
    $price = $_GET['price'] ?? '';
    $beds = $_GET['beds'] ?? '';

    $params = [];
    $whereConditions = [];

    // Base query
    if ($tab === 'wishlist') {
        // Only get properties in user's wishlist
        $sql = "SELECT p.* FROM properties p 
                INNER JOIN wishlists w ON p.id = w.property_id 
                WHERE w.user_id = ?";
        $params[] = $_SESSION['user_id'];
    } else {
        // Get all properties
        $sql = "SELECT * FROM properties WHERE 1=1";
    }

    // Add search conditions
    if ($search) {
        $sql .= " AND (location LIKE ? OR title LIKE ? OR description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    if ($type) {
        $sql .= " AND property_type = ?";
        $params[] = $type;
    }

    if ($price) {
        list($min, $max) = explode('-', $price);
        if ($max) {
            $sql .= " AND price BETWEEN ? AND ?";
            $params[] = $min;
            $params[] = $max;
        } else {
            $sql .= " AND price >= ?";
            $params[] = $min;
        }
    }

    if ($beds) {
        $sql .= " AND bedrooms >= ?";
        $params[] = $beds;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($properties);

} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['error' => 'Database error']);
} catch(Exception $e) {
    error_log("General error: " . $e->getMessage());
    echo json_encode(['error' => 'General error']);
}
?>