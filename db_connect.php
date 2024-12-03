<!-- db_connect.php -->
<?php
$host = '127.0.0.1';
$dbname = 'web_pro_project4_ammarteam';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";  // We'll remove this later, it's just for testing
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>