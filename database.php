<?php
    $password = "WEBDBwebdb123456789";

    $conn = mysqli_connect("localhost", "root", $password, "library");

    if (mysqli_affected_rows($conn) > 0) {
        echo "Data inserted";
    }
    else {
        echo "error";
    }
?>