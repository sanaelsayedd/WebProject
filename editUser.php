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
if (isset($_GET['UserID'])) {
    $UserID = intval($_GET['UserID']); 

    $connection = mysqli_connect("localhost", "root", "", "library");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM `user` WHERE `UserID` = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $UserID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $UserName = $row['UserName'];
        $Password = $row['Password']; 
        $Email = $row['Email'];
        $Type = $row['Type'];
    } else {
        echo "User not found.";
        exit();
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);
} else {
    echo "UserID is required.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $UserName = $_POST["UserName"];
    $Email = $_POST["Email"];
    $Type = $_POST["Type"];

    $connection = mysqli_connect("localhost", "root", "", "library");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Only update password if a new one is provided
    if (!empty($_POST["Password"])) {
        $hashedPassword = password_hash($_POST["Password"], PASSWORD_DEFAULT);
        $update_query = "UPDATE `user` SET `UserName` = ?, `Password` = ?, `Email` = ?, `Type` = ? WHERE `UserID` = ?";
        $stmt = mysqli_prepare($connection, $update_query);
        mysqli_stmt_bind_param($stmt, "ssssi", $UserName, $hashedPassword, $Email, $Type, $UserID);
    } else {
        // If no new password, don't update the password field
        $update_query = "UPDATE `user` SET `UserName` = ?, `Email` = ?, `Type` = ? WHERE `UserID` = ?";
        $stmt = mysqli_prepare($connection, $update_query);
        mysqli_stmt_bind_param($stmt, "sssi", $UserName, $Email, $Type, $UserID);
    }

    if ($stmt) {
        $execute_result = mysqli_stmt_execute($stmt);

        if ($execute_result) {
            header("Location: manageUser.php?status=success");
            exit();
        } else {
            echo "Error updating user: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($connection);
    }

    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/editUser.css">
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

<div class="dashboard-container">
<a href="javascript:history.back()" class="back-button" >
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
    <div class="dashboard-content">
        <h2>Edit User</h2>
        <form action="EditUser.php?UserID=<?php echo htmlspecialchars($UserID); ?>" method="POST">
            <table>
                <tr>
                    <td>Username:</td>
                    <td><input type="text" name="UserName" value="<?php echo htmlspecialchars($UserName); ?>" required></td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td><input type="password" name="Password" placeholder="Enter new password to change"></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input type="email" name="Email" value="<?php echo htmlspecialchars($Email); ?>" required></td>
                </tr>
                <tr>
                    <td>Type:</td>
                    <td>
                        <select name="Type" required>
                            <option value="admin" <?php echo ($Type == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="user" <?php echo ($Type == 'user') ? 'selected' : ''; ?>>User</option>
                        </select>
                    </td>
                </tr>
            </table>
            <button type="submit">Update User</button>
        </form>
    </div>
</div>
</main>
</body>
</html>
