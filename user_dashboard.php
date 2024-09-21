<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #e9ecef;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .dashboard-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
            max-width: 90%;
        }

        h2 {
            color: #343a40;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
        }

        .welcome-message {
            margin-bottom: 30px;
            font-size: 18px;
            color: #495057;
            line-height: 1.4;
        }

        .logout-button {
            padding: 12px 25px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .logout-button:hover {
            background-color: #218838;
        }

        /* Responsive styles */
        @media (max-width: 600px) {
            .dashboard-container {
                width: 90%;
                padding: 20px;
            }

            h2 {
                font-size: 20px;
            }

            .welcome-message {
                font-size: 16px;
            }

            .logout-button {
                padding: 10px 20px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>User Dashboard</h2>

        <!-- Welcome message -->
        <div class="welcome-message">
            <?php echo "Welcome to your User Dashboard, " . htmlspecialchars($_SESSION['username']) . " (" . htmlspecialchars($_SESSION['email']) . ")"; ?>
        </div>

        <!-- Logout button -->
        <form action="logout.php" method="post">
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </div>
</body>
</html>
