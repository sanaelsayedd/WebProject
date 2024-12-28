<?php
session_start();

// Check if the user is logged in
$is_logged_in = isset($_SESSION['username']);
$userType = $is_logged_in ? $_SESSION['userType'] : null; 


if (!$is_logged_in) {
    header("Location: login.php");
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy(); 
    header("Location: index.php"); 
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Success</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: linear-gradient(rgba(44, 24, 16, 0.7), rgba(44, 24, 16, 0.7)), url("css/Image/Knowledge\ Nest.webp");
            width: 100%;
            min-height: 100vh;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .popup {
            background-color: #faf6f1;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(44, 24, 16, 0.2);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        h1 {
            color: #2c1810;
            font-size: 28px;
            margin-bottom: 20px;
        }

        p {
            color: #5c3324;
            font-size: 16px;
            margin-bottom: 30px;
        }

        .home-btn {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #2c1810 0%, #5c3324 100%);
            color: #faf6f1;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            transition: opacity 0.3s ease;
        }

        .home-btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="popup">
        <h1>Purchase Successful!</h1>
        <p>Your purchase was successful. Thank you for your purchase!</p>
        <a href="index.php" class="home-btn">Go to Home</a>
    </div>
</body>
</html>
