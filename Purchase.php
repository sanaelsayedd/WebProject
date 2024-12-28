<?php
session_start();

$is_logged_in = isset($_SESSION['username']);
$userType = $is_logged_in ? $_SESSION['userType'] : null; 

if (!$is_logged_in) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy(); 
    header("Location: index.php"); 
    exit();
}

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "library"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT `PurchaseTransactionID`, `UserID`, `BookID`, `Quantity`,`TotalPrice` FROM `purchase_transaction`";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reversation Books</title>
    <style>
                body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
        }

        table th {
            background-color: #55608f;
            color: #ffffff;
            text-transform: uppercase;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #ddd;
        }

        table td {
            border-bottom: 1px solid #dddddd;
        }

        .logout {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 20px;
            background-color: #55608f;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .logout:hover {
            background-color: #37405c;
        }
    </style>
</head>
<body>
    <?php if ($userType === 'admin') { ?>
    <div class="container">
        <h1>Purchase Transaction Books</h1>
        <a href="AddPurchase.php">Add New Purchase</a>
        <table>
            <thead>
                <tr>
                    <th>Purchase Transaction ID</th>
                    <th>User ID</th>
                    <th>Book ID</th>
                    <th>Quantity</th>
                    <th>TotalPrice</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['PurchaseTransactionID']); ?></td>
                            <td><?= htmlspecialchars($row['UserID']); ?></td>
                            <td><?= htmlspecialchars($row['BookID']); ?></td>
                            <td><?= htmlspecialchars($row['Quantity']); ?></td>
                            <td><?= htmlspecialchars($row['TotalPrice']); ?></td>
                            <td>
                                <a href="EditPurchase.php?PurchaseTransactionID=<?= urlencode($row['PurchaseTransactionID']); ?>">Edit</a> |
                                <a href="DeletePurchase.php?PurchaseTransactionID=<?= urlencode($row['PurchaseTransactionID']); ?>" 
                                   onclick="return confirm('Are you sure you want to delete this Purchase?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No data found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
</body>
</html>
