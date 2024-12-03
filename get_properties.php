<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';
header('Content-Type: application/json');

try {
   $pdo = connectDB();
   error_log("Database connected");

   $tab = $_GET['tab'] ?? 'all';
   $search = $_GET['search'] ?? '';
   $type = $_GET['type'] ?? '';
   $price = $_GET['price'] ?? '';
   $beds = $_GET['beds'] ?? '';

   $params = [];
   $whereConditions = [];

   if ($search) {
       $whereConditions[] = "(location LIKE ? OR title LIKE ? OR description LIKE ?)";
       $params[] = "%$search%";
       $params[] = "%$search%";
       $params[] = "%$search%";
   }

   if ($type) {
       $whereConditions[] = "property_type = ?";
       $params[] = $type;
   }

   if ($price) {
       list($min, $max) = explode('-', $price);
       if ($max) {
           $whereConditions[] = "price BETWEEN ? AND ?";
           $params[] = $min;
           $params[] = $max;
       } else {
           $whereConditions[] = "price >= ?";
           $params[] = $min;
       }
   }

   if ($beds) {
       $whereConditions[] = "bedrooms >= ?";
       $params[] = $beds;
   }

   $whereClause = $whereConditions ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

   if ($tab === 'wishlist') {
       $sql = "SELECT DISTINCT p.* FROM properties p 
               INNER JOIN wishlists w ON p.id = w.property_id 
               WHERE w.user_id = ?";
       if (!empty($whereConditions)) {
           $sql .= " AND " . implode(' AND ', $whereConditions);
       }
       $params = array_merge([$_SESSION['user_id']], $params);
       $stmt = $pdo->prepare($sql);
       $stmt->execute($params);
   } else {
       $sql = "SELECT * FROM properties $whereClause";
       $stmt = $pdo->prepare($sql);
       $stmt->execute($params);
   }

   error_log("SQL Query: " . $sql);
   error_log("Parameters: " . json_encode($params));

   $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
   error_log("Found " . count($properties) . " properties");
   error_log("Properties data: " . json_encode($properties));

   echo json_encode($properties);
} catch(PDOException $e) {
   error_log("Database error: " . $e->getMessage());
   echo json_encode(['error' => $e->getMessage()]);
} catch(Exception $e) {
   error_log("General error: " . $e->getMessage());
   echo json_encode(['error' => $e->getMessage()]);
}
?>