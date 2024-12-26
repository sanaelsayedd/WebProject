<?php
$passworddb = "WEBDBwebdb123456789";

if (isset($_GET['ReversationID'])) {
    $ReversationID = $_GET['ReversationID'];

    if (empty($ReversationID)) {
        die("Error: ReversationID is required.");
    }

    $connection = mysqli_connect("localhost", "root", $passworddb, "library");

    if (!$connection) {
        die("Error: Connection failed - " . mysqli_connect_error());
    }

    $query = "DELETE FROM `reversation` WHERE `ReversationID` = ?";
    $stmt = mysqli_prepare($connection, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $ReversationID);

        if (mysqli_stmt_execute($stmt)) {

            header("Location: reservation.php?status=success&message=Reversation%20deleted%20successfully");
            exit();
        } else {

            echo "Error: Could not delete Reversation - " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error: Could not prepare statement - " . mysqli_error($connection);
    }

    mysqli_close($connection);
} else {
    echo "Error: ReversationID is missing in the request.";
}
?>
