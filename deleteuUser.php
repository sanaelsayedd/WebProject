<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['username']) || $_SESSION['userType'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['UserID'])) {
    $UserID = $_GET['UserID'];

    if (empty($UserID)) {
        die("UserID is required.");
    }

    $connection = mysqli_connect("localhost", "root", "", "library");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Start transaction
    mysqli_begin_transaction($connection);

    try {
        // Delete from reservation table first
        $query = "DELETE FROM `reversation` WHERE `UserID` = ?";
        $stmt = mysqli_prepare($connection, $query);
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . mysqli_error($connection));
        }
        mysqli_stmt_bind_param($stmt, "i", $UserID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Delete from purchase_transaction table
        $query = "DELETE FROM `purchase_transaction` WHERE `UserID` = ?";
        $stmt = mysqli_prepare($connection, $query);
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . mysqli_error($connection));
        }
        mysqli_stmt_bind_param($stmt, "i", $UserID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Delete from borrow table
        $query = "DELETE FROM `borrow` WHERE `UserID` = ?";
        $stmt = mysqli_prepare($connection, $query);
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . mysqli_error($connection));
        }
        mysqli_stmt_bind_param($stmt, "i", $UserID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Finally delete from user table
        $query = "DELETE FROM `user` WHERE `UserID` = ?";
        $stmt = mysqli_prepare($connection, $query);
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . mysqli_error($connection));
        }
        mysqli_stmt_bind_param($stmt, "i", $UserID);
        
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_commit($connection);
            header("Location: manageUser.php");
            exit();
        } else {
            mysqli_stmt_close($stmt);
            throw new Exception("Error deleting user: " . mysqli_error($connection));
        }

    } catch (Exception $e) {
        mysqli_rollback($connection);
        echo "An error occurred: " . $e->getMessage();
    } finally {
        mysqli_close($connection);
    }
} else {
    echo "UserID is missing.";
}
?>