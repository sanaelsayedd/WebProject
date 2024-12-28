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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/Bookdetails.css">

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
