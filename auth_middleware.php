<?php
function checkAuth() {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Please login to access this page";
        header("Location: login.php");
        exit();
    }
}

function checkUserType($allowed_types) {
    if (!in_array($_SESSION['user_type'], $allowed_types)) {
        $_SESSION['error'] = "You don't have permission to access this page";
        header("Location: index.php");
        exit();
    }
}
?>