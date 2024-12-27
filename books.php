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
                <img src="css/Image/Book_1.jpg" alt="Book Image" class="book-image">
                <div class="book-details">
                    <h3 class="book-title"><?php echo $book['Title']; ?></h3>
                    <p class="book-author">Author: <?php echo $book['Author']; ?></p>
                    <p class="book-category">Category: <?php echo $book['Category']; ?></p>
                    <p class="book-price">Price: $<?php echo $book['Price']; ?></p>
                    
                    <!-- Book actions (Details, Edit, Remove) -->
                    <div class="book-actions">
                        <a href="BookDetails.php?BookID=<?php echo $book['BookID']; ?>" class="action-link">
                            <i class="fas fa-info-circle detail-icon" title="Details"></i>
                        </a>
                        <?php if ($userType === 'admin'): ?>
                            <a href="DeleteBook.php?BookID=<?php echo $book['BookID']; ?>" class="action-link">
                                <i class="fas fa-trash-alt remove-icon" title="Remove"></i>
                            </a>
                            <a href="EditBook.php?BookID=<?php echo $book['BookID']; ?>" class="action-link">
                                <i class="fas fa-edit edit-icon" title="Edit"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No books found.</p>
    <?php endif; ?>
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
                Creative Commons Attribution 4.0 International License.
                &copy;2024 Presidents and Fellows of Harvard College.
            </p>
        </div>
    </div>
</footer>

<script src="js/script.js"></script>
</body>
</html>
