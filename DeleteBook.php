<?php
$passworddb = "WEBDBwebdb123456789";

// Check if BookID is passed in the URL
if (isset($_GET['BookID'])) {
    $BookID = $_GET['BookID'];

    // Check if BookID is valid
    if (empty($BookID)) {
        die("BookID is required.");
    }

    // Connect to the database
    $connection = mysqli_connect("localhost", "root", $passworddb, "library");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare the DELETE query
    $query = "DELETE FROM `book` WHERE `BookID` = ?";
    $stmt = mysqli_prepare($connection, $query);

    if ($stmt) {
        // Bind the BookID to the prepared statement
        mysqli_stmt_bind_param($stmt, "i", $BookID);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            echo "Book with ID $BookID has been deleted successfully.";
            // Redirect back to the books page after deletion
            header("Location: books.php");
            exit();
        } else {
            echo "Error deleting book: " . mysqli_error($connection);
        }
        
        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($connection);
    }

    // Close the connection
    mysqli_close($connection);
} else {
    echo "BookID is missing.";
}
?>
