<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Require Composer's autoloader for PHPMailer
require 'vendor/autoload.php';

// Initialize variables
$otpSent = false;
$otpVerified = false;
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $generatedOtp = $_POST['generated_otp']; // Get the generated OTP from the front end

    try {
        // Initialize PHPMailer
        $mail = new PHPMailer(true);

        // SMTP server configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // Set the SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'chandurespriyavindya@gmail.com'; // Your Gmail address
        $mail->Password   = 'sxzw evcv ssxu ygow';    // Your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipient details
        $mail->setFrom('noreply@yourdomain.com', 'Your Company Name');
        $mail->addAddress($email); // Send to the user's email

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'OTP Authentication';
        $mail->Body    = 'Your OTP is: <b>' . $generatedOtp . '</b>';

        // Send mail
        $mail->send();
        $otpSent = true;
        $errorMsg = 'OTP has been sent to ' . $email;
    } catch (Exception $e) {
        $errorMsg = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Verify OTP if submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['entered_otp']) && isset($_POST['generated_otp'])) {
    $enteredOtp = $_POST['entered_otp'];
    $generatedOtp = $_POST['generated_otp'];  // Hidden field with the generated OTP

    if ($enteredOtp == $generatedOtp) {
        $otpVerified = true;
        $errorMsg = "OTP verified successfully! Redirecting to login...";
        
        // Pop-up alert for OTP verification success using JavaScript
        echo "<script>alert('OTP verified successfully! Redirecting to login...');</script>";
        
        // Redirect to login.php after showing the alert
        echo "<script>setTimeout(function(){ window.location.href = 'login.php'; }, 2000);</script>";
    } else {
        $errorMsg = "Invalid OTP! Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
            color: #555;
        }

        input[type="email"],
        input[type="number"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
        }

        button {
            padding: 10px;
            background-color: #A020F0;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        h3 {
            text-align: center;
            color: #28a745;
        }

        p {
            text-align: center;
            color: #777;
        }
    </style>

    <script>
        // Function to generate a random OTP
        function generateOtp() {
            return Math.floor(10000 + Math.random() * 90000);  // Generate a 5-digit OTP
        }

        // Function to handle the OTP generation and form submission
        function handleOtpGeneration() {
            var otp = generateOtp();  // Generate OTP
            document.getElementById('generated_otp').value = otp;  // Set OTP in hidden field
            return true;  // Allow the form to submit
        }
    </script>
</head>
<body>

<div class="container">
    <h2>OTP Verification</h2>

    <!-- Show success message if OTP was sent -->
    <?php if ($otpSent && !$otpVerified): ?>
        <h3><?php echo $errorMsg; ?></h3>
        <!-- OTP verification form -->
        <form action="" method="POST">
            <label for="entered_otp">Enter OTP sent to your email:</label>
            <input type="number" name="entered_otp" placeholder="Enter OTP" required>
            <input type="hidden" name="generated_otp" value="<?php echo $_POST['generated_otp']; ?>">
            <button type="submit">Verify OTP</button>
        </form>

    <?php elseif ($otpVerified): ?>
        <!-- Show verification success message -->
        <h3><?php echo $errorMsg; ?></h3>
        <p>Redirecting to login...</p>

    <?php else: ?>
        <!-- Form to send OTP -->
        <form action="" method="POST" onsubmit="return handleOtpGeneration();">
            <label for="email">Enter your Email:</label>
            <input type="email" name="email" placeholder="Enter the Email" required>
            <input type="hidden" name="generated_otp" id="generated_otp" value="">
            <button type="submit">Send OTP</button>
        </form>
    <?php endif; ?>

    <!-- Show error message if applicable -->
    <?php if (!$otpSent || $otpVerified): ?>
        <h3><?php echo $errorMsg; ?></h3>
    <?php endif; ?>
</div>

</body>
</html>
