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
        $StartDate = date('Y-m-d'); // Set start date to today
        $borrowDuration = $_POST['borrowDuration'];
        $ReturnDate = date('Y-m-d', strtotime($StartDate . ' + ' . $borrowDuration . ' days'));

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
            header("Location: borrowSuccess.php");
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
    <link rel="stylesheet" href="css/BorrowDetailsstyle.css">
</head>

<body>
<header class="header">
    <div class="logo">
        <a href="index.php"><i class="fa-solid fa-book"></i> Knowledge Nest</a>
    </div>
    <nav class="nav-bar">
        <ul class="nav__links">
            <li><a href="index.php">Home</a></li>
            <li><a href="#CSection">Contact</a></li>
            <li><a href="books.php">Books</a></li>
            <?php if ($is_logged_in): ?>
                <?php if ($userType === 'user'): ?>
                    <li><a href="myAccount.php">My Account</a></li>
                <?php elseif ($userType === 'admin'): ?>
                    <li><a href="dashboard.php">Admin Dashboard</a></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="login">
        <?php if ($is_logged_in): ?>
            <form method="GET" action="index.php">
                <button type="submit" name="logout">
                    <i class="fa-solid fa-sign-out-alt"></i><b class="logout-text">Logout</b>
                </button>
            </form>
        <?php else: ?>
            <a href="login.php" class="login-icon">
                <button><i class="fa-solid fa-user"></i><b class="login-text">Login</b></button>
            </a>
        <?php endif; ?>
    </div>
</header>
<main>
    <div class="reservation-container">
        <h1>Borrow Details</h1>
        <form method="POST">
            <p><strong>User Name:</strong> <?= htmlspecialchars($userName) ?></p>
            <p><strong>Book Title:</strong> <?= htmlspecialchars($bookTitle) ?></p>
            <p>
                <strong>Start Date:</strong>
                <?= date('Y-m-d') ?> (Today)
            </p>
            <p class="duration-field">
                <strong>Borrow Duration:</strong>
                <select name="borrowDuration" id="borrowDuration" required class="duration-select">
                    <option value="7">7 Days</option>
                    <option value="10">10 Days</option>
                    <option value="14">14 Days</option>
                </select>
            </p>
          
            
            <button type="submit">Confirm Borrow</button>
        </form>

        <a href="books.php">Back to Books</a>
    </div>
</main>

<script>
window.onload = function() {
    const borrowDuration = document.getElementById('borrowDuration');
    

    function updateReturnDate() {
        const today = new Date();
        const duration = parseInt(borrowDuration.value);
        
        returnDate.setDate(returnDate.getDate() + duration);
        returnDateDisplay.textContent = returnDate.toISOString().split('T')[0];
    }

    borrowDuration.addEventListener('change', updateReturnDate);
    updateReturnDate(); // Set initial return date
}
</script>
</body>
</html>
