<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user input
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = trim($_POST['password']); // Trim any spaces
    $passworddb = "WEBDBwebdb123456789";
    // Establish database connection
    $connection = mysqli_connect("localhost", "root", "WEBDBwebdb123456789", "library");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare SQL query using a prepared statement to avoid SQL injection
    $stmt = $connection->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $hashPass = $user['Password']; 

        
        if (password_verify($password, $hashPass)) {
            $_SESSION['username'] = $username;
            $_SESSION['userType'] = $user['Type'];

           
            if ($_SESSION['userType'] === 'admin') {
                header("Location: dashboard.html"); 
                exit();
            } else {
                header("Location: index.php"); 
                exit();
            }
        } else {
            echo "<script>alert('Invalid password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('No user found with that username. Please try again.');</script>";
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
    <title>Knowledge Nest - Login</title>
    <link rel="stylesheet" href="css/loginStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <header class="header">
        <div class="logo">
            <a href="#">Knowledge Nest</a>
        </div>
        <nav class="nav-bar">
            <ul class="nav__links">
                <li><a href="index.php">Home</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="#">Books</a></li>
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
                <img src="Image/KnowledgeNest-noBK.png" alt="Harvard Shield" class="footer-logo">
            </div>
            <div class="footer-content">
                <div class="footer-links">
                    <div class="link-column">
                        <p>GIVING TO THE LIBRARY</p>
                        <p>OFFICE OF THE PROVOST</p>
                        <p>HOLLIS</p>
                        <p>HOLLIS FOR ARCHIVAL DISCOVERY</p>
                        <p>DATABASES</p>
                    </div>
                    <div class="link-column">
                        <p>NEWSLETTERS/SOCIAL</p>
                        <p>STAFF PORTAL</p>
                        <p>LIBRARY ACCESSIBILITY</p>
                        <p>REPORT A PROBLEM</p>
                    </div>
                    <div class="link-column">
                        <div class="footer-policy-links">
                            <a href="#">Accessibility</a>
                            <a href="#">Privacy</a>
                        </div>
                    </div>
                </div>
                <p class="footer-license">
   


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knowledge Nest - Login</title>
    <link rel="stylesheet" href="css/loginStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <header class="header">
        <div class="logo">
            <a href="#">Knowledge Nest</a>
        </div>
        <nav class="nav-bar">
            <ul class="nav__links">
                <li><a href="index.php">Home</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="#">Books</a></li>
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
                <img src="Image/KnowledgeNest-noBK.png" alt="Harvard Shield" class="footer-logo">
            </div>
            <div class="footer-content">
                <div class="footer-links">
                    <div class="link-column">
                        <p>GIVING TO THE LIBRARY</p>
                        <p>OFFICE OF THE PROVOST</p>
                        <p>HOLLIS</p>
                        <p>HOLLIS FOR ARCHIVAL DISCOVERY</p>
                        <p>DATABASES</p>
                    </div>
                    <div class="link-column">
                        <p>NEWSLETTERS/SOCIAL</p>
                        <p>STAFF PORTAL</p>
                        <p>LIBRARY ACCESSIBILITY</p>
                        <p>REPORT A PROBLEM</p>
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
                    For details and exceptions, see the <a href="#">Harvard Library Copyright Policy</a> 
                    &copy;2024 Presidents and Fellows of Harvard College.
                </p>
            </div>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
