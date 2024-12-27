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
