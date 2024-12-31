<?php
session_start();

// Check if the user is logged in and is an admin
$is_logged_in = isset($_SESSION['username']);
$userType = $is_logged_in ? $_SESSION['userType'] : null;
$userName = $is_logged_in ? $_SESSION['username'] : null;

// Only allow admin access
if (!$is_logged_in || $userType !== 'admin') {
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

$connection = mysqli_connect($servername, $username, $password, $dbname);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $UserName = mysqli_real_escape_string($connection, $_POST["UserName"]);
    $Password = $_POST["Password"];
    $Email = mysqli_real_escape_string($connection, $_POST["Email"]);
    $Type = mysqli_real_escape_string($connection, $_POST["UserType"]); 
    
  
    if (empty($UserName) || empty($Password) || empty($Email) || empty($Type)) {
        echo "<script>alert('All fields are required!');</script>";
    } else {
       
        $check_query = "SELECT * FROM user WHERE UserName = '$UserName'";
        $result = mysqli_query($connection, $check_query);
        
        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('Username already exists!');</script>";
        } else {
          
            $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);
            
            
            $query = "INSERT INTO `user`(`UserName`, `Password`, `Email`, `Type`) 
                      VALUES ('$UserName', '$hashedPassword', '$Email', '$Type')";

            if (mysqli_query($connection, $query)) {
                echo "<script>
                        alert('User added successfully!');
                        window.location.href = 'dashboard.php';
                      </script>";
            } else {
                echo "<script>alert('Error: " . mysqli_error($connection) . "');</script>";
            }
        }
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/addUserStyle.css">
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

                    <?php if ($userType === 'admin') { ?>
                        <li><a href="borrowBook.php">Borrow</a></li>
                        <li><a href="reservation.php">Reservation</a></li>
                    <?php } ?>
                    
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
        <h1>Add New User</h1>
        <form method="POST">
            <div class="form-group">
                <label for="UserName">Username:</label>
                <input type="text" id="UserName" name="UserName" required>
            </div>
            <div class="form-group">
                <label for="Email">Email:</label>
                <input type="email" id="Email" name="Email" required>
            </div>
            <div class="form-group">
                <label for="Password">Password:</label>
                <input type="password" id="Password" name="Password" required>
            </div>
        
            <div class="form-group">
                <label for="UserType">User Type:</label>
                <select id="UserType" name="UserType" required>
                    <option value="">Select User Type</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="submit-btn">Add User</button>
        </form>
    </div>
        </main>

    
</body>
</html>
