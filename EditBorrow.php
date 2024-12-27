<?php
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

    // Fetch all users for the UserID dropdown
    $users_query = "SELECT `UserID`, `UserName` FROM `user`";
    $users_result = mysqli_query($connection, $users_query);

    // Fetch all books for the BookID dropdown
    $books_query = "SELECT `BookID`, `Title` FROM `book`";
    $books_result = mysqli_query($connection, $books_query);

    mysqli_close($connection);
} else {
    die("BorrowID is required.");
}

// Handle form submission to update borrow data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $UserID = $_POST["UserID"];
    $BookID = $_POST["BookID"];
    $IssueDate = $_POST["IssueDate"];
    $StartDate = $_POST["StartDate"];
    $ReturnDate = $_POST["ReturnDate"];

    // Connect to the database
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
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;1,500&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(to right, #FF6952, #3CAEC3);
            color: #333333;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-size: cover;
            background-position: center;
        }

        .dashboard-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .dashboard-content {
            width: 100%;
            max-width: 700px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            opacity: 0.9;
        }

        .dashboard-content h2 {
            font-size: 28px;
            margin-bottom: 30px;
            color: #583A83;
            text-align: center;
            font-weight: 600;
        }

        form {
            width: 100%;
        }

        table {
            width: 100%;
            margin-bottom: 20px;
        }

        table td {
            padding: 12px;
            font-size: 16px;
        }

        input[type="date"], select {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        input:focus, select:focus {
            outline: none;
            border-color: #ff6952;
            box-shadow: 0 0 10px rgba(255, 105, 82, 0.5);
        }

        button {
            width: 100%;
            padding: 15px;
            font-size: 18px;
            background-color: #ff6952;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #e95d46;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
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
