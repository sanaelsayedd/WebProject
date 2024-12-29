<?php
session_start();

// Check if the user is logged in
$is_logged_in = isset($_SESSION['username']);
$userType = $is_logged_in ? $_SESSION['userType'] : null; 
$userName = $is_logged_in ? $_SESSION['username'] : null;

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Knowledge Nest</title>
    <link rel="stylesheet" href="css/contact.css">
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
        <section class="contact-hero">
            <div class="hero-content">
                <h1>Get in Touch</h1>
                <p>We're here to help and answer any question you might have</p>
            </div>
        </section>

        <section class="contact-section">
            <div class="contact-container">
                <div class="contact-info">
                    <h2>Contact Information</h2>
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

                    <div class="social-links">
                        <h3>Follow Us</h3>
                        <div class="social-icons">
                            <a href="#"><i class="fab fa-facebook"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>

                <div class="contact-form-container">
                    <h2>Send us a Message</h2>
                    <form class="contact-form" action="process_contact.php" method="POST">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" placeholder="Your Name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="Your Email" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" placeholder="Your Phone (optional)">
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" placeholder="Message Subject" required>
                        </div>

                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" placeholder="Your Message" required></textarea>
                        </div>

                        <button type="submit" class="submit-btn">Send Message</button>
                    </form>
                </div>
            </div>
        </section>

        <section class="map-section">
            <h2>Find Us</h2>
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d55251.336634698244!2d31.217264562211042!3d30.059556317003686!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14583fa60b21beeb%3A0x79dfb296e8423bba!2sCairo%2C%20Cairo%20Governorate!5e0!3m2!1sen!2seg!4v1735344697814!5m2!1sen!2seg"
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
        </section>
    </main>

    <footer>
    <div class="footer-container">
        <!-- Logo Section -->
        <div class="footer-logo-section">
            <img src="css/Image/KnowledgeNest-noBK.png" alt="Knowledge Nest Logo" class="footer-logo">
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