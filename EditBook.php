<?php
$passworddb = "";

// Check if BookID is provided in the URL
if (isset($_GET['BookID'])) {
    $BookID = $_GET['BookID'];

    // Connect to the database
    $connection = mysqli_connect("localhost", "root", "", "library");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve the book data based on BookID
    $query = "SELECT * FROM `book` WHERE `BookID` = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $BookID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Populate the form with the book data
        $Title = $row['Title'];
        $Author = $row['Author'];
        $Status = $row['Status'];
        $Edition = $row['Edition'];
        $Price = $row['Price'];
        $Quantity = $row['Quantity'];
        $Category = $row['Category'];
    } else {
        echo "Book not found.";
    }

    // Close the connection
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
} else {
    echo "BookID is required.";
}

// Handle form submission to update book data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Title = $_POST["Title"];
    $Author = $_POST["Author"];
    $Status = $_POST["Status"];
    $Edition = $_POST["Edition"];
    $Price = $_POST["Price"];
    $Quantity = $_POST["Quantity"];
    $Category = $_POST["Category"];

    // Connect to the database
    $connection = mysqli_connect("localhost", "root", "", "library");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Update book data
    $update_query = "UPDATE `book` SET `Title` = ?, `Author` = ?, `Status` = ?, `Edition` = ?, `Price` = ?, `Quantity` = ?, `Category` = ? WHERE `BookID` = ?";
    $stmt = mysqli_prepare($connection, $update_query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssdiis", $Title, $Author, $Status, $Edition, $Price, $Quantity, $Category, $BookID);
        $execute_result = mysqli_stmt_execute($stmt);

        if ($execute_result) {
            // Redirect to the books page with success message
            header("Location: books.php?status=success");
            exit(); // Ensure the script stops executing after redirection
        } else {
            echo "Error updating book: " . mysqli_error($connection);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($connection);
    }

    // Close the connection
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="css/editBook.css">
</head>
<body>

<div class="dashboard-container">
    <div class="dashboard-content">
        <h2>Edit Book</h2>
        <form action="EditBook.php?BookID=<?php echo htmlspecialchars($BookID); ?>" method="POST">
            <table>
                <tr>
                    <td>Title:</td>
                    <td><input type="text" name="Title" value="<?php echo htmlspecialchars($Title); ?>" required></td>
                </tr>
                <tr>
                    <td>Author:</td>
                    <td><input type="text" name="Author" value="<?php echo htmlspecialchars($Author); ?>" required></td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td>
                        <select name="Status" required>
                            <option value="Available" <?php echo ($Status == 'Available') ? 'selected' : ''; ?>>Available</option>
                            <option value="Unavailable" <?php echo ($Status == 'Unavailable') ? 'selected' : ''; ?>>Unavailable</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Edition:</td>
                    <td><input type="text" name="Edition" value="<?php echo htmlspecialchars($Edition); ?>" required></td>
                </tr>
                <tr>
                    <td>Price:</td>
                    <td><input type="number" name="Price" step="0.01" value="<?php echo htmlspecialchars($Price); ?>" required></td>
                </tr>
                <tr>
                    <td>Quantity:</td>
                    <td><input type="number" name="Quantity" value="<?php echo htmlspecialchars($Quantity); ?>" required></td>
                </tr>
                <tr>
                    <td>Category:</td>
                    <td><input type="text" name="Category" value="<?php echo htmlspecialchars($Category); ?>" required></td>
                </tr>
            </table>
            <button type="submit">Update Book</button>
        </form>
    </div>
</div>

</body>
</html>
