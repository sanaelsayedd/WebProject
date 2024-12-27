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
                <a href="index.php"><i class="fa-solid fa-book"></i> Knowledge Nest</a>
            </div>
            <nav class="nav-bar">
                <ul class="nav__links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact</a></li>
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
            <!-- Search form -->
            <form id="searchForm">
                <input type="text" class="input" id="search-input" placeholder="Search">
                <button type="submit" id="search-button"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
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

<section class="discount_section" id = "discount">
<div class="disount__container container grid">
    <div class="discount__data">
        <h2 class="discount__section_title">Up to 50% off</h2>
        <p class="discount__description">
            Discover our latest discounted books, perfect for saving on your favorite reads.
        </p>

        <a href="books.php" class="button-s">Shop Now</a>
    </div>
    <div class="discount__images">
        <a href="books.php"><img src="css/Image/Books-cover/book-2.png" alt="" class="discount__img-1"></a>
       <a href="books.php"><img src="css/Image/Books-cover/book-4.png" alt="" class="discount__img-2"></a>
        </div>
</div>
</section>


            <section class="list-books">
                <button class="scroll-btn left"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="books-container">
                    <div class="book-card">
                        <i class="fa-solid fa-book-open-reader"></i>
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

                    <div class="book-card">
                        <i class="fa-solid fa-book-open-reader"></i>
                        <img src="Image/Book2.jpg" alt="Book Cover" class="book-image">
                        <div class="book-details">
                            <h2 class="book-title">Romeo and Juliet</h2>
                            <p class="book-author"><strong>Author:</strong> William Shakespeare</p>
                            <p class="book-type"><strong>Type:</strong> Drama</p>
                            <p class="book-price"><strong>Price:</strong> $19.99</p>
                            <p class="book-status"><strong>Status:</strong> In Stock</p>
                            <p class="book-quantity"><strong>Quantity:</strong> 10</p>
                        </div>
                    </div>

                    <div class="book-card">
                        <i class="fa-solid fa-book-open-reader"></i>
                        <img src="Image/Book3.jpg" alt="Book Cover" class="book-image">
                        <div class="book-details">
                            <h2 class="book-title">Pride and Prejudice</h2>
                            <p class="book-author"><strong>Author:</strong> Jane Austen</p>
                            <p class="book-type"><strong>Type:</strong> Love Story</p>
                            <p class="book-price"><strong>Price:</strong> $24.99</p>
                            <p class="book-status"><strong>Status:</strong> In Stock</p>
                            <p class="book-quantity"><strong>Quantity:</strong> 8</p>
                        </div>
                    </div>

                    <div class="book-card">
                        <i class="fa-solid fa-book-open-reader"></i>
                        <img src="Image/Book4.jpg" alt="Book Cover" class="book-image">
                        <div class="book-details">
                            <h2 class="book-title">Hamlet</h2>
                            <p class="book-author"><strong>Author:</strong> William Shakespeare</p>
                            <p class="book-type"><strong>Type:</strong> Drama</p>
                            <p class="book-price"><strong>Price:</strong> $21.99</p>
                            <p class="book-status"><strong>Status:</strong> In Stock</p>
                            <p class="book-quantity"><strong>Quantity:</strong> 12</p>
                        </div>
                    </div>

                    <div class="book-card">
                        <i class="fa-solid fa-book-open-reader"></i>
                        <img src="Image/Book5.jpg" alt="Book Cover" class="book-image">
                        <div class="book-details">
                            <h2 class="book-title">The Notebook</h2>
                            <p class="book-author"><strong>Author:</strong> Nicholas Sparks</p>
                            <p class="book-type"><strong>Type:</strong> Love Story</p>
                            <p class="book-price"><strong>Price:</strong> $23.99</p>
                            <p class="book-status"><strong>Status:</strong> In Stock</p>
                            <p class="book-quantity"><strong>Quantity:</strong> 7</p>
                        </div>
                    </div>

                    <div class="book-card">
                        <i class="fa-solid fa-book-open-reader"></i>
                        <img src="Image/Book6.jpg" alt="Book Cover" class="book-image">
                        <div class="book-details">
                            <h2 class="book-title">Macbeth</h2>
                            <p class="book-author"><strong>Author:</strong> William Shakespeare</p>
                            <p class="book-type"><strong>Type:</strong> Drama</p>
                            <p class="book-price"><strong>Price:</strong> $20.99</p>
                            <p class="book-status"><strong>Status:</strong> In Stock</p>
                            <p class="book-quantity"><strong>Quantity:</strong> 9</p>
                        </div>
                    </div>

                    <div class="book-card">
                        <i class="fa-solid fa-book-open-reader"></i>
                        <img src="Image/Book7.jpg" alt="Book Cover" class="book-image">
                        <div class="book-details">
                            <h2 class="book-title">Me Before You</h2>
                            <p class="book-author"><strong>Author:</strong> Jojo Moyes</p>
                            <p class="book-type"><strong>Type:</strong> Love Story</p>
                            <p class="book-price"><strong>Price:</strong> $22.99</p>
                            <p class="book-status"><strong>Status:</strong> In Stock</p>
                            <p class="book-quantity"><strong>Quantity:</strong> 11</p>
                        </div>
                    </div>

                    <div class="book-card">
                        <i class="fa-solid fa-book-open-reader"></i>
                        <img src="Image/Book8.jpg" alt="Book Cover" class="book-image">
                        <div class="book-details">
                            <h2 class="book-title">The Glass Menagerie</h2>
                            <p class="book-author"><strong>Author:</strong> Tennessee Williams</p>
                            <p class="book-type"><strong>Type:</strong> Drama</p>
                            <p class="book-price"><strong>Price:</strong> $18.99</p>
                            <p class="book-status"><strong>Status:</strong> In Stock</p>
                            <p class="book-quantity"><strong>Quantity:</strong> 6</p>
                        </div>
                    </div>

                    <div class="book-card">
                        <i class="fa-solid fa-book-open-reader"></i>
                        <img src="Image/Book9.jpg" alt="Book Cover" class="book-image">
                        <div class="book-details">
                            <h2 class="book-title">The Fault in Our Stars</h2>
                            <p class="book-author"><strong>Author:</strong> John Green</p>
                            <p class="book-type"><strong>Type:</strong> Love Story</p>
                            <p class="book-price"><strong>Price:</strong> $25.99</p>
                            <p class="book-status"><strong>Status:</strong> In Stock</p>
                            <p class="book-quantity"><strong>Quantity:</strong> 13</p>
                        </div>
                    </div>

                    <div class="book-card">
                        <i class="fa-solid fa-book-open-reader"></i>
                        <img src="Image/Book10.jpg" alt="Book Cover" class="book-image">
                        <div class="book-details">
                            <h2 class="book-title">A Streetcar Named Desire</h2>
                            <p class="book-author"><strong>Author:</strong> Tennessee Williams</p>
                            <p class="book-type"><strong>Type:</strong> Drama</p>
                            <p class="book-price"><strong>Price:</strong> $19.99</p>
                            <p class="book-status"><strong>Status:</strong> In Stock</p>
                            <p class="book-quantity"><strong>Quantity:</strong> 5</p>
                        </div>
                    </div>
                </div>
                <button class="scroll-btn right"><i class="fa-solid fa-chevron-right"></i></button>

            </section>

            
        </main>

        <footer>
    <div class="footer-container">
        <!-- Logo Section -->
        <div class="footer-logo-section">
            <img src="css/Image/KnowledgeNest-noBK.png" alt="Harvard Shield" class="footer-logo">
        </div>

        <!-- Links and License Section -->
        <div class="footer-content">
            <div class="footer-links">
                <!-- First Column -->
                <div class="link-column">
                    <p>GIVING TO THE LIBRARY</p>
                    <p>OFFICE OF THE PROVOST</p>
                    <p>HOLLIS</p>
                    <p>HOLLIS FOR ARCHIVAL DISCOVERY</p>
                    <p>DATABASES</p>
                </div>

                <!-- Second Column -->
                <div class="link-column">
                    <p>NEWSLETTERS/SOCIAL</p>
                    <p>STAFF PORTAL</p>
                    <p>LIBRARY ACCESSIBILITY</p>
                    <p>REPORT A PROBLEM</p>
                </div>

                <!-- Third Column -->
                <div class="link-column">
                    <div class="footer-policy-links">
                        <a href="#">Accessibility</a>
                        <a href="#">Privacy</a>
                    </div>
                </div>
            </div>

            <!-- License Section -->
            <p class="footer-license">
                Creative Commons Attribution 4.0 International License. Except where otherwise noted, 
                this work is subject to a <a href="#">Creative Commons Attribution 4.0 International License</a> 
                which allows anyone to share and adapt our material as long as proper attribution is given. 
                For details and exceptions, see the <a href="#">Harvard Library Copyright Policy</a> 
                &copy;2024 Presidents and Fellows of Harvard College.
            </p>
        </div>
    </div>
</footer>

        <script src="js/script.js"></script>
    </body>
</html>