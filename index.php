<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Web UI</title>
    <style>
        /* Reset CSS for better cross-browser compatibility */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Style for the body */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navigation bar style */
        nav {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Title of the navbar */
        .nav-title {
            font-size: 20px;
            font-weight: bold;
        }

        /* Navigation buttons */
        .nav-buttons {
            display: flex;
            gap: 15px;
        }

        .nav-buttons a {
            text-decoration: none;
            color: white;
            padding: 8px 15px;
            background-color: #A020F0; /* Purple color */
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .nav-buttons a:hover {
            background-color: #45a049; /* Change to green on hover */
        }

        /* Style for the Home page content */
        .content {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .content h1 {
            font-size: 48px;
            color: #333;
        }
    </style>
</head>
<body>

    <!-- Navigation bar -->
    <nav>
        <div class="nav-title">My Website</div>
        <div class="nav-buttons">
            <a href="#">Home</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </div>
    </nav>

    <!-- Home page content -->
    <div class="content">
        <h1>Welcome Home</h1>
    </div>

</body>
</html>
