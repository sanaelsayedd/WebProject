<?php
session_start(); 

if (isset($_POST["login"])) {
  
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];

    
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
            
            echo "Invalid password.";
        }
    } else {
        
        echo "No user found with that username.";
    }

    mysqli_close($connection);
    }

?>
