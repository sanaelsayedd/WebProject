<?php
$servername = "localhost";
$username = "root";
$password = "WEBDBwebdb123456789"; 
$dbname = "library";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT `BookID`, `Title`, `Author`, `Status`, `Edition`, `Price`, `Quantity`, `Category` FROM `book` WHERE `Title` LIKE '%$searchQuery%' OR `Author` LIKE '%$searchQuery%' OR `Category` LIKE '%$searchQuery%'";
} else {
    
    $sql = "SELECT `BookID`, `Title`, `Author`, `Status`, `Edition`, `Price`, `Quantity`, `Category` FROM `book`";
}


$result = $conn->query($sql);

$books = [];


if ($result->num_rows > 0) {
    
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
} else {
    
    $books = [];
}


header('Content-Type: application/json');


echo json_encode($books);


$conn->close();
?>
