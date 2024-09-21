<?php
// db.php - Database connection file with prepared statements for protection against SQL injection
$servername = "localhost"; // or your server
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "secure_app"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

?>