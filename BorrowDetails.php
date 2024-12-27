<?php
session_start();

$is_logged_in = isset($_SESSION['username']);
$userType = $is_logged_in ? $_SESSION['userType'] : null; 

if (!$is_logged_in) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['BookID'], $_GET['Title'], $_GET['UserName'], $_GET['StartDate'], $_GET['ReturnDate'])) {
    $bookTitle = $_GET['Title'];
    $userName = $_GET['UserName'];
    $StartDate = $_GET['StartDate'];
    $ReturnDate = $_GET['ReturnDate'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = ""; 
    $dbname = "library"; 

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch UserID based on UserName
    $userQuery = "SELECT UserID FROM user WHERE UserName = '$userName'";
    $userResult = $conn->query($userQuery);
    if ($userResult && $userResult->num_rows > 0) {
        $userRow = $userResult->fetch_assoc();
        $userID = $userRow['UserID'];
    } else {
        die("User not found.");
    }

    // Fetch BookID based on Book Title
    $bookQuery = "SELECT BookID FROM book WHERE Title = '$bookTitle'";
    $bookResult = $conn->query($bookQuery);
    if ($bookResult && $bookResult->num_rows > 0) {
        $bookRow = $bookResult->fetch_assoc();
        $bookID = $bookRow['BookID'];
    } else {
        die("Book not found.");
    }

    // Insert into borrow table
    $sql = "INSERT INTO `borrow`(`UserID`, `BookID`, `IssueDate`, `StartDate`, `ReturnDate`) 
            VALUES ('$userID', '$bookID', NOW(), '$StartDate', '$ReturnDate')";

    if ($conn->query($sql) === TRUE) {
        // Show borrow details
        echo "<script>
                alert('You have successfully borrowed the book!');
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    // Redirect to the borrow page if required parameters are not set
    header("Location: books.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .reservation-container {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 500px;
            text-align: center;
        }

        .reservation-container h1 {
            color: #007bff;
            margin-bottom: 20px;
        }

        .reservation-container p {
            font-size: 16px;
            margin: 10px 0;
            color: #343a40;
        }

        .reservation-container p strong {
            color: #495057;
        }

        .reservation-container a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .reservation-container a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="reservation-container">
        <h1>Borrow Details</h1>
        <p><strong>User Name:</strong> <?= htmlspecialchars($userName) ?></p>
        <p><strong>Book Title:</strong> <?= htmlspecialchars($bookTitle) ?></p>
        <p><strong>Reservation Date:</strong> <?= htmlspecialchars($StartDate) ?></p>
        <p><strong>Return Date:</strong> <?= htmlspecialchars($ReturnDate) ?></p>

        <a href="books.php">Back to Books</a>
    </div>

    <!-- <script>
        function() {
            window.location.href = 'books.php';
        } 
    </script> -->
</body>
</html>
