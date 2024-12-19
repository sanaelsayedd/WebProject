<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "WEBDBwebdb123456789";
$dbname = "library";

$connection = mysqli_connect($servername, $username, $password, $dbname);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch users and books for dropdowns
$userQuery = "SELECT UserID, UserName FROM user";
$userResult = mysqli_query($connection, $userQuery);

$bookQuery = "SELECT BookID, Title FROM book";
$bookResult = mysqli_query($connection, $bookQuery);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $UserID = $_POST["UserID"];
    $BookID = $_POST["BookID"];
    $IssueDate = $_POST["IssueDate"];
    $StartDate = $_POST["StartDate"];
    $ReturnDate = $_POST["ReturnDate"];

    // Insert record into borrow table
    $query = "INSERT INTO `borrow`(`UserID`, `BookID`, `IssueDate`, `StartDate`, `ReturnDate`) 
              VALUES ('$UserID', '$BookID', '$IssueDate', '$StartDate', '$ReturnDate')";

    if (mysqli_query($connection, $query)) {
        echo "<script>
                alert('Record inserted successfully!');
                window.location.href = 'borrowBook.php';
              </script>";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($connection);
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Book Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* CSS code as provided earlier */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .form-container {
            width: 100%;
            max-width: 500px;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-size: 1rem;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        select,
        input[type="date"] {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .form-container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Borrow a Book</h1>
        <form method="POST">
            <div class="form-group">
                <label for="UserID">User ID:</label>
                <select id="UserID" name="UserID" required>
                    <option value="">Select User</option>
                    <?php while ($user = mysqli_fetch_assoc($userResult)): ?>
                        <option value="<?php echo $user['UserID']; ?>">
                            <?php echo $user['UserName']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="BookID">Book ID:</label>
                <select id="BookID" name="BookID" required>
                    <option value="">Select Book</option>
                    <?php while ($book = mysqli_fetch_assoc($bookResult)): ?>
                        <option value="<?php echo $book['BookID']; ?>">
                            <?php echo $book['Title']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="IssueDate">Issue Date:</label>
                <input type="date" id="IssueDate" name="IssueDate" required>
            </div>
            <div class="form-group">
                <label for="StartDate">Start Date:</label>
                <input type="date" id="StartDate" name="StartDate" required>
            </div>
            <div class="form-group">
                <label for="ReturnDate">Return Date:</label>
                <input type="date" id="ReturnDate" name="ReturnDate" required>
            </div>
            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>
</body>
</html>
