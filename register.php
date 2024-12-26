<?php
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $passworddb = "WEBDBwebdb123456789";

    
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
        exit;
    }
 
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $userType = 'user';
    $passworddb = "WEBDBwebdb123456789";

    
    $connection = mysqli_connect("localhost", "root", "WEBDBwebdb123456789", "library");
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
  
    $stmt = $connection->prepare("SELECT * FROM user WHERE UserName = ? OR Email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['UserName'] === $username) {
            echo "<script>alert('Username already exists. Please choose a different one.');</script>";
        } elseif ($user['Email'] === $email) {
            echo "<script>alert('Email is already registered. Please choose a different one.');</script>";
        }
    } else {
        
        $insertQuery = $connection->prepare("INSERT INTO user (UserName, Password, Email, Type) VALUES (?, ?, ?, ?)");
        $insertQuery->bind_param("ssss", $username, $hashedPassword, $email, $userType);

        if ($insertQuery->execute()) {
            
            header("Location: login.php");
            exit;
        } else {
            echo "Error: " . $insertQuery->error;
        }
    }

    
    $stmt->close();
    mysqli_close($connection);
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
    <header class="header">
        <div class="logo">
            <a href="#">Knowledge Nest</a>
        </div>
        <nav class="nav-bar">
            <ul class="nav__links">
                <li><a href="index.html">Home</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="#">Books</a></li>
            </ul>
        </nav>
        <div class="toggle-btn">
            <i class="fa-solid fa-bars"></i>
        </div>

        <div class="dropdown-menu">
            <li><a href="index.html">Home</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">Books</a></li>
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
                        <input type="password" placeholder="Password" name="password" required>
                    </div>
                    <div class="textbox">
                        <input type="password" placeholder="Confirm Password" name="confirm_password" required>
                    </div>
                    <input type="submit" value="Register" name="login" class="login-btn">
                </form>
    
                <div class="register-prompt">
                    <p>Already have an account? <a href="login.html">Login here</a></p>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="footer-container">
            <!-- Logo Section -->
            <div class="footer-logo-section">
                <img src="Image/KnowledgeNest-noBK.png" alt="Harvard Shield" class="footer-logo">
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
