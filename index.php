<?php
session_start();


$is_logged_in = isset($_SESSION['username']);
$userType = $is_logged_in ? $_SESSION['userType'] : null; 

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knowledge Nest</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            <div class="dropdown-menu">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="books.php">Books</a></li>
                    <?php if ($is_logged_in): ?>
                        <?php if ($userType === 'user'): ?>
                            <li><a href="account.php">My Account</a></li>
                        <?php elseif ($userType === 'admin'): ?>
                            <li><a href="dashboard.php">Admin Dashboard</a></li>
                        <?php endif; ?>
                        <li>
                            <form method="GET" action="index.php">
                                <button type="submit" name="logout">
                                    <i class="fa-solid fa-sign-out-alt"></i><b class="logout-text">Logout</b>
                                </button>
                            </form>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="login.php" class="login-icon">
                                <button><i class="fa-solid fa-user"></i><b>Login</b></button>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

        </header>

        <main>
            <section class="section1">
                <div class="search-bar">
                    <h1>Welcome to Knowledge Nest Library</h1>
                    <div class="search-box">
                        <input type="text" class="input" placeholder="Search">
                        <button><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                    <p>Knowledge Nest is your go-to library catalog, offering easy access to a vast collection of books for browsing and borrowing.</p>
                </div>
            </section>

            <section class="library-guides">
                <h1>Using the Library</h1>
                <p>Get started using the library with these guides</p>
                <div class="book-container">
                    <div class="book" id="book1">
                        <div class="book-content">
                            <div class="book-head">
                                <i class="fa-solid fa-compass"></i>
                                <span class="tag">How To</span>
                            </div>
                            <div class="book-tex1">
                                <h2>Get Started Using the Libraries</h2>
                                <p>Ways to power up your library skills.</p>
                            </div>
                        </div>
                    </div>
                    <div class="book" id="book2">
                        <div class="book-content">
                            <div class="book-head">
                                <i class="fa-solid fa-compass"></i>
                                <span class="tag">How To</span>
                            </div>
                            <div class="book-tex2">
                                <h2>Use Knowledge Nest Library's Special Collections and Archives</h2>
                                <p>Open to all, these unique materials can take you to places you never expected.</p>
                            </div>
                        </div>
                    </div>
                    <div class="book" id="book3">
                        <div class="book-content">
                            <div class="book-head">
                                <i class="fa-solid fa-compass"></i>
                                <span class="tag">How To</span>
                            </div>
                            <div class="book-tex2">
                                <h2>Use Knowledge Nest as an Alum</h2>
                                <p>Access a growing number of electronic resources available to alumni and find out how to visit libraries.</p>
                            </div>
                        </div>
                    </div>
                    <div class="book" id="book4">
                        <div class="book-content">
                            <div class="book-head">
                                <i class="fa-solid fa-compass"></i>
                                <span class="tag">How To</span>
                            </div>
                            <div class="book-tex4">
                                <h2>Borrow, Renew, and Return Library Materials</h2>
                                <p>Connect with the library materials you need to get your work done.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="list-books">
                <div class="book-card">
                    <img src="Image/Book1.jpg" alt="Book Cover" class="book-image">
                    <div class="book-details">
                        <h2 class="book-title">Learn Programming</h2>
                        <p class="book-author"><strong>Author:</strong> John Doe</p>
                        <p class="book-type"><strong>Type:</strong> Programming</p>
                        <p class="book-price"><strong>Price:</strong> $29.99</p>
                        <p class="book-status"><strong>Status:</strong> In Stock</p>
                        <p class="book-quantity"><strong>Quantity:</strong> 15</p>
                    </div>
                </div>
            </section>

            <section class="featured_books" id="featured">
                <h2 class="section_title">Featured Books</h2>
                <div class="featured__container">
                    <div class="featured__swiper">
                        <div>
                            <article class="featured__card">
                                <img src="Image/Book1.jpg" alt="Book Cover" class="book-image">
                                <h2 class="book-title">Learn Programming</h2>
                                <div class="book-details">
                                    <p class="book-author"><strong>Author:</strong> John Doe</p>
                                    <p class="book-type"><strong>Type:</strong> Programming</p>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer>
            <div class="footer-container">
                <div class="footer-logo-section">
                    <img src="Image/KnowledgeNest-noBK.png" alt="Knowledge Nest Logo" class="footer-logo">
                </div>
                <div class="footer-content">
                    <div class="footer-links">
                        <div class="link-column">
                            <p>GIVING TO THE LIBRARY</p>
                            <p>OFFICE OF THE PROVOST</p>
                            <p>HOLLIS</p>
                            <p>DATABASES</p>
                        </div>
                        <div class="link-column">
                            <p>NEWSLETTERS/SOCIAL</p>
                            <p>STAFF PORTAL</p>
                            <p>LIBRARY ACCESSIBILITY</p>
                        </div>
                        <div class="link-column">
                            <div class="footer-policy-links">
                                <a href="#">Accessibility</a>
                                <a href="#">Privacy</a>
                            </div>
                        </div>
                    </div>
                    <p class="footer-license">
                        Creative Commons Attribution 4.0 International License. Except where otherwise noted, 
                        this work is subject to a <a href="#">Creative Commons Attribution 4.0 International License</a> 
                        which allows anyone to share and adapt our material as long as proper attribution is given.
                    </p>
                </div>
            </div>
        </footer>
        <script src="js/script.js"></script>
    </body>
</html>