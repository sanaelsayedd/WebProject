<?php
session_start();

if (isset($_POST['login'])) {
    $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Password match check
    if ($password !== $confirmPassword) {
        $_SESSION['alert'] = [
            'message' => 'Passwords do not match!',
            'type' => 'error'
        ];
        header('Location: register.php');
        exit;
    }

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['alert'] = [
            'message' => 'Invalid email format!',
            'type' => 'error'
        ];
        header('Location: register.php');
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $userType = 'user';

    try {
        $connection = mysqli_connect("localhost", "root", "", "library");
        if (!$connection) {
            throw new Exception("Connection failed: " . mysqli_connect_error());
        }

        // Check existing user
        $stmt = $connection->prepare("SELECT * FROM user WHERE UserName = ? OR Email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if ($user['UserName'] === $username) {
                $_SESSION['alert'] = [
                    'message' => 'Username already exists. Please choose a different one.',
                    'type' => 'warning'
                ];
                header('Location: register.php');
                exit;
            } elseif ($user['Email'] === $email) {
                $_SESSION['alert'] = [
                    'message' => 'Email is already registered. Please choose a different one.',
                    'type' => 'warning'
                ];
                header('Location: register.php');
                exit;
            }
        } else {
            $insertQuery = $connection->prepare("INSERT INTO user (UserName, Password, Email, Type) VALUES (?, ?, ?, ?)");
            $insertQuery->bind_param("ssss", $username, $hashedPassword, $email, $userType);

            if ($insertQuery->execute()) {
                $_SESSION['alert'] = [
                    'message' => 'Registration successful! Redirecting...',
                    'type' => 'success'
                ];
                $_SESSION['redirect_after_alert'] = true;
                header('Location: register.php');
                exit;
            } else {
                $_SESSION['alert'] = [
                    'message' => 'Error during registration. Please try again.',
                    'type' => 'error'
                ];
                header('Location: register.php');
                exit;
            }
        }

        // Make sure to close database connections
        $stmt->close();
        mysqli_close($connection);

    } catch (Exception $e) {
        $_SESSION['alert'] = [
            'message' => 'Error during registration. Please try again.',
            'type' => 'error'
        ];
        if (isset($connection)) {
            mysqli_close($connection);
        }
        header('Location: register.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knowledge Nest - Register</title>
    <link rel="stylesheet" href="css/registrationStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php
    if (isset($_SESSION['alert'])) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                showAlert('" . addslashes($_SESSION['alert']['message']) . "', '" . $_SESSION['alert']['type'] . "');
                " . (isset($_SESSION['redirect_after_alert']) ? "
                    setTimeout(function() {
                        window.location.href = 'login.php';
                    }, 2000);
                " : "") . "
            });
        </script>";
        unset($_SESSION['alert']);
        unset($_SESSION['redirect_after_alert']);
    }
    ?>
    <header class="header">
        <div class="logo">
        <a href="index.php"><i class="fa-solid fa-book"></i> Knowledge Nest</a>
        </div>
        <nav class="nav-bar">
            <ul class="nav__links">
                <li><a href="index.php">Home</a></li>
                <li><a href="CSection">Contact</a></li>
                <li><a href="books.php">Books</a></li>
            </ul>
        </nav>
        <div class="toggle-btn">
            <i class="fa-solid fa-bars"></i>
        </div>

        <div class="dropdown-menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="books.php">Books</a></li>
        </div>
    </header>

    <section class="login-section">
        <div class="login-container">
            <div class="login-box">
                <h2>Register</h2>
                <form action="register.php" method="post">
                    <div class="textbox">
                        <input type="email" placeholder="Email" name="email" required>
                    </div>
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
                        <input type="password" placeholder="Password" name="password" minlength="8" pattern=".{8,}" title="Password must be at least 8 characters long" required>
                    </div>
                    <div class="textbox">
                        <input type="password" placeholder="Confirm Password" name="confirm_password" >
                    </div>
                    
                    <input type="submit" value="Register" name="login" class="login-btn">
                </form>
    
                <div class="register-prompt">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>
    </section>
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

    <script src="js/alerts.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
