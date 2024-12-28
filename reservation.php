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

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT `ReversationID`, `UserID`, `BookID`, `ReturnDate` FROM `reversation`";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reservasion.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <title>Reversation Books</title>
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
                    <span class="text">Manage Reservations</span>
                </div>

                <?php if ($userType === 'admin') { ?>
                    <div class="add-button-container">
                        <a href="Addreversation.php" class="btnbtn-primary">Add New Reservation</a>
                    </div>

                    <div class="boxes">
                        <div class="box">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr>
                                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #2c1810;">Reservation ID</th>
                                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #2c1810;">User ID</th>
                                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #2c1810;">Book ID</th>
                                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #2c1810;">Return Date</th>
                                        <th style="padding: 12px; text-align: left; border-bottom: 2px solid #2c1810;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result && $result->num_rows > 0): ?>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td style="padding: 12px; border-bottom: 1px solid #ddd;"><?= htmlspecialchars($row['ReversationID']); ?></td>
                                                <td style="padding: 12px; border-bottom: 1px solid #ddd;"><?= htmlspecialchars($row['UserID']); ?></td>
                                                <td style="padding: 12px; border-bottom: 1px solid #ddd;"><?= htmlspecialchars($row['BookID']); ?></td>
                                                <td style="padding: 12px; border-bottom: 1px solid #ddd;"><?= htmlspecialchars($row['ReturnDate']); ?></td>
                                                <td style="padding: 12px; border-bottom: 1px solid #ddd;">
                                                    <a href="EditReversation.php?ReversationID=<?= urlencode($row['ReversationID']); ?>" style="color: #2c1810; text-decoration: none; margin-right: 10px;">Edit</a>
                                                    <a href="RemoveReversation.php?ReversationID=<?= urlencode($row['ReversationID']); ?>" 
                                                       onclick="return confirm('Are you sure you want to delete this Reservation?')"
                                                       style="color: #2c1810; text-decoration: none;">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" style="text-align: center; padding: 12px;">No data found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <script src="js/dashboard.js"></script>
</body>
</html>
