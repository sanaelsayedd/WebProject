<?php
session_start();

// Check if the user is logged in
$is_logged_in = isset($_SESSION['username']);
$userType = $is_logged_in ? $_SESSION['userType'] : null; 
$userName = $is_logged_in ? $_SESSION['username'] : null;

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

// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "library"; 

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch book details if BookID is provided
if (isset($_GET['BookID']) && is_numeric($_GET['BookID'])) {
    $bookID = $_GET['BookID'];

    // Fetch book details first
    $query = "SELECT * FROM book WHERE BookID = ?";
    $stmt = $conn->prepare($query);
    $result = null;

    if ($stmt) {
        $stmt->bind_param("i", $bookID);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    }

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $is_available = $row['Quantity'] > 0;

        // Check if the user already has the book
        $hasBook = false;
        if ($is_logged_in) {
            $checkBookQuery = "SELECT COUNT(*) as count FROM (
                SELECT b.BookID 
                FROM borrow b
                JOIN user u ON b.UserID = u.UserID 
                WHERE b.BookID = ? AND u.UserName = ? AND (b.ReturnDate IS NULL OR b.ReturnDate > NOW())
                UNION
                SELECT pt.BookID 
                FROM purchase_transaction pt
                JOIN user u ON pt.UserID = u.UserID
                WHERE pt.BookID = ? AND u.UserName = ?
            ) AS combined";

            $stmt = $conn->prepare($checkBookQuery);
            if ($stmt) {
                $stmt->bind_param("isis", $bookID, $userName, $bookID, $userName);
                $stmt->execute();
                $checkResult = $stmt->get_result();
                if ($checkRow = $checkResult->fetch_assoc()) {
                    $hasBook = $checkRow['count'] > 0;
                }
                $stmt->close();
            }
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/BookDetailsStyle.css">
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
    <div class="book-details-card">
        <div class="book-image">
            <img src="<?= $row['ImagePath'] ?? 'css/Image/Book2.jpg' ?>" alt="Book Cover">
        </div>
        <div class="book-info">
            <h1 class="book-title"><?= $row['Title'] ?></h1>
            <p><strong>Author:</strong> <?= $row['Author'] ?></p>
            <p><strong>Edition:</strong> <?= $row['Edition'] ?></p>
            <p><strong>Category:</strong> <?= $row['Category'] ?></p>
            <p><strong>Price:</strong> $<?= $row['Price'] ?></p>
            <p><strong>Description:</strong> <?= $row['Description'] ?></p>
            <p><strong>Quantity Available:</strong> <?= $row['Quantity'] ?></p>
            <div class="action-buttons">
                <?php if ($hasBook): ?>
                    
                    <button class="account-btn" onclick="window.location.href='myAccount.php'">View in My Account</button>
                    <button class="account-btn" onclick="window.location.href='books.php'">Find Other Books</button>
                    <p style="color: #e6b17e; margin-bottom: 10px; display: block;">You already have this book.</p>
                <?php elseif ($is_available): ?>
                    <?php if ($userType === 'admin'): ?>
                        <button class="purchase-btn" onclick="window.location.href='Purchase.php?BookID=<?= $row['BookID'] ?>&admin=true'">Purchase</button>
                        <button class="borrow-btn" onclick="window.location.href='borrowBook.php?BookID=<?= $row['BookID'] ?>'">Borrow</button>
                    <?php elseif ($userType === 'user'): ?>
                        <button class="purchase-btn" onclick="window.location.href='purchaseBook.php?BookID=<?= $row['BookID'] ?>'">Purchase</button>
                        <button class="borrow-btn" onclick="window.location.href='BorrowDetails.php?BookID=<?= $bookID ?>&Title=<?= urlencode($row['Title']) ?>&UserName=<?= urlencode($userName) ?>&StartDate=<?= date('Y-m-d') ?>&ReturnDate=<?= date('Y-m-d', strtotime('+20 days')) ?>'">Borrow</button>
                    <?php endif; ?>
                <?php else: ?>
                    <button class="purchase-btn" style="background-color: #ff4444;" disabled>Out of Stock</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <a href="javascript:history.back()" class="back-button">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
</main>
</body>
</html>

<?php
    } else {
        echo "<p>Book not found!</p>";
    }
} else {
    echo "<p>Invalid Book ID!</p>";
}

$conn->close();
?>
