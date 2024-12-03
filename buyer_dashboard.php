<?php
require_once 'auth_middleware.php';
checkAuth();
checkUserType(['buyer']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Dashboard - Property Connect</title>
    <style>
        /* Add your dashboard styles here */
        .dashboard-header {
            background: #fff;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .user-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logout-btn {
            background: #dc2626;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="dashboard-header">
        <div class="user-info">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
    <!-- Add your dashboard content here -->
</body>
</html>