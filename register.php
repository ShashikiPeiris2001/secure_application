
<?php
// Include database connection (replace with your actual database connection file)
include('db.php');

// Initialize error message variable
$error_message = '';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Check if the email or username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "Email or Username already exists.";
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert user into database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                // Registration successful, redirect to otp verification
                header("Location: otp_verification.php");
                exit();
            } else {
                $error_message = "Error: " . $stmt->error;
            }
        }

        $stmt->close();
    }
}

// Close database connection
$conn->close();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        .register-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            position: relative;
        }

        .register-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .register-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .password-container {
            position: relative;
        }

        .register-form input[type="text"], 
        .register-form input[type="email"],
        .register-form input[type="password"] {
            width: calc(100% - 30px); /* Space for the icon */
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .view-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 10;
        }

        .register-button {
            width: 100%;
            padding: 10px;
            background-color: #A020F0;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .register-button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .strength-meter {
            height: 5px;
            margin-bottom: 10px;
            border-radius: 5px;
            background-color: #ddd;
        }

        .strength-meter-bar {
            height: 100%;
            width: 0;
            border-radius: 5px;
            transition: width 0.3s;
        }

        .strength-text {
            text-align: center;
            margin-bottom: 10px;
        }

        p {
            text-align: center;
            margin-top: 15px;
        }

        p a {
            color: #4CAF50;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function validateForm() {
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;

            // Email format validation
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            // Password strength validation
            var passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/;
            if (!passwordPattern.test(password)) {
                alert("Password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a number, and a special character.");
                return false;
            }

            // Confirm password validation
            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }

            return true;
        }

        function updatePasswordStrength() {
            var password = document.getElementById("password").value;
            var strengthBar = document.getElementById("strength-bar");
            var strengthText = document.getElementById("strength-text");
            var strength = 0;

            if (password.length >= 8) {
                strength += 1;
            }
            if (/[A-Z]/.test(password)) {
                strength += 1;
            }
            if (/[a-z]/.test(password)) {
                strength += 1;
            }
            if (/\d/.test(password)) {
                strength += 1;
            }
            if (/[\W_]/.test(password)) {
                strength += 1;
            }

            var strengthPercentage = strength * 20;
            var color;
            var text;

            switch (strength) {
                case 1:
                case 2:
                    color = "red";
                    text = "Weak";
                    break;
                case 3:
                    color = "orange";
                    text = "Moderate";
                    break;
                case 4:
                case 5:
                    color = "green";
                    text = "Strong";
                    break;
                default:
                    color = "red";
                    text = "Weak";
            }

            strengthBar.style.width = strengthPercentage + "%";
            strengthBar.style.backgroundColor = color;
            strengthText.textContent = text;
        }

        function togglePasswordVisibility(inputId, iconId) {
            var passwordInput = document.getElementById(inputId);
            var icon = document.getElementById(iconId);
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</head>
<body>
    <div class="register-container">
        <form action="register.php" method="post" class="register-form" onsubmit="return validateForm()">
            <h2>Create an Account</h2>

            <!-- Display error messages if any -->
            <?php if (!empty($error_message)) { ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php } ?>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="password">Password:</label>
            <div class="password-container">
                <input type="password" id="password" name="password" required oninput="updatePasswordStrength()">
                <i class="fas fa-eye view-icon" id="toggle-password" onclick="togglePasswordVisibility('password', 'toggle-password')"></i>
            </div><br>

            <label for="confirm_password">Confirm Password:</label>
            <div class="password-container">
                <input type="password" id="confirm_password" name="confirm_password" required>
                <i class="fas fa-eye view-icon" id="toggle-confirm-password" onclick="togglePasswordVisibility('confirm_password', 'toggle-confirm-password')"></i>
            </div><br>

            <!-- Strength meter -->
            <div class="strength-meter">
                <div id="strength-bar" class="strength-meter-bar"></div>
            </div>
            <div id="strength-text" class="strength-text">Weak</div>

            <button type="submit" class="register-button">Register</button>
        </form>
    </div>
</body>
</html>