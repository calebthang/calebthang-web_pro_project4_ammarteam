<?php
require_once 'db_connect.php';

if(isset($pdo)) {
    echo "<h3>Database connection is working!</h3>";
    
    // Test query
    try {
        $query = "SELECT 1";
        $stmt = $pdo->query($query);
        echo "<p>Query executed successfully!</p>";
    } catch(PDOException $e) {
        echo "<p>Query error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<h3>Database connection failed!</h3>";
}
?>