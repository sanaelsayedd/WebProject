<?php

session_start();

$is_logged_in = isset($_SESSION['username']);
$userType = $is_logged_in ? $_SESSION['userType'] : null; 

if (!$is_logged_in) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy(); 
    header("Location: index.php"); 
    exit();
}

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "library"; 


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['BookID']) && is_numeric($_GET['BookID'])) {
    $bookID = $_GET['BookID'];

    
    $query = "SELECT * FROM book WHERE BookID = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }

    $stmt->bind_param("i", $bookID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

      
        $is_available = $row['Quantity'] > 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <link rel="stylesheet" href="css/BookDetailsStyle.css">
</head>
<body>
<header class="header">
    <div class="logo">
        <a href="index.php">Knowledge Nest</a>
    </div>
    <nav class="nav-bar">
        <ul class="nav__links">
            <li><a href="index.php">Home</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact</a></li>
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

    <div class="toggle-btn">
        <i class="fa-solid fa-bars"></i>
    </div>
    <div class="dropdown-menu">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="books.php">Books</a></li>
            <?php if ($is_logged_in): ?>
                <?php if ($userType === 'user'): ?>
                    <li><a href="account.php">My Account</a></li>
                <?php elseif ($userType === 'admin'): ?>
                    <li><a href="dashboard.php">Admin Dashboard</a></li>
                <?php endif; ?>
                <li>
                    <form method="GET" action="index.php">
                        <button type="submit" name="logout">
                            <i class="fa-solid fa-sign-out-alt"></i><b class="logout-text">Logout</b>
                        </button>
                    </form>
                </li>
            <?php else: ?>
                <li>
                    <a href="login.php" class="login-icon">
                        <button><i class="fa-solid fa-user"></i><b>Login</b></button>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</header>
    <div class="book-details-card">
        <div class="book-image">
            <img src="<?= htmlspecialchars($row['ImagePath'] ?? 'css/Image/Book2.jpg') ?>" alt="Book Cover">
        </div>
        <div class="book-info">
            <h1 class="book-title"><?= htmlspecialchars($row['Title']) ?></h1>
            <p><strong>Author:</strong> <?= htmlspecialchars($row['Author']) ?></p>
            <p><strong>Edition:</strong> <?= htmlspecialchars($row['Edition']) ?></p>
            <p><strong>Category:</strong> <?= htmlspecialchars($row['Category']) ?></p>
            <p><strong>Price:</strong> $<?= htmlspecialchars($row['Price']) ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($row['Description']) ?></p> 
            <p><strong>Quantity Available:</strong> <?= htmlspecialchars($row['Quantity']) ?></p>

            <div class="action-buttons">
                <?php if ($is_available): ?>
                    <button class="purchase-btn" onclick="window.location.href='purchaseBook.php?BookID=<?= $row['BookID'] ?>'">Purchase</button>
                    <button class="borrow-btn" onclick="window.location.href='borrowBook.php?BookID=<?= $row['BookID'] ?>'">Borrow</button>
                <?php else: ?>
                    <button class="purchase-btn" style="background-color: red;" disabled>Out of Stock</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <footer>
    <div class="footer-container">
        <div class="footer-logo-section">
            <img src="Image/KnowledgeNest-noBK.png" alt="Harvard Shield" class="footer-logo">
        </div>

        <div class="footer-content">
            <div class="footer-links">
                <div class="link-column">
                    <p>GIVING TO THE LIBRARY</p>
                    <p>OFFICE OF THE PROVOST</p>
                    <p>HOLLIS</p>
                    <p>HOLLIS FOR ARCHIVAL DISCOVERY</p>
                    <p>DATABASES</p>
                </div>

                <div class="link-column">
                    <p>NEWSLETTERS/SOCIAL</p>
                    <p>STAFF PORTAL</p>
                    <p>LIBRARY ACCESSIBILITY</p>
                    <p>REPORT A PROBLEM</p>
                </div>

                <div class="link-column">
                    <div class="footer-policy-links">
                        <a href="#">Accessibility</a>
                        <a href="#">Privacy</a>
                    </div>
                </div>
            </div>

            <p class="footer-license">
                Creative Commons Attribution 4.0 International License. Except where otherwise noted, 
                this work is subject to a <a href="#">Creative Commons Attribution 4.0 International License</a> 
                which allows anyone to share and adapt our material as long as proper attribution is given. 
                For details and exceptions, see the <a href="#">Harvard Library Copyright Policy</a> 
                &copy;2024 Presidents and Fellows of Harvard College.
            </p>
        </div>
    </div>
</footer>
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
