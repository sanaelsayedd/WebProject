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
$dbname = "library"; 

$conn = new mysqli($servername, $username, "", $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$borrowQuery = "
    SELECT b.Title, b.Author, b.Edition, br.BorrowID, br.StartDate, br.ReturnDate, b.PDFFile
    FROM borrow br
    INNER JOIN book b ON br.BookID = b.BookID
    WHERE br.UserID = (SELECT UserID FROM user WHERE Username = '$_SESSION[username]')
";

$borrowResult = $conn->query($borrowQuery);
if (!$borrowResult) {
    echo "Error with the query: " . $conn->error;
}



$purchaseQuery = "
    SELECT b.Title, b.Author, b.Edition, b.PDFFile, pt.PurchaseTransactionID, pt.Quantity, pt.TotalPrice
    FROM purchase_transaction pt
    INNER JOIN book b ON pt.BookID = b.BookID
    WHERE pt.UserID = (SELECT UserID FROM user WHERE Username = '$_SESSION[username]')
";
$purchaseResult = $conn->query($purchaseQuery);
if (!$purchaseResult) {
    echo "Error with the query: " . $conn->error;
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_username'])) {
        $new_username = $_POST['new_username'];
        $current_username = $_SESSION['username'];
        $update_query = "UPDATE user SET UserName = '$new_username' WHERE UserName = '$current_username'";
        if ($conn->query($update_query)) {
            $_SESSION['username'] = $new_username;
            echo "<p>Username updated successfully!</p>";
        } else {
            echo "<p>Error updating username.</p>";
        }
    } elseif (isset($_POST['update_password'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password === $confirm_password) {
            $current_username = $_SESSION['username'];
            $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE user SET Password = '$hashedPassword' WHERE UserName = '$current_username'";
            if ($conn->query($update_query)) {
                echo "<p>Password updated successfully!</p>";
            } else {
                echo "<p>Error updating password.</p>";
            }
        } else {
            echo "<p>Passwords do not match. Please try again.</p>";
        }
    } elseif (isset($_POST['update_email'])) {
        $new_email = $_POST['new_email'];
        $current_username = $_SESSION['username'];
        $update_query = "UPDATE user SET Email = '$new_email' WHERE UserName = '$current_username'";
        if ($conn->query($update_query)) {
            echo "<p>Email updated successfully!</p>";
        } else {
            echo "<p>Error updating email.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <link rel="stylesheet" href="css/myAccountStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
<header class="header">
            <div class="logo">
                <a href="index.php"><i class="fa-solid fa-book"></i> Knowledge Nest</a>
            </div>
            <nav class="nav-bar">
                <ul class="nav__links">
                    <li><a href="index.php">Home</a></li>

                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="books.php">Books</a></li>

                    <?php if ($userType === 'admin') {?>
                    <li><a href="borrowBook.php">Borrow</a></li>
                    <li><a href="reservation.php">Reservation</a></li>
                    <?php }?>
                    
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
                    <li><a href="contact.php">Contact</a></li>
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
            <h1 class="account-title">My Account</h1>

        <main class="account-main">
            <section class="edit-account">
                <h2 class="section-title">Edit Your Account</h2>

                <form action="myAccount.php" method="POST" class="account-form">
                    <h3 class="form-title">Change Username</h3>
                    <input type="text" name="new_username" placeholder="Enter New Username" class="form-input" required>
                    <button type="submit" name="update_username" class="form-button">Update Username</button>
                </form>

                <form action="myAccount.php" method="POST" class="account-form">
                    <h3 class="form-title">Change Email</h3>
                    <input type="email" name="new_email" placeholder="Enter New Email" class="form-input" required>
                    <button type="submit" name="update_email" class="form-button">Update Email</button>
                </form>

                <form action="myAccount.php" method="POST" class="account-form">
                    <h3 class="form-title">Change Password</h3>
                    <input type="password" name="new_password" placeholder="Enter New Password" class="form-input" required>
                    <input type="password" name="confirm_password" placeholder="Confirm New Password" class="form-input" required>
                    <button type="submit" name="update_password" class="form-button">Update Password</button>
                </form>

            </section>

<!-- Borrowed Books Section -->
<section class="borrowed-books">
    <h2 class="section-title">Borrowed Books</h2>
    <?php 
    if ($borrowResult && $borrowResult->num_rows > 0): ?>
        <div class="book-cards">
            <?php while ($row = $borrowResult->fetch_assoc()): ?>
                <div class="book-card" id="book-card-<?php echo $row['BorrowID']; ?>">
                    <img src="<?php echo htmlspecialchars($row['ImagePath'] ?? 'css/Image/Book2.jpg'); ?>" alt="Book Cover" class="book-image">
                    <div class="book-details">
                        <h2 class="book-title"><?php echo htmlspecialchars($row['Title']); ?></h2>
                        <p class="book-author"><strong>Author:</strong> <?php echo htmlspecialchars($row['Author']); ?></p>
                        <p class="book-edition"><strong>Edition:</strong> <?php echo htmlspecialchars($row['Edition']); ?></p>
                    </div>

                                        <div class="book-actions">
                        <?php if (!empty($row['PDFFile']) && file_exists('pdf/' . $row['PDFFile'])): ?>
                            
                            <form action="pdfViewer.php" method="get">
                                <input type="hidden" name="pdf_filename" value="<?php echo htmlspecialchars($row['PDFFile']); ?>">
                                <button type="submit" class="pdf-button">
                                    <i class="fas fa-file-pdf" title="Read PDF"></i> Read PDF
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="pdf-button disabled" disabled>
                                <i class="fas fa-file-pdf" title="PDF Not Available"></i> PDF Not Available
                            </button>
                        <?php endif; ?>
                    </div>

                    <div>
                        <form action="RemoveBorrow.php" method="POST" onsubmit="return confirm('Are you sure you want to return the book?');">
                            <input type="hidden" name="BorrowID" value="<?php echo htmlspecialchars($row['BorrowID']); ?>">
                            <button type="submit" class="reversation-button">
                                <i class="fas fa-bookmark" title="Reversation"></i> Reversation
                            </button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="no-books">You have no borrowed books.</p>
    <?php endif; ?>
</section>


<script>
    window.onload = function() {
        <?php 

        if (isset($_SESSION['deleted']) && $_SESSION['deleted']) {
            echo "alert('The book has been successfully deleted.');";
            unset($_SESSION['deleted']); 
        }
        ?>
    };
</script>


 <!-- help -->
<section class="purchased-books">
    <h2 class="section-title">Purchased Books</h2>
    <?php 
    if ($purchaseResult && $purchaseResult->num_rows > 0): ?>
        <div class="book-cards">
            <?php while ($row = $purchaseResult->fetch_assoc()): ?>
                <div class="book-card" id="book-card-<?php echo $row['PurchaseTransactionID']; ?>">
                    <img src="<?php echo htmlspecialchars($row['ImagePath'] ?? 'css/Image/Book2.jpg'); ?>" alt="Book Cover" class="book-image">
                    <div class="book-details">
                        <h2 class="book-title"><?php echo htmlspecialchars($row['Title']); ?></h2>
                        <p class="book-author"><strong>Author:</strong> <?php echo htmlspecialchars($row['Author']); ?></p>
                        <p class="book-edition"><strong>Edition:</strong> <?php echo htmlspecialchars($row['Edition']); ?></p>
                    </div>
                    <div class="book-actions">
                        <?php if (!empty($row['PDFFile']) && file_exists('pdf/' . $row['PDFFile'])): ?>
                            <!-- Read PDF -->
                            <form action="pdfViewer.php" method="get" class="pdf-form">
                                <input type="hidden" name="pdf_filename" value="<?php echo htmlspecialchars($row['PDFFile']); ?>">
                                <button type="submit" class="pdf-button">
                                    <i class="fas fa-file-pdf" title="Read PDF"></i> Read PDF
                                </button>
                            </form>
                            <!-- Download PDF -->
                            <a href="pdf/<?php echo htmlspecialchars($row['PDFFile']); ?>" download class="pdf-download-link">
                                <button class="pdf-button">
                                    <i class="fas fa-download" title="Download PDF"></i> Download PDF
                                </button>
                            </a>
                        <?php else: ?>
                            <button class="pdf-button disabled" disabled>
                                <i class="fas fa-file-pdf" title="PDF Not Available"></i> PDF Not Available
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="no-books">You have no purchased books.</p>
    <?php endif; ?>
</section>

        </main>


        <footer>
    <div class="footer-container">
        <!-- Logo Section -->
        <div class="footer-logo-section">
            <img src="css/Image/KnowledgeNest-noBK.png" alt="Harvard Shield" class="footer-logo">
        </div>

        <!-- Links and License Section -->
        <div class="footer-content">
            <div class="footer-links">
                <!-- First Column -->
                <div class="link-column">
                    <p>GIVING TO THE LIBRARY</p>
                    <p>OFFICE OF THE PROVOST</p>
                    <p>HOLLIS</p>
                    <p>HOLLIS FOR ARCHIVAL DISCOVERY</p>
                    <p>DATABASES</p>
                </div>

                <!-- Second Column -->
                <div class="link-column">
                    <p>NEWSLETTERS/SOCIAL</p>
                    <p>STAFF PORTAL</p>
                    <p>LIBRARY ACCESSIBILITY</p>
                    <p>REPORT A PROBLEM</p>
                </div>

                <!-- Third Column -->
                <div class="link-column">
                    <div class="footer-policy-links">
                        <a href="#">Accessibility</a>
                        <a href="#">Privacy</a>
                    </div>
                </div>
            </div>

            <!-- License Section -->
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
