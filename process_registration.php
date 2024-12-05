<?php
session_start();

// Database connection
function connectDB() {
    $host = 'localhost';
    $dbname = 'kle53';
    $username = 'kle53';
    $password = 'kle53';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $userType = $_POST['userType'];
    $password = $_POST['password'];

    // Additional validation
    if (strlen($password) < 8) {
        $_SESSION['error'] = "Password must be at least 8 characters long";
        header("Location: register.php");
        exit();
    }

    try {
        $pdo = connectDB();

        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Email already registered";
            header("Location: register.php");
            exit();
        }

        // Check if username already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Username already taken";
            header("Location: register.php");
            exit();
        }

        // Insert new user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("
            INSERT INTO users (first_name, last_name, email, username, password, user_type, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([$firstName, $lastName, $email, $username, $hashedPassword, $userType]);

        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: login.php");
        exit();

    } catch(PDOException $e) {
        $_SESSION['error'] = "Registration failed: " . $e->getMessage();
        header("Location: register.php");
        exit();
    }
}
?>
