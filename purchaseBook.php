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
    <link rel="stylesheet" href="css/BookDetailsStyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .book-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .total-price {
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
<header class="header">
    <div class="logo">
        <a href="index.php">Knowledge Nest</a>
    </div>
    <nav class="nav-bar">
        <ul class="nav__links">
            <li><a href="index.php">Home</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact</a></li>
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
                    <b class="logout-text">Logout</b>
                </button>
            </form>
        <?php else: ?>
            <a href="login.php" class="login-icon">
                <button><b class="login-text">Login</b></button>
            </a>
        <?php endif; ?>
    </div>
</header>

<div class="container book-container">
    <?php if ($book): ?>
        <h3 class="text-center"><?php echo htmlspecialchars($book['Title']); ?></h3>
        <p>Price: $<span id="book-price"><?php echo htmlspecialchars($book['Price']); ?></span></p>
        <p>Available Quantity: <?php echo htmlspecialchars($book['Quantity']); ?></p>

        <form id="purchase-form" action="confirmPurchase.php" method="POST">
            <div class="mb-3">
                <label for="quantity" class="form-label">Select Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="<?php echo htmlspecialchars($book['Quantity']); ?>" required>
            </div>
            <p class="total-price">Total Price: $<span id="total-price"><?php echo htmlspecialchars($book['Price']); ?></span></p>

            <input type="hidden" name="BookID" value="<?php echo htmlspecialchars($bookID); ?>">
            <input type="hidden" name="Price" value="<?php echo htmlspecialchars($book['Price']); ?>">

            <button type="submit" class="btn btn-primary w-100">Confirm Purchase</button>
        </form>
    <?php else: ?>
        <p class="text-center">Book not found!</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
