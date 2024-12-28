<?php
$passworddb = "WEBDBwebdb123456789";


if (isset($_GET['PurchaseTransactionID'])) {
    $PurchaseTransactionID = $_GET['PurchaseTransactionID'];

    if (empty($PurchaseTransactionID)) {
        die("PurchaseTransactionID is required.");
    }


    $connection = mysqli_connect("localhost", "root", "WEBDBwebdb123456789", "library");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "DELETE FROM `purchase_transaction` WHERE `PurchaseTransactionID` = ?";
    $stmt = mysqli_prepare($connection, $query);

    if ($stmt) {

        mysqli_stmt_bind_param($stmt, "i", $PurchaseTransactionID);

        if (mysqli_stmt_execute($stmt)) {
            echo "Book with ID $PurchaseTransactionID has been deleted successfully.";

            header("Location: Purchase.php");
            exit();
        } else {
            echo "Error deleting book: " . mysqli_stmt_error($stmt);
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($connection);
    }

    mysqli_close($connection);
} else {
    echo "PurchaseTransactionID is missing.";
}
?>