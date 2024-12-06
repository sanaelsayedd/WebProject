<?php
session_start(); 

$username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
$password = $_POST['password'];
// Handle login process

$connection = mysqli_connect('sana', 'sana', 'sana', 'sana');

$result = mysqli_query($connection, "SELECT * FROM users WHERE username = '$username'");

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    if (password_verify($password, $user['password'])) {
        $_SESSION['username'] = $username;
        $_SESSION['user_type'] = $user['user_type'];

        if ($_SESSION['user_type'] === 'admin') {
            header("Location: dashboard.html"); 
        } else {
            header("Location: "); //lesa bafaker 
        }
        exit();
    } else {
        echo "<script>
            alert('Invalid password. Please try again.');
            window.history.back();
        </script>";
    }
} else {
    echo "<script>
        alert('No user found with that username. Please try again.');
        window.history.back();
    </script>";
}

mysqli_close($connection);
?>