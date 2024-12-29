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

                    <li><a href="contact.php">Contact</a></li>
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
                    <li><a href="contact.php">Contact</a></li>
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

<section class="discount_section">
    <div class="discount_container">
        <div class="discount__data">
            <h2 class="discount__section_title">Up to 50% off</h2>
            <div class="discount__description">
                Discover our latest collection of discounted books, 
                offering you the perfect opportunity to indulge in your favorite reads without stretching your budget. Whether you're a fan of thrilling mysteries, 
                heartwarming romances, or insightful non-fiction, our carefully curated selection has something for everyone.
            </div>
            <a href="books.php" class="button-s">Shop Now</a>
        </div>
        <div class="discount__images">
            <a href="books.php"><img src="css/Image/Books-cover/book-2.png" alt="" class="discount__img-1"></a>
            <a href="books.php"><img src="css/Image/Books-cover/book-4.png" alt="" class="discount__img-2"></a>
        </div>
    </div>
</section>

            
            <section class="list-books">
                <h2 class="ttext-f">Most Popular Books</h2>
                <button class="scroll-btn left"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="books-container">
                    <?php
                    // Connect to database
                    $conn = mysqli_connect("localhost", "root", "", "library");
                    
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }
                    
                    // Query to get top 10 most borrowed books
                    $query = "SELECT b.*, COUNT(br.BookID) as borrow_count 
                            FROM book b 
                            JOIN borrow br ON b.BookID = br.BookID 
                            GROUP BY b.BookID 
                            ORDER BY borrow_count DESC 
                            LIMIT 10";
                    
                    $result = mysqli_query($conn, $query);
                    
                    if ($result) {
                        while ($book = mysqli_fetch_assoc($result)) {
                            ?>
                            <a href="books.php" class="book-link" style="text-decoration: none;">
                                <div class="book-card">
                                    <img src="<?php echo $book['image_path'] ?? 'css/Image/Knowledge Nest.webp'; ?>" alt="Book Cover" class="book-image">
                                    <div class="book-details">
                                        <h2 class="book-title"><?php echo $book['Title']; ?></h2>
                                        <p class="book-author"><strong>Author:</strong> <?php echo $book['Author']; ?></p>
                                        <p class="book-type"><strong>Category:</strong> <?php echo $book['Category']; ?></p>
                                    </div>
                                </div>
                            </a>
                            <?php
                        }
                    } else {
                        echo "Error executing query: " . mysqli_error($conn);
                    }
                    mysqli_close($conn);
                    ?>
                </div>
                <button class="scroll-btn right"><i class="fa-solid fa-chevron-right"></i></button>
            </section>

                    


            <section class="contact-section" id = "CSection">
                <div class="contact-container">
                    <h2>Contact Us</h2>
                    <p>Get in touch with us for any questions or concerns</p>
                    
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fa-solid fa-location-dot"></i>
                            <div>
                                <h3>Address</h3>
                                <p>123 Library Street, Reading Town, RT 12345</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <i class="fa-solid fa-phone"></i>
                            <div>
                                <h3>Phone</h3>
                                <p>+1 (555) 123-4567</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <i class="fa-solid fa-envelope"></i>
                            <div>
                                <h3>Email</h3>
                                <p>knowledgenest@gmail.com</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <i class="fa-solid fa-clock"></i>
                            <div>
                                <h3>Hours</h3>
                                <p>Mon-Fri: 9:00 AM - 8:00 PM</p>
                                <p>Sat-Sun: 10:00 AM - 6:00 PM</p>
                            </div>
                        </div>
                    </div>

                    <form class="contact-form">
                        <div class="form-group">
                            <input type="text" placeholder="Your Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" placeholder="Your Email" required>
                        </div>
                        <div class="form-group">
                            <input type="text" placeholder="Subject" required>
                        </div>
                        <div class="form-group">
                            <textarea placeholder="Your Message" required></textarea>
                        </div>
                        <button type="submit" class="submit-btn">Send Message</button>
                    </form>
                </div>
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