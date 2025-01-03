<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user input
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = trim($_POST['password']);
   
    try {
        // Establish database connection
        $connection = mysqli_connect("localhost", "root", "", "library");
        if (!$connection) {
            throw new Exception("Connection failed: " . mysqli_connect_error());
        }

        // Prepare SQL query using a prepared statement
        $stmt = $connection->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $hashPass = $user['Password']; 

            // Close statement and connection before any exit
            $stmt->close();
            mysqli_close($connection);

            if (password_verify($password, $hashPass)) {
                $_SESSION['username'] = $username;
                $_SESSION['userType'] = $user['Type'];
                $_SESSION['alert'] = [
                    'message' => 'Login successful! Redirecting...',
                    'type' => 'success'
                ];
                $_SESSION['redirect_after_alert'] = true;
                $_SESSION['redirect_url'] = ($user['Type'] === 'admin') ? 'dashboard.php' : 'index.php';
                
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['alert'] = [
                    'message' => 'Invalid password. Please try again.',
                    'type' => 'error'
                ];
                header("Location: login.php");
                exit();
            }
        } else {
            // Close statement and connection before exit
            $stmt->close();
            mysqli_close($connection);
            
            $_SESSION['alert'] = [
                'message' => 'No user found with that username. Please try again.',
                'type' => 'error'
            ];
            header("Location: login.php");
            exit();
        }
        
    } catch (Exception $e) {
        // Close connections if they exist
        if (isset($stmt)) {
            $stmt->close();
        }
        if (isset($connection)) {
            mysqli_close($connection);
        }
        
        $_SESSION['alert'] = [
            'message' => 'An error occurred. Please try again later.',
            'type' => 'error'
        ];
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knowledge Nest - Login</title>
    <link rel="stylesheet" href="css/loginStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
</head>
<body>
    <?php
    // Add this right after the <body> tag for alert handling
    if (isset($_SESSION['alert'])) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showAlert('" . addslashes($_SESSION['alert']['message']) . "', '" . $_SESSION['alert']['type'] . "');
                " . (isset($_SESSION['redirect_after_alert']) ? "
                    setTimeout(function() {
                        window.location.href = '" . $_SESSION['redirect_url'] . "';
                    }, 2000);
                " : "") . "
            });
        </script>";
        unset($_SESSION['alert']);
        unset($_SESSION['redirect_after_alert']);
        unset($_SESSION['redirect_url']);
    }
    ?>

    <header class="header">
            <div class="logo">
                <a href="index.php"><i class="fa-solid fa-book"></i> Knowledge Nest</a>
            </div>
        <nav class="nav-bar">
            <ul class="nav__links">
                <li><a href="index.php">Home</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="books.php">Books</a></li>
            </ul>
        </nav>
        <div class="toggle-btn">
            <i class="fa-solid fa-bars"></i>
        </div>
    </header>

    <section class="login-section">
        <div class="login-container">
            <div class="login-box">
                <h2>Login</h2>
                <form method="POST" action="login.php">
                    <div class="textbox">
                        <input 
                            type="text" 
                            name="username" 
                            placeholder="Username" 
                            pattern="^[a-zA-Z0-9_]+$" 
                            title="Username can only contain letters, numbers, and underscores, and should not include '@'." 
                            required>
                    </div>
                    <div class="textbox">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <input type="submit" value="Login" class="login-btn">
                </form>
    
                <div class="register-prompt">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </div>
        </div>
    </section>

    <footer>
    <div class="footer-container">
        
        <div class="footer-logo-section">
            <img src="css/Image/KnowledgeNest-noBK.png" alt="Harvard Shield" class="footer-logo">
        </div>

       
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
   



    <script src="js/alerts.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
