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
$passworddb = "";
$BorrowID = $UserID = $BookID = $IssueDate = $StartDate = $ReturnDate = ""; // Initialize variables

// Check if `BorrowID` is provided in the URL
if (isset($_GET['BorrowID'])) {
    $BorrowID = $_GET['BorrowID'];

    // Connect to the database
    $connection = mysqli_connect("localhost", "root", $passworddb, "library");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve the borrow data
    $query = "SELECT * FROM `borrow` WHERE `BorrowID` = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $BorrowID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $UserID = $row["UserID"];
        $BookID = $row["BookID"];
        $IssueDate = $row["IssueDate"];
        $StartDate = $row["StartDate"];
        $ReturnDate = $row["ReturnDate"];
    } else {
        echo "Borrow record not found.";
        exit();
    }

    mysqli_stmt_close($stmt);


    $users_query = "SELECT `UserID`, `UserName` FROM `user`";
    $users_result = mysqli_query($connection, $users_query);


    $books_query = "SELECT `BookID`, `Title` FROM `book`";
    $books_result = mysqli_query($connection, $books_query);

    mysqli_close($connection);
} else {
    die("BorrowID is required.");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $UserID = $_POST["UserID"];
    $BookID = $_POST["BookID"];
    $IssueDate = $_POST["IssueDate"];
    $StartDate = $_POST["StartDate"];
    $ReturnDate = $_POST["ReturnDate"];

    $connection = mysqli_connect("localhost", "root", $passworddb, "library");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Update borrow data
    $update_query = "UPDATE `borrow` SET `UserID` = ?, `BookID` = ?, `IssueDate` = ?, `StartDate` = ?, `ReturnDate` = ? WHERE `BorrowID` = ?";
    $stmt = mysqli_prepare($connection, $update_query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iisssi", $UserID, $BookID, $IssueDate, $StartDate, $ReturnDate, $BorrowID);

        if (mysqli_stmt_execute($stmt)) {
            // Redirect to the borrow page with a success message
            header("Location: borrowBook.php?status=success");
            exit();
        } else {
            echo "Error updating Borrow: " . mysqli_stmt_error($stmt);
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
    <title>Edit Borrow</title>
    <link rel="stylesheet" href="css/editBorrowStyle.css">
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
<div class="dashboard-container">
<a href="javascript:history.back()" class="back-button" >
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
    <div class="dashboard-content">
        <h2>Edit Borrow</h2>
        <form action="EditBorrow.php?BorrowID=<?php echo htmlspecialchars($BorrowID); ?>" method="POST">
            <table>
                <tr>
                    <td>UserID:</td>
                    <td>
                        <select name="UserID" required>
                            <?php
                            if (isset($users_result) && mysqli_num_rows($users_result) > 0) {
                                while ($user = mysqli_fetch_assoc($users_result)) {
                                    $selected = ($user['UserID'] == $UserID) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($user['UserID']) . "' $selected>" . htmlspecialchars($user['UserName']) . "</option>";
                                }
                            } else {
                                echo "<option value=''>No users available</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>BookID:</td>
                    <td>
                        <select name="BookID" required>
                            <?php
                            if (isset($books_result) && mysqli_num_rows($books_result) > 0) {
                                while ($book = mysqli_fetch_assoc($books_result)) {
                                    $selected = ($book['BookID'] == $BookID) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($book['BookID']) . "' $selected>" . htmlspecialchars($book['Title']) . "</option>";
                                }
                            } else {
                                echo "<option value=''>No books available</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>IssueDate:</td>
                    <td><input type="date" name="IssueDate" value="<?php echo htmlspecialchars($IssueDate); ?>" required></td>
                </tr>
                <tr>
                    <td>StartDate:</td>
                    <td><input type="date" name="StartDate" value="<?php echo htmlspecialchars($StartDate); ?>" required></td>
                </tr>
                <tr>
                    <td>ReturnDate:</td>
                    <td><input type="date" name="ReturnDate" value="<?php echo htmlspecialchars($ReturnDate); ?>" required></td>
                </tr>
            </table>
            <button type="submit">Update Borrow</button>
        </form>
    </div>
</div>

</body>
</html>
