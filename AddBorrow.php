<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library";

$connection = mysqli_connect($servername, $username, $password , $dbname);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch users and books for dropdowns
$userQuery = "SELECT UserID, UserName FROM user";
$userResult = mysqli_query($connection, $userQuery);

$bookQuery = "SELECT BookID, Title FROM book";
$bookResult = mysqli_query($connection, $bookQuery);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $UserID = $_POST["UserID"];
    $BookID = $_POST["BookID"];
    $IssueDate = $_POST["IssueDate"];
    $StartDate = $_POST["StartDate"];
    $ReturnDate = $_POST["ReturnDate"];

    // Insert record into borrow table
    $query = "INSERT INTO `borrow`(`UserID`, `BookID`, `IssueDate`, `StartDate`, `ReturnDate`) 
              VALUES ('$UserID', '$BookID', '$IssueDate', '$StartDate', '$ReturnDate')";

    if (mysqli_query($connection, $query)) {
        echo "<script>
                alert('Record inserted successfully!');
                window.location.href = 'borrowBook.php';
              </script>";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($connection);
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Book Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/addborrow.css">
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
                    </form>
                </div>
            </li>
        </ul>
    </div>



    <div class="form-container">
        <h1>Borrow a Book</h1>
        <form method="POST">
            <div class="form-group">
                <label for="UserID">User ID:</label>
                <select id="UserID" name="UserID" required>
                    <option value="">Select User</option>
                    <?php while ($user = mysqli_fetch_assoc($userResult)): ?>
                        <option value="<?php echo $user['UserID']; ?>">
                            <?php echo $user['UserName']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="BookID">Book ID:</label>
                <select id="BookID" name="BookID" required>
                    <option value="">Select Book</option>
                    <?php while ($book = mysqli_fetch_assoc($bookResult)): ?>
                        <option value="<?php echo $book['BookID']; ?>">
                            <?php echo $book['Title']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="IssueDate">Issue Date:</label>
                <input type="date" id="IssueDate" name="IssueDate" required>
            </div>
            <div class="form-group">
                <label for="StartDate">Start Date:</label>
                <input type="date" id="StartDate" name="StartDate" required>
            </div>
            <div class="form-group">
                <label for="ReturnDate">Return Date:</label>
                <input type="date" id="ReturnDate" name="ReturnDate" required>
            </div>
            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>
</body>
</html>
