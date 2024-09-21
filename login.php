<?php
// Include database connection
include 'db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $username_or_email = sanitize_input($_POST['username_or_email']);
    $password = sanitize_input($_POST['password']);

    // Prepared statement to find the user by username or email
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username_or_email, $username_or_email); // Bind the same variable for username or email
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['email'] = $row['email'];

            // Log user activity
            $user_id = $row['id']; // Assuming `id` is the primary key in the `users` table
            $activity = "User logged in";

            $log_stmt = $conn->prepare("INSERT INTO user_activity (user_id, activity) VALUES (?, ?)");
            $log_stmt->bind_param("is", $user_id, $activity);
            $log_stmt->execute();

            // Redirect based on role
            if ($row['role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            $error_message = "Invalid password";
        }
    } else {
        $error_message = "No user found with that username or email";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Centering the form on the page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .login-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .login-form input[type="text"], 
        .login-form input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-button {
            width: 100%;
            padding: 10px;
            background-color: #A020F0;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        p {
            text-align: center;
            margin-top: 15px;
        }

        p a {
            color: #A020F0;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <form action="login.php" method="post" class="login-form">
            <h2>Login</h2>

            <!-- Display error messages if any -->
            <?php if (!empty($error_message)) { ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php } ?>

            <label for="username_or_email">Username or Email:</label>
            <input type="text" id="username_or_email" name="username_or_email" required><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>

            <button type="submit" class="login-button">Login</button>

            <!-- Add a link to the registration page -->
            <p>Don't have an account? <a href="register.php">Signup</a>.</p>
        </form>
    </div>
</body>
</html>
