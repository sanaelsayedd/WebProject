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

// Check if there's a search query
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Modify the SQL query if there is a search term
$sql = "SELECT `BookID`, `Title`, `Author`, `Status`, `Edition`, `Price`, `Quantity`, `Category` FROM `book`";
if ($searchQuery) {
    $searchQuery = $conn->real_escape_string($searchQuery);
    $sql .= " WHERE `Title` LIKE '%$searchQuery%' OR `Author` LIKE '%$searchQuery%' OR `Category` LIKE '%$searchQuery%'";
}

$result = $conn->query($sql);

// Fetch all books
$books = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}

$conn->close();
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
                <a href="index.php"><i class="fa-solid fa-book"></i> Knowledge Nest</a>
            </div>
    <nav class="nav-bar">
        <ul class="nav__links">
            <li><a href="index.php">Home</a></li>
            <li><a href="contact.php">Contact</a></li>
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
<aside class="sideMenu">
    <?php if ($userType === 'admin'): ?>
        <div class="admin">
            <h1>Admin</h1>
            <a href="AddBook.php">Add Book</a>
        </div>
    <?php endif; ?>
    <div class="categories">
        <h1>Categories</h1>
        <a href="?search=Programming">Programming</a>
        <a href="?search=Language">Language</a>
        <a href="?search=Design">Design</a>
        <a href="?search=Science">Science</a>
        <a href="?search=Business">Business</a>
    </div>
</aside>
<main>
    <section class="searchBar">
        <div class="search-box">
            <form id="searchForm">
                <input type="text" id="search" name="search" class="input" placeholder="Search">
                <button type="submit" id="search-button"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
    </section>

    <section class="list-books" id="bookList">
    <?php if (count($books) > 0): ?>
        <?php foreach ($books as $book): ?>
        <div class="book-card">
            <a href="BookDetails.php?BookID=<?php echo $book['BookID']; ?>" style="cursor: pointer;">
                <img src="css/Image/Knowledge Nest.webp" alt="Book Image" class="book-image">
                <div class="book-details">
                    <h1 class="book-title" style="font-size: 1.2em; font-weight: 600; margin: 0 0 8px 0; color: #2c1810; transition: color 0.3s ease;"><?php echo $book['Title']; ?></h1>
                    <p class="book-author">Author: <?php echo $book['Author']; ?></p>
                    <p class="book-category">Category: <?php echo $book['Category']; ?></p>
                    <p class="book-edition">Edition: <?php echo $book['Edition']; ?></p>
                    <p class="book-quantity">Price: <?php echo $book['Price']; ?>$</p>
                    <p class="book-status">Status: <?php echo $book['Status']; ?></p>
                    
                    <?php if ($userType === 'admin'): ?>
                        <div class="book-actions">
                            <a href="DeleteBook.php?BookID=<?php echo $book['BookID']; ?>" class="action-link">
                                <i class="fas fa-trash-alt remove-icon" title="Remove"></i>
                            </a>
                            <a href="EditBook.php?BookID=<?php echo $book['BookID']; ?>" class="action-link">
                                <i class="fas fa-edit edit-icon" title="Edit"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No books found.</p>
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

<script src="js/script.js"></script>
</body>
</html>
