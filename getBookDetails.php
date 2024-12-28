<?php
if (isset($_GET['BookID']) && is_numeric($_GET['BookID'])) {
    $bookID = $_GET['BookID'];

    $conn = new mysqli("localhost", "root", "", "library");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT * FROM book WHERE BookID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $bookID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $is_available = $row['Quantity'] > 0;
        ?>
        <div class="book-details">
            <div class="book-image">
                <img src="<?= htmlspecialchars($row['ImagePath'] ?? 'css/Image/Book2.jpg') ?>" alt="Book Cover">
            </div>
            <div class="book-info">
                <h1><?= htmlspecialchars($row['Title']) ?></h1>
                <p><strong>Author:</strong> <?= htmlspecialchars($row['Author']) ?></p>
                <p><strong>Edition:</strong> <?= htmlspecialchars($row['Edition']) ?></p>
                <p><strong>Category:</strong> <?= htmlspecialchars($row['Category']) ?></p>
                <p><strong>Price:</strong> $<?= htmlspecialchars($row['Price']) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($row['Description']) ?></p>
                <p><strong>Quantity Available:</strong> <?= htmlspecialchars($row['Quantity']) ?></p>
            </div>
        </div>
        <?php
    } else {
        echo "<p>Book not found!</p>";
    }
    $conn->close();
}
?>
