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

// Database credentials
$host = "localhost"; 
$username = "root"; 
$password = "WEBDBwebdb123456789"; 
$dbname = "library"; 

// Connect to the database
$connection = mysqli_connect($host, $username, $password, $dbname);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all users from the database
$query = "SELECT * FROM user";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/manageUserStyle.css">
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
                    <span class="text">Manage Users</span>
                </div>
            
            <div class="add-button-container">
                <a href="AddUser.php" class="btnbtn-primary">Add New User</a>
            </div>

            <table class="user-table">
                <thead>
                    <tr>
                        <th>UserID</th>
                        <th>UserName</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display users
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['UserID'] . "</td>";
                        echo "<td>" . $row['UserName'] . "</td>";
                        echo "<td>" . $row['Email'] . "</td>";
                        echo "<td>" . $row['Type'] . "</td>";
                        echo "<td>
                                <a href='editUser.php?UserID=" . $row['UserID'] . "' class='btn btn-edit'>Edit</a>
                                <a href='deleteuUser.php?UserID=" . $row['UserID'] . "' class='btn btn-delete'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    </section>

    <script src="js/dashboard.js"></script>
</body>
</html>

<?php
// Close the database connection
mysqli_close($connection);
?>
