<?php
session_start();

// Check if the user is logged in
$is_logged_in = isset($_SESSION['username']);
$userType = $is_logged_in ? $_SESSION['userType'] : null; 


if (!$is_logged_in) {
    header("Location: login.php");
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy(); 
    header("Location: index.php"); 
    exit();
}

// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "library"; 

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch book details
$book = null;
if (isset($_GET['BookID']) && is_numeric($_GET['BookID'])) {
    $bookID = $_GET['BookID'];

    $query = "SELECT * FROM book WHERE BookID = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }

    $stmt->bind_param("i", $bookID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Book</title>
    <link rel="stylesheet" href="css/purchaseBookStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

</head>
<body>
<header class="header">
    <div class="logo">
        <a href="index.php"><i class="fa-solid fa-book"></i> Knowledge Nest</a>
    </div>
    <nav class="nav-bar">
        <ul class="nav__links">
            <li><a href="index.php">Home</a></li>
            <li><a href="#CSection">Contact</a></li>
            <li><a href="books.php">Books</a></li>
            <?php if ($is_logged_in): ?>
                <?php if ($userType === 'user'): ?>
                    <li><a href="myAccount.php">My Account</a></li>
                <?php elseif ($userType === 'admin'): ?>
                    <li><a href="dashboard.php">Admin Dashboard</a></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="login">
        <?php if ($is_logged_in): ?>
            <form method="GET" action="index.php">
                <button type="submit" name="logout">
                    <i class="fa-solid fa-sign-out-alt"></i><b class="logout-text">Logout</b>
                </button>
            </form>
        <?php else: ?>
            <a href="login.php" class="login-icon">
                <button><i class="fa-solid fa-user"></i><b class="login-text">Login</b></button>
            </a>
        <?php endif; ?>
    </div>
</header>

<main>
    <div class="book-container">
        <?php if ($book): ?>
            <h3 class="book-title"><?php echo htmlspecialchars($book['Title']); ?></h3>
            <p class="book-info">Price: $<span id="book-price"><?php echo htmlspecialchars($book['Price']); ?></span></p>
            <p class="book-info">Available Quantity: <?php echo htmlspecialchars($book['Quantity']); ?></p>

            <form id="purchase-form" action="confirmPurchase.php" method="POST">
                <div class="form-group">
                    <label for="quantity" class="form-label">Select Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="<?php echo htmlspecialchars($book['Quantity']); ?>" required>
                </div>
                <p class="total-price">Total Price: $<span id="total-price"><?php echo htmlspecialchars($book['Price']); ?></span></p>

                <input type="hidden" name="BookID" value="<?php echo htmlspecialchars($bookID); ?>">
                <input type="hidden" name="Price" value="<?php echo htmlspecialchars($book['Price']); ?>">

                <button type="submit" class="btn-purchase">Confirm Purchase</button>
            </form>
        <?php else: ?>
            <p class="book-info text-center">Book not found!</p>
        <?php endif; ?>
    </div>
    <div class="dashboard-container">
<a href="javascript:history.back()" class="back-button" >
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const price = parseFloat(document.getElementById('book-price').textContent);
        const quantityInput = document.getElementById('quantity');
        const totalPriceElement = document.getElementById('total-price');

        quantityInput.addEventListener('input', () => {
            const quantity = parseInt(quantityInput.value) || 1;
            const totalPrice = (price * quantity).toFixed(2);
            totalPriceElement.textContent = totalPrice;
        });
    });
</script>

</body>
</html>

<?php
$conn->close();
?>
