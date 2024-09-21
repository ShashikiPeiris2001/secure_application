<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .dashboard-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        .dashboard-container h1 {
            margin-top: 0;
            color: #333;
        }

        .dashboard-container p {
            color: #666;
            margin: 10px 0;
        }

        .logout-button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .logout-button:hover {
            background-color: #45a049;
        }
        
        .nav {
            text-align: center;
            margin-top: 20px;
        }

        .nav a {
            color: #4CAF50;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }

        .nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome to your Admin Dashboard</h1>
        <p><?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo htmlspecialchars($_SESSION['email']); ?>)</p>
        
        <div class="nav">
            <a href="view_users.php">View Users</a>
            <a href="settings.php">Settings</a>
            <!-- Add more navigation links as needed -->
        </div>

        <a href="logout.php" class="logout-button">Logout</a>
    </div>
</body>
</html>
