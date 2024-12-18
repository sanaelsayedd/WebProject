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
$password = "WEBDBwebdb123456789"; 
$dbname = "library"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT `BookID`, `Title`, `Author`, `Status`, `Edition`, `Price`, `Quantity`, `Category` FROM `book`";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knowledge Nest - Books</title>
    <link rel="stylesheet" href="css/booksStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
    <aside class="sideMenu">
        <?php if ($userType === 'admin'): ?>
            <div class="admin">
                <h1>Admin</h1>
                <a href="AddBook.php">Add Book</a>
            </div>
        <?php endif; ?>
        <div class="categories">
            <h1>Categories</h1>
            <a href="">Programming</a>
            <a href="">Language</a>
            <a href="">Design</a>
            <a href="">Science</a>
            <a href="">Business</a>
        </div>
    </aside>

    <section class="searchBar">
        <div class="search-box">
            <input type="text" class="input" placeholder="Search">
            <button><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
    </section>

    <section class="list-books">
    <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="book-card">';
                echo '<img src="' . htmlspecialchars($row['ImagePath'] ?? 'css/Image/Book2.jpg') . '" alt="Book Cover" class="book-image">';
                echo '<div class="book-details">';
                echo '<h2 class="book-title">' . htmlspecialchars($row['Title']) . '</h2>';
                echo '<p class="book-author"><strong>Author:</strong> ' . htmlspecialchars($row['Author']) . '</p>';
                echo '<p class="book-type"><strong>Status:</strong> ' . htmlspecialchars($row['Status']) . '</p>';
                echo '<p class="book-type"><strong>Edition:</strong> ' . htmlspecialchars($row['Edition']) . '</p>';
                echo '<p class="book-price"><strong>Price:</strong> $' . htmlspecialchars($row['Price']) . '</p>';
                echo '<p class="book-quantity"><strong>Quantity:</strong> ' . htmlspecialchars($row['Quantity']) . '</p>';
                echo '<p class="book-category"><strong>Category:</strong> ' . htmlspecialchars($row['Category']) . '</p>';
                echo '</div>'; 
                echo '<div class="book-actions">';

                // Always show the "Details" button
                echo '<a href="BookDetails.php?BookID=' . $row['BookID'] . '" class="action-link">
                        <i class="fas fa-info-circle detail-icon" title="Details"></i>
                    </a>';

                // Show "Edit" and "Remove" only for admins
                if ($userType === 'admin') {
                    echo '<a href="DeleteBook.php?BookID=' . $row['BookID'] . '" class="action-link">
                            <i class="fas fa-trash-alt remove-icon" title="Remove"></i>
                        </a>';
                    echo '<a href="EditBook.php?BookID=' . $row['BookID'] . '" class="action-link">
                            <i class="fas fa-edit edit-icon" title="Edit"></i>
                        </a>';
                }

                echo '</div>'; 
                echo '</div>'; 
            }
        } else {
            echo '<div class="no-books">';
            echo '<p>No books found!</p>';
            if ($userType === 'admin') {
                echo '<p>Add new books to the library.</p>';
                echo '<a href="AddBook.php" class="add-book-button">Add Book</a>';
            }
            echo '</div>';
        }
        ?>

    </section>
    
</main>

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

<script src="js/script.js"></script>
</body>
</html>
<?php

$conn->close();
?>