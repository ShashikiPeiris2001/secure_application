<?php
session_start();

// Example: Only admin can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: unauthorized.php");
    exit();
}

echo "Welcome, Admin!";
?>
