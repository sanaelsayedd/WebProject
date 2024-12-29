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
    <title>Borrow Success</title>
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

        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #2c1810 0%, #5c3324 100%);
            color: #faf6f1;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            transition: opacity 0.3s ease;
            margin: 0 10px;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <div class="popup">
        <h1>Borrow Successful!</h1>
        <p>You have successfully borrowed the book!</p>
        <div class="btn-container">
            <a href="books.php" class="btn">Borrow More Books</a>
            <a href="myAccount.php" class="btn">View My Borrowed Books</a>
        </div>
    </div>
</body>
</html>