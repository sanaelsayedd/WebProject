<?php
$passworddb = "";
$UserID = $BookID = $ReturnDate = ""; // Initialize variables

// Check if `ReversationID` is provided in the URL
if (isset($_GET['ReversationID'])) {
    $ReversationID = $_GET['ReversationID'];

    // Connect to the database
    $connection = mysqli_connect("localhost", "root", $passworddb, "library");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve the reversation data
    $query = "SELECT * FROM `reversation` WHERE `ReversationID` = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $ReversationID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $UserID = $row['UserID'];
        $BookID = $row['BookID'];
        $ReturnDate = $row['ReturnDate'];
    } else {
        echo "Reversation not found.";
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
    die("ReversationID is required.");
}

// Handle form submission to update reversation data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $UserID = $_POST["UserID"];
    $BookID = $_POST["BookID"];
    $ReturnDate = $_POST["ReturnDate"];

    // Connect to the database
    $connection = mysqli_connect("localhost", "root", $passworddb, "library");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Update reversation data
    $update_query = "UPDATE `reversation` SET `UserID` = ?, `BookID` = ?, `ReturnDate` = ? WHERE `ReversationID` = ?";
    $stmt = mysqli_prepare($connection, $update_query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssi", $UserID, $BookID, $ReturnDate, $ReversationID);

        if (mysqli_stmt_execute($stmt)) {
            // Redirect to the reservation page with a success message
            header("Location: reservation.php?status=success");
            exit();
        } else {
            echo "Error updating reversation: " . mysqli_stmt_error($stmt);
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
    <title>Edit ReversationID</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;1,500&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(to right, #FF6952, #3CAEC3); /* Gradient background */
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
            opacity: 0.9; /* Slight transparency for background blending */
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

        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        input[type="text"]:focus, input[type="number"]:focus, select:focus {
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

        a {
            color: #3CAEC3;
        }

        a:hover {
            color: #FF6952;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                width: 100%;
                height: auto;
            }

            .dashboard-container {
                margin-left: 0;
                padding: 20px;
            }

            .dashboard-content {
                padding: 20px;
            }

            form {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div class="dashboard-content">
        <h2>Edit Reversation</h2>
        <form action="EditReversation.php?ReversationID=<?php echo htmlspecialchars($ReversationID); ?>" method="POST">
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
            <td>ReturnDate:</td>
            <td><input type="date" name="ReturnDate" value="<?php echo htmlspecialchars($ReturnDate); ?>" required></td>
        </tr>
    </table>
    <button type="submit">Update Reversation</button>
</form>

    </div>
</div>

</body>
</html>
