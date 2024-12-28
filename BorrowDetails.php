<?php
session_start();

$is_logged_in = isset($_SESSION['username']);
$userType = $is_logged_in ? $_SESSION['userType'] : null; 

if (!$is_logged_in) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['BookID'], $_GET['Title'], $_GET['UserName'])) {
    $bookTitle = $_GET['Title'];
    $userName = $_GET['UserName'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = ""; 
    $dbname = "library"; 

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $StartDate = $_POST['StartDate'];
        $ReturnDate = date('Y-m-d', strtotime($StartDate . ' + 14 days')); // Set return date to 14 days after start

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
            echo "<script>
                    alert('You have successfully borrowed the book!');
                  </script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
} else {
   
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
        <form method="POST">
            <p><strong>User Name:</strong> <?= htmlspecialchars($userName) ?></p>
            <p><strong>Book Title:</strong> <?= htmlspecialchars($bookTitle) ?></p>
            <p>
                <strong>Reservation Date:</strong>
                <input type="date" name="StartDate" required min="<?= date('Y-m-d') ?>">
            </p>
            <p><em>Note: Return date will be set to 14 days after reservation date</em></p>
            
            <button type="submit">Confirm Borrow</button>
        </form>

        <a href="books.php">Back to Books</a>
    </div>
</body>
</html>
