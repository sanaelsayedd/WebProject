<?php
$passworddb = "WEBDBwebdb123456789";


if (isset($_GET['UserID'])) {
    $UserID = $_GET['UserID'];

    if (empty($UserID)) {
        die("UserID is required.");
    }


    $connection = mysqli_connect("localhost", "root", "WEBDBwebdb123456789", "library");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "DELETE FROM `user` WHERE `UserID` = ?";
    $stmt = mysqli_prepare($connection, $query);

    if ($stmt) {

        mysqli_stmt_bind_param($stmt, "i", $UserID);

        if (mysqli_stmt_execute($stmt)) {
            echo "User with ID $UserID has been deleted successfully.";

            header("Location: manageUser.php");
            exit();
        } else {
            echo "Error deleting User: " . mysqli_stmt_error($stmt);
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($connection);
    }

    mysqli_close($connection);
} else {
    echo "UserID is missing.";
}
?>