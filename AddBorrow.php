<?php
session_start();

// Check if the user is logged in
$is_logged_in = isset($_SESSION['username']);
$userType = $is_logged_in ? $_SESSION['userType'] : null; 
$userName = $is_logged_in ? $_SESSION['username'] : null;

if (!$is_logged_in) {
    header("Location: login.php");
    exit();
}

// Handle logout
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/addborrow.css">
</head>
<body>
<header class="header">
            <div class="logo">
                <a href="index.php"><i class="fa-solid fa-book"></i> Knowledge Nest</a>
            </div>
            <nav class="nav-bar">
                <ul class="nav__links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#CSection">Contact</a></li>
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
        <main>
        
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
    <a href="javascript:history.back()" class="back-button" >
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
        </main>

    
</body>
</html>
