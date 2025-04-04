<?php
session_start();
require_once "../config/database.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch available books
$stmt = $conn->query("SELECT * FROM books WHERE available = 1");
$available_books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch books that the user has borrowed
$stmt = $conn->prepare("SELECT books.id, books.title, books.author, books.year FROM borrowed_books 
                        JOIN books ON borrowed_books.book_id = books.id 
                        WHERE borrowed_books.user_id = :user_id");
$stmt->execute(["user_id" => $user_id]);
$borrowed_books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Borrow Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["borrow_book_id"])) {
    $book_id = $_POST["borrow_book_id"];

    // Insert into borrowed_books
    $stmt = $conn->prepare("INSERT INTO borrowed_books (user_id, book_id) VALUES (:user_id, :book_id)");
    $stmt->execute(["user_id" => $user_id, "book_id" => $book_id]);

    // Mark book as unavailable
    $stmt = $conn->prepare("UPDATE books SET available = 0 WHERE id = :book_id");
    $stmt->execute(["book_id" => $book_id]);

    header("Refresh: 1");
}

// Handle Return Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["return_book_id"])) {
    $book_id = $_POST["return_book_id"];

    // Delete from borrowed_books
    $stmt = $conn->prepare("DELETE FROM borrowed_books WHERE user_id = :user_id AND book_id = :book_id");
    $stmt->execute(["user_id" => $user_id, "book_id" => $book_id]);

    // Mark book as available
    $stmt = $conn->prepare("UPDATE books SET available = 1 WHERE id = :book_id");
    $stmt->execute(["book_id" => $book_id]);

    header("Refresh: 1");
}

// Handle Logout
if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Borrow or Return Book</title>
    <link rel="stylesheet" href="../static/style.css">
</head>
<body class="borrow-book"> <!-- Apply the 'borrow-book' class here -->
    <div class="container">
        <h2>Library System - Borrow & Return Books</h2>

        <!-- Logout Button -->
        <form method="POST" style="text-align: right;">
            <button type="submit" name="logout">Logout</button>
        </form>

        <!-- Available Books -->
        <h3>Available Books</h3>
        <?php if (empty($available_books)): ?>
            <p>No books available for borrowing.</p>
        <?php else: ?>
            <form method="POST">
                <table border="1" cellpadding="10">
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Year</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($available_books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['title']) ?></td>
                        <td><?= htmlspecialchars($book['author']) ?></td>
                        <td><?= htmlspecialchars($book['year']) ?></td>
                        <td>
                            <button type="submit" name="borrow_book_id" value="<?= $book['id'] ?>">Borrow</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </form>
        <?php endif; ?>

        <!-- Borrowed Books -->
        <h3>Your Borrowed Books</h3>
        <?php if (empty($borrowed_books)): ?>
            <p>You have not borrowed any books.</p>
        <?php else: ?>
            <form method="POST">
                <table border="1" cellpadding="10">
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Year</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($borrowed_books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['title']) ?></td>
                        <td><?= htmlspecialchars($book['author']) ?></td>
                        <td><?= htmlspecialchars($book['year']) ?></td>
                        <td>
                            <button type="submit" name="return_book_id" value="<?= $book['id'] ?>">Return</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
