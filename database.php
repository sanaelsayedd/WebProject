<?php
    $password = "";

    $conn = mysqli_connect("localhost", "root", "WEBDBwebdb123456789", "library");

    if (mysqli_affected_rows($conn) > 0) {
        echo "Data inserted";
    }
    else {
        echo "error";
    }
?>