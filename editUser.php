<?php

if (isset($_GET['UserID'])) {
    $UserID = intval($_GET['UserID']); 

    $connection = mysqli_connect("localhost", "root", "WEBDBwebdb123456789", "library");

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
    $Password = $_POST["Password"];
    $Email = $_POST["Email"];
    $Type = $_POST["Type"];

    $connection = mysqli_connect("localhost", "root", "WEBDBwebdb123456789", "library");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $update_query = "UPDATE `user` SET `UserName` = ?, `Password` = ?, `Email` = ?, `Type` = ? WHERE `UserID` = ?";
    $stmt = mysqli_prepare($connection, $update_query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssi", $UserName, $Password, $Email, $Type, $UserID);
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
    <link rel="stylesheet" href="css/editUser.css">
</head>
<body>

<div class="dashboard-container">
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
                    <td><input type="password" name="Password" value="<?php echo htmlspecialchars($Password); ?>" required></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input type="email" name="Email" value="<?php echo htmlspecialchars($Email); ?>" required></td>
                </tr>
                <tr>
                    <td>Type:</td>
                    <td>
                        <select name="Type" required>
                            <option value="Admin" <?php echo ($Type == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="User" <?php echo ($Type == 'User') ? 'selected' : ''; ?>>User</option>
                        </select>
                    </td>
                </tr>
            </table>
            <button type="submit">Update User</button>
        </form>
    </div>
</div>

</body>
</html>
