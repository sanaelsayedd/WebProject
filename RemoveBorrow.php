<?php
$passworddb = "";

session_start(); 

if (isset($_SESSION['userType'])) {
    $userType = $_SESSION['userType'];
} else {
    $userType = 'user'; 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['BorrowID'])) {
        $BorrowID = $_POST['BorrowID'];

        $connection = mysqli_connect("localhost", "root", $passworddb, "library");

        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $select_query = "SELECT UserID, BookID, IssueDate ,StartDate,ReturnDate 
                         FROM borrow WHERE BorrowID = ?";
        $stmt = mysqli_prepare($connection, $select_query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $BorrowID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {

                $UserID = $row['UserID'];
                $BookID = $row['BookID'];
                $ReturnDate = date('Y-m-d'); 

                $insert_query = "INSERT INTO `reversation`(`UserID`, `BookID`, `ReturnDate`) 
                                 VALUES (?, ?, ?)";
                $insert_stmt = mysqli_prepare($connection, $insert_query);

                if ($insert_stmt) {
                    mysqli_stmt_bind_param($insert_stmt, "iis", $UserID, $BookID, $ReturnDate);
                    if (mysqli_stmt_execute($insert_stmt)) {

                        $delete_query = "DELETE FROM `borrow` WHERE `BorrowID` = ?";
                        $delete_stmt = mysqli_prepare($connection, $delete_query);

                        if ($delete_stmt) {
                            mysqli_stmt_bind_param($delete_stmt, "i", $BorrowID);
                            if (mysqli_stmt_execute($delete_stmt)) {

                                $_SESSION['deleted'] = true;
                                
                                if ($userType === 'user') {
                                    header("Location: myAccount.php");
                                } else if ($userType === "admin") {
                                    header("Location: borrowBook.php");
                                }
                                exit();
                            } else {
                                echo "Error deleting record from borrow: " . mysqli_stmt_error($delete_stmt);
                            }
                        }
                    } else {
                        echo "Error inserting record into reservation: " . mysqli_stmt_error($insert_stmt);
                    }
                    mysqli_stmt_close($insert_stmt);
                }
            } else {
                echo "No matching borrow record found.";
            }
            mysqli_stmt_close($stmt);
        }

        mysqli_close($connection);
    } else {
        echo "BorrowID is required.";
    }
}
?>
