<?php
session_start();

$is_logged_in = isset($_SESSION['username']);
$userType = $is_logged_in ? $_SESSION['userType'] : null;

if (!$is_logged_in) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

// Database credentials
$passworddb = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $Title = $_POST["Title"];
    $Author = $_POST["Author"];
    $Status = $_POST["Status"];
    $Edition = $_POST["Edition"];
    $Price = $_POST["Price"];
    $Quantity = $_POST["Quantity"];
    $Category = $_POST["Category"];
    $Description = $_POST["Description"];  // New Description field

    // Handle the file upload
    $fileUploadError = "";
    $PDFFile = "";

    if (isset($_FILES["PDFFile"]) && $_FILES["PDFFile"]["error"] == 0) {
        $fileName = $_FILES["PDFFile"]["name"];
        $fileTmpPath = $_FILES["PDFFile"]["tmp_name"];
        $fileSize = $_FILES["PDFFile"]["size"];
        $fileType = $_FILES["PDFFile"]["type"];

        // Check if the file is a PDF
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if ($fileExtension != "pdf") {
            $fileUploadError = "Only PDF files are allowed.";
        } else {
            // Define the upload directory (pdf folder)
            $uploadDir = 'pdf/';
            $filePath = $uploadDir . basename($fileName);

          
            if (file_exists($filePath)) {
                $fileUploadError = "Sorry, file already exists.";
            } else {
                
                if (move_uploaded_file($fileTmpPath, $filePath)) {
                    $PDFFile = basename($fileName); 
                } else {
                    $fileUploadError = "There was an error uploading the file.";
                }
            }
        }
    } else {
        $fileUploadError = "No file uploaded or there was an error with the upload.";
    }

    if ($fileUploadError) {
        echo $fileUploadError;
        exit();
    }

    // Connect to the database
    $connection = mysqli_connect("localhost", "root", "", "library");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the book already exists
    $checkQuery = "SELECT * FROM `book` WHERE `Title` = '$Title' AND `Author` = '$Author'";
    $checkResult = mysqli_query($connection, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "This book already exists in the database.";
    } else {
        // Insert new book record
        $query = "INSERT INTO `book` (`Title`, `Author`, `Status`, `Edition`, `Price`, `Quantity`, `Category`, `PDFFile`, `Description`) 
                  VALUES ('$Title', '$Author', '$Status', '$Edition', '$Price', '$Quantity', '$Category', '$PDFFile', '$Description')";

        if (mysqli_query($connection, $query)) {
            echo "Book added successfully!";
            header("Location: books.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    }

    mysqli_close($connection);
}
?>



<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/addBookStyle.css">
    <title>Add Book</title>
</head>
<body>
    
<header class="header">
            <div class="logo">
                <a href="index.php"><i class="fa-solid fa-book"></i> Knowledge Nest</a>
            </div>
            <nav class="nav-bar">
                <ul class="nav__links">
                    <li><a href="index.php">Home</a></li>
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
                    <li><a href="CSection">Contact</a></li>
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
    
    <form action="AddBook.php" method="POST" class="add-book-form" enctype="multipart/form-data">
    <h2 class="h2add">Add a New Book</h2>
        <label for="Title">Title:</label>
        <input type="text" id="Title" name="Title" required>

        <label for="Author">Author:</label>
        <input type="text" id="Author" name="Author" required>

        <label for="Status">Status:</label>
        <select id="Status" name="Status" required>
            <option value="Available">Available</option>
            <option value="Unavailable">Unavailable</option>
        </select>

        <label for="Edition">Edition:</label>
        <input type="text" id="Edition" name="Edition" required>

        <label for="Price">Price:</label>
        <input type="number" id="Price" name="Price" step="0.01" required>

        <label for="Quantity">Quantity:</label>
        <input type="number" id="Quantity" name="Quantity" required>

        <label for="Category">Category:</label>
        <input type="text" id="Category" name="Category" required>

        <label for="Description">Description:</label>
        <textarea id="Description" name="Description" required></textarea>

        <label for="PDFFile">Upload PDF:</label>
        <input type="file" id="PDFFile" name="PDFFile" accept=".pdf">

        <button type="submit">Submit</button>
    </form>
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

</body>
</html>
