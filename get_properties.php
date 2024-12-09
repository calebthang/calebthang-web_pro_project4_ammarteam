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

    $params = [];
    $whereConditions = [];
    
    // Base query depending on tab
    if ($tab === 'wishlist') {
        $sql = "SELECT DISTINCT p.* 
                FROM properties p 
                INNER JOIN wishlists w ON p.id = w.property_id 
                WHERE w.user_id = ?";
        $params[] = $userId;
    } else {
        $sql = "SELECT * FROM properties WHERE 1=1";
    }

    // Add search condition
    if ($search) {
        if ($tab === 'wishlist') {
            $sql .= " AND (p.location LIKE ? OR p.title LIKE ? OR p.description LIKE ?)";
        } else {
            $sql .= " AND (location LIKE ? OR title LIKE ? OR description LIKE ?)";
        }
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    // Add property type filter
    if ($type) {
        if ($tab === 'wishlist') {
            $sql .= " AND p.property_type = ?";
        } else {
            $sql .= " AND property_type = ?";
        }
        $params[] = $type;
    }

    // Add price range filter
    if ($price) {
        list($min, $max) = explode('-', $price);
        if ($tab === 'wishlist') {
            if ($max) {
                $sql .= " AND p.price BETWEEN ? AND ?";
            } else {
                $sql .= " AND p.price >= ?";
            }
        } else {
            if ($max) {
                $sql .= " AND price BETWEEN ? AND ?";
            } else {
                $sql .= " AND price >= ?";
            }
        }
        $params[] = (float)$min;
        if ($max) {
            $params[] = (float)$max;
        }
    }

    // Add bedrooms filter
    if ($beds) {
        $beds = str_replace('+', '', $beds); // Remove + if present
        if ($tab === 'wishlist') {
            $sql .= " AND p.bedrooms >= ?";
        } else {
            $sql .= " AND bedrooms >= ?";
        }
        $params[] = (int)$beds;
    }

    error_log("SQL Query: " . $sql);
    error_log("Parameters: " . print_r($params, true));

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

    error_log("Found " . count($properties) . " properties");
    
    echo json_encode($properties);

} catch(Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}
?>