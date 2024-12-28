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

$sql = "SELECT `BorrowID`, `UserID`, `BookID`, `IssueDate`, `StartDate`, `ReturnDate` FROM `borrow`";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/borrowbook.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <title>Borrow Books</title>

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
                    <li><a href="Addreversation.php">Reversition Books</a></li>
                    <li><a href="AddBorrow.php">Borrowed Books</a></li>
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
                        <!-- <div class="profile_name"><?php echo $_SESSION['username']; ?></div> -->
                        <div class="job">Administrator</div>
                    </div>
                    <form method="GET" action="index.php" style="display: inline;">
                        <button type="submit" name="logout" style="background: none; border: none; color: white; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                            <i class='bx bx-log-out'></i>
                        </button>
                </div>
            </li>
        </ul>
    </div>
    <section class="home-section">
        <div class="home-content">
            <i class='bx bx-menu'></i>
            <span class="text">Dashboard</span>
        </div>

    

        <div class="dash-content">
            <div class="overview">
                <div class="title">
                    <i class='bx bx-user'></i>
                    <span class="text">Borrowed Books</span>
                </div>
            
            <div class="add-button-container">
                <a href="AddUser.php" class="btnbtn-primary">Add New User</a>
            </div>




    <?php if ($userType === 'admin') { ?>
    <div class="container">
        <h1>Borrowed Books</h1>
        <a href="AddBorrow.php">Add New Borrow</a>
        <table>
            <thead>
                <tr>
                    <th>Borrow ID</th>
                    <th>User ID</th>
                    <th>Book ID</th>
                    <th>Issue Date</th>
                    <th>Start Date</th>
                    <th>Return Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['BorrowID']); ?></td>
                            <td><?= htmlspecialchars($row['UserID']); ?></td>
                            <td><?= htmlspecialchars($row['BookID']); ?></td>
                            <td><?= htmlspecialchars($row['IssueDate']); ?></td>
                            <td><?= htmlspecialchars($row['StartDate']); ?></td>
                            <td><?= htmlspecialchars($row['ReturnDate']); ?></td>
                            <td>
                                <a href="EditBorrow.php?BorrowID=<?= urlencode($row['BorrowID']); ?>">Edit</a> |
                                <a href="RemoveBorrow.php?BorrowID=<?= urlencode($row['BorrowID']); ?>" 
                                   onclick="return confirm('Are you sure you want to delete this Borrow?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No data found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
    <script src="js/dashboard.js"></script>
</body>
</html>
