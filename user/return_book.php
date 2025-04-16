<?php
session_start();
require_once "../config/database.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["user_id"])) {
    die("You must be logged in to return a book.");
}

$user_id = $_SESSION["user_id"];


$stmt = $conn->prepare("SELECT books.id, books.title, books.author, books.year, borrowed_books.due_date FROM borrowed_books 
                        JOIN books ON borrowed_books.book_id = books.id 
                        WHERE borrowed_books.user_id = :user_id");
$stmt->execute(["user_id" => $user_id]);
$borrowed_books = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["book_id"])) {
    $book_id = $_POST["book_id"];

    
    $stmt = $conn->prepare("DELETE FROM borrowed_books WHERE user_id = :user_id AND book_id = :book_id");
    $stmt->execute(["user_id" => $user_id, "book_id" => $book_id]);

    
    $stmt = $conn->prepare("UPDATE books SET available = 1 WHERE id = :book_id");
    $stmt->execute(["book_id" => $book_id]);

    echo "<p style='color: green;'>Book returned successfully!</p>";
    
    
    header("Refresh: 1");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Return Book</title>
    <link rel="stylesheet" href="../static/style.css">
</head>
<body class="return-book">
    <div class="container">
        <h2>Return a Book</h2>
        <?php if (empty($borrowed_books)): ?>
            <p>You have not borrowed any books.</p>
        <?php else: ?>
            <form method="POST">
                <table border="1" cellpadding="10">
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Year</th>
                        <th>Due date</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($borrowed_books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['title']) ?></td>
                        <td><?= htmlspecialchars($book['author']) ?></td>
                        <td><?= htmlspecialchars($book['year']) ?></td>
                        <td><?= htmlspecialchars($book['due_date']) ?></td>

                        <td>
                            <button type="submit" name="book_id" value="<?= $book['id'] ?>">Return</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <a href="../dashboard/user_dashboard.php">Back to Dashboard</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
