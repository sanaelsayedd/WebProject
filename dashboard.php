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

// Total Books
$total_books_query = "SELECT SUM(Quantity) as total FROM book";
$result = $conn->query($total_books_query);
$total_books = $result->fetch_assoc()['total'] ?? 0;

// Borrowed Books
$borrowed_books_query = "SELECT COUNT(*) as total FROM borrow";
$result = $conn->query($borrowed_books_query);
$borrowed_books = $result->fetch_assoc()['total'] ?? 0;

// Reservations
$reservations_query = "SELECT COUNT(*) as total FROM reversation";
$result = $conn->query($reservations_query);
$reservations = $result->fetch_assoc()['total'] ?? 0;

// Total Fines (from purchase transactions)
$total_fines_query = "SELECT SUM(TotalPrice) as total_fines 
FROM purchase_transaction";
$result = $conn->query($total_fines_query);
$total_fines = $result->fetch_assoc()['total_fines'] ?? 0;


// Total Users
$total_users_query = "SELECT COUNT(*) as total FROM user";
$result = $conn->query($total_users_query);
$total_users = $result->fetch_assoc()['total'] ?? 0;



// Available Books (Total quantity minus borrowed)
$available_books_query = "SELECT 
    (SELECT SUM(Quantity) FROM book) - 
    (SELECT COUNT(*) FROM borrow WHERE ReturnDate IS NULL) as available";
$result = $conn->query($available_books_query);
$available_books = $result->fetch_assoc()['available'] ?? 0;

// Active Users (Users who have borrowed or reserved books in the last 30 days)
$active_users_query = "SELECT COUNT(DISTINCT UserID) as total FROM (
    SELECT UserID FROM borrow WHERE IssueDate >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
    UNION
    SELECT UserID FROM reversation WHERE ReturnDate >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
) as active_users";

$result = $conn->query($active_users_query);
$active_users = $result->fetch_assoc()['total'] ?? 0;

// Get recent activity data
$recent_activity_query = "SELECT b.BorrowID, u.Username, bk.Title, b.IssueDate 
    FROM borrow b
    JOIN user u ON b.UserID = u.UserID
    JOIN book bk ON b.BookID = bk.BookID
    ORDER BY b.IssueDate DESC LIMIT 5";
$recent_activity = $conn->query($recent_activity_query);

// Get popular books data
$popular_books_query = "SELECT b.BookID, bk.Title, COUNT(*) as borrow_count
    FROM borrow b
    JOIN book bk ON b.BookID = bk.BookID
    GROUP BY b.BookID
    ORDER BY borrow_count DESC LIMIT 5";
$popular_books = $conn->query($popular_books_query);

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/dashboard-styles.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="sidebar close">
        <div class="logo-details">
            <i class='bx bx-book-open'></i>
            <span class="logo_name">KnowledgeNest</span>
        </div>
        <ul class="nav-links">
            <li>
                <a href="index.php">
                    <i class='bx bx-home'></i>
                    <span class="link_name">Home</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="index.php">Home</a></li>
                </ul>
            </li>
            <li>
                <a href="dashboard.php">
                    <i class='bx bx-grid-alt'></i>
                    <span class="link_name">Dashboard</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="dashboard.php">Dashboard</a></li>
                </ul>
            </li>
            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class='bx bx-book'></i>
                        <span class="link_name">Books</span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name" href="books.php">Books</a></li>
                    <li><a href="books.php">Books</a></li>
                    <li><a href="reservation.php">Reversition Books</a></li>
                    <li><a href="borrowBook.php">Borrowed Books</a></li>
                    <li><a href="Purchase.php">Purchaes</a></li>

                </ul>
            </li>
            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class='bx bx-user'></i>
                        <span class="link_name">Users</span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name" href="#">Users</a></li>
                    <li><a href="manageUser.php">Manage Users</a></li>
                </ul>
            </li>
            <li>
                <a href="settings.php">
                    <i class='bx bx-cog'></i>
                    <span class="link_name">Settings</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="settings.php">Settings</a></li>
                </ul>
            </li>
            <li>
                <div class="profile-details">
                    <div class="profile-content">
                        <i class='bx bx-user-circle'></i>
                    </div>
                    <div class="name-job">
                        <div class="profile_name"><?php echo $_SESSION['username']; ?></div>
                        <div class="job">Administrator</div>
                    </div>
                    <form method="GET" action="index.php" style="display: inline;">
                        <button type="submit" name="logout" style="background: none; border: none; color: white; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                            <i class='bx bx-log-out'></i>
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
<main>

    <section class="home-section">
        <div class="home-content">
            <i class='bx bx-menu'></i>
            <span class="text">Dashboard</span>
        </div>

        <div class="dash-content">
            <div class="overview">
                <div class="title">
                    <i class='bx bx-tachometer'></i>
                    <span class="text">Dashboard Overview</span>
                </div>

                <div class="boxes">
                    <!-- Total Books -->
                    <div class="box box1">
                        <i class='bx bx-book-alt'></i>
                        <span class="text">Total Books</span>
                        <span class="number"><?php echo $total_books ?? '0'; ?></span>
                    </div>

                    <!-- Borrowed Books -->
                    <div class="box box2">
                        <i class='bx bx-book-reader'></i>
                        <span class="text">Borrowed Books</span>
                        <span class="number"><?php echo $borrowed_books ?? '0'; ?></span>
                    </div>

                    <!-- Reservations -->
                    <div class="box box3">
                        <i class='bx bx-bookmark'></i>
                        <span class="text">Reservations</span>
                        <span class="number"><?php echo $reservations ?? '0'; ?></span>
                    </div>

                    <!-- Total Fines -->
                    <div class="box box4">
                        <i class='bx bx-money'></i>
                        <span class="text">Total Fines</span>
                        <span class="number">$<?php echo $total_fines ?? '0'; ?></span>
                    </div>

                    <!-- New cards -->
                    <div class="box box5">
                        <i class='bx bx-user'></i>
                        <span class="text">Total Users</span>
                        <span class="number"><?php echo $total_users ?? '0'; ?></span>
                    </div>

                    <div class="box box7">
                        <i class='bx bx-check-circle'></i>
                        <span class="text">Available Books</span>
                        <span class="number"><?php echo $available_books ?? '0'; ?></span>
                    </div>

                    <div class="box box8">
                        <i class='bx bx-user-check'></i>
                        <span class="text">Active Users</span>
                        <span class="number"><?php echo $active_users ?? '0'; ?></span>
                    </div>
        </div>

                <!-- Recent Activity Section -->
                <div class="activity-section">
                    <div class="title">
                        <i class='bx bx-time'></i>
                        <span class="text">Recent Activity</span>
                    </div>
                    <table class="activity-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Book</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($activity = $recent_activity->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($activity['Username']); ?></td>
                                <td><?php echo htmlspecialchars($activity['Title']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($activity['IssueDate'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Popular Books Section -->
                <div class="popular-books-section">
                    <div class="title">
                        <i class='bx bx-trending-up'></i>
                        <span class="text">Popular Books</span>
                    </div>
                    <table class="popular-books-table">
                        <thead>
                            <tr>
                                <th>Book Title</th>
                                <th>Times Borrowed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($book = $popular_books->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($book['Title']); ?></td>
                                <td><?php echo $book['borrow_count']; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </section>
    </main>
    <script src="js/dashboard.js"></script>
</body>
</html>