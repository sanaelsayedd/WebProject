<?php
if(isset($_POST["login"])){
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $userType = 'user';

   
$connection = mysqli_connect(hostname: 'sana', username: 'sana', password: 'sana', database: 'sana');
mysqli_query($connection, "INSERT INTO USER(`USERNAME`, `EMAIL`, `PASSWORD`, `USERTYPE`) VALUES('$username', '$email', '$password',`$userType`)");
mysqli_close($connection);

//reg check 
}

?>