<?php
    $password = "WEBDBwebdb123456789";

    $conn = mysqli_connect("localhost", "root", $password, "library");
    $result = mysqli_query($conn, "SELECT * FROM book");
?>
