<?php
session_start();

// Check if the user is logged in
$is_logged_in = isset($_SESSION['username']);
$username = $is_logged_in ? $_SESSION['username'] : null;

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

// Database connection
$servername = "localhost"; 
$usernameDB = "root"; 
$passwordDB = ""; 
$database = "library"; 

$conn = new mysqli($servername, $usernameDB, $passwordDB, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get UserID from the database using the username
$username = $_SESSION['username'];
$query = "SELECT UserID FROM user WHERE Username = '$username'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $userID = $user['UserID'];
} else {
    die("User not found in the database.");
}

// Handle book purchase
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['BookID'], $_POST['quantity']) && is_numeric($_POST['quantity'])) {
    $bookID = $_POST['BookID'];
    $quantityPurchased = $_POST['quantity'];

    // Fetch book details
    $query = "SELECT * FROM book WHERE BookID = $bookID";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $book = mysqli_fetch_assoc($result);
        $bookQuantity = $book['Quantity'];
        $bookPrice = $book['Price'];

        if ($bookQuantity >= $quantityPurchased) {
            // Update book quantity
            $newQuantity = $bookQuantity - $quantityPurchased;
            $updateQuery = "UPDATE book SET Quantity = $newQuantity WHERE BookID = $bookID";
            mysqli_query($conn, $updateQuery);

            // Insert transaction
            $totalPrice = $bookPrice * $quantityPurchased;
            $purchaseQuery = "INSERT INTO purchase_transaction (UserID, BookID, Quantity, TotalPrice) 
                              VALUES ($userID, $bookID, $quantityPurchased, $totalPrice)";
            mysqli_query($conn, $purchaseQuery);

            header("Location: purchaseSuccess.php");
            exit();
        } else {
            echo "Insufficient stock.";
        }
    } else {
        echo "Book not found!";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
