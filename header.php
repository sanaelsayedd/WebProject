<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$is_logged_in = isset($_SESSION['username']);
$userType = $is_logged_in ? $_SESSION['userType'] : null; 

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<header class="header">
    <div class="logo">
        <a href="index.php"><i class="fa-solid fa-book"></i> Knowledge Nest</a>
    </div>
    <nav class="nav-bar">
        <ul class="nav__links">
            <li><a href="index.php">Home</a></li>
            <li><a href="#CSection">Contact</a></li>
            <li><a href="books.php">Books</a></li>

            <?php if ($userType === 'admin') {?>
            <li><a href="borrowBook.php">Borrow</a></li>
            <li><a href="reservation.php">Reservation</a></li>
            <?php }?>
            
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

    <div class="toggle-btn">
        <i class="fa-solid fa-bars"></i>
    </div>
    
    </div>
</header> 