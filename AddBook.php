<?php

$passworddb = "WEBDBwebdb123456789";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Title = $_POST["Title"];
    $Author = $_POST["Author"];
    $Status = $_POST["Status"];
    $Edition = $_POST["Edition"];
    $Price = $_POST["Price"];
    $Quantity = $_POST["Quantity"];
    $Category = $_POST["Category"];

    $connection = mysqli_connect("localhost", "root", $passworddb, "library");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $checkQuery = "SELECT * FROM `book` WHERE `Title` = ? AND `Author` = ?";
    $checkStmt = mysqli_prepare($connection, $checkQuery);

    if ($checkStmt) {

        mysqli_stmt_bind_param($checkStmt, "ss", $Title, $Author);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            echo "This book already exists in the database.";
        } else {
            $query = "INSERT INTO `book` (`Title`, `Author`, `Status`, `Edition`, `Price`, `Quantity`, `Category`) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($connection, $query);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ssssdis", $Title, $Author, $Status, $Edition, $Price, $Quantity, $Category);

                if (mysqli_stmt_execute($stmt)) {
                    header("Location: books.php"); 
                    exit(); 
                } else {
                    echo "Error: " . mysqli_error($connection);
                }

                mysqli_stmt_close($stmt);
            } else {
                echo "Error preparing statement: " . mysqli_error($connection);
            }
        }

        mysqli_stmt_close($checkStmt);
    } else {
        echo "Error preparing check statement: " . mysqli_error($connection);
    }

    mysqli_close($connection);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap');

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f4f7fb;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        h2 {
            font-size: 28px;
            color: #3CAEC3;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            width: 100%;
            max-width: 450px;
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        form:hover {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        label {
            font-size: 16px;
            color: #555;
            margin-bottom: 8px;
            display: block;
            font-weight: 600;
        }

        input, select, button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        input:focus, select:focus {
            border-color: #3CAEC3;
            outline: none;
            box-shadow: 0 0 8px rgba(60, 174, 195, 0.2);
        }

        button {
            background-color: #FF6952;
            color: white;
            border: none;
            font-size: 18px;
            padding: 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #e95d46;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            form {
                padding: 20px;
                max-width: 100%;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<h2>Add a New Book</h2>

<form action="AddBook.php" method="POST">
    <label for="Title">Title:</label>
    <input type="text" id="Title" name="Title" required>
    
    <label for="Author">Author:</label>
    <input type="text" id="Author" name="Author" required>
    
    <label for="Status">Status:</label>
    <select id="Status" name="Status" required>
        <option value="Available">Available</option>
        <option value="Unavailable">Unavailable</option>
    </select>
    
    <label for="Edition">Edition:</label>
    <input type="text" id="Edition" name="Edition" required>
    
    <label for="Price">Price:</label>
    <input type="number" id="Price" name="Price" step="0.01" required>
    
    <label for="Quantity">Quantity:</label>
    <input type="number" id="Quantity" name="Quantity" required>
    
    <label for="Category">Category:</label>
    <input type="text" id="Category" name="Category" required>
    
    <button type="submit">Submit</button>
</form>

<div class="footer">
    <p>&copy; 2024 Library Management System</p>
</div>

</body>
</html>

