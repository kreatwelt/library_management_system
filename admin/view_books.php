<?php
session_start();
require_once "../config/database.php";

if ($_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

$books = $conn->query("SELECT * FROM books")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Books</title>
    <link rel="stylesheet" href="../static/style.css">
</head>
<body class="view-books">
    <div class="container">
        <h2>View Books</h2>
        <a href="../dashboard/admin_dashboard.php">Go Home</a><br><br>
        <a href="add_book.php">Add Book</a><br><br>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Genre</th>
                <th>Year</th>
                <th>quantity</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?= $book["id"] ?></td>
                    <td><?= $book["title"] ?></td>
                    <td><?= $book["author"] ?></td>
                    <td><?= $book["ISBN"] ?></td>
                    <td><?= $book["genre"] ?></td>
                    <td><?= $book["year"] ?></td>
                    <td><?= $book["quantity"] ?></td>
                    <td>
                        <a href="edit_book.php?id=<?= $book['id'] ?>">Edit</a> |
                        <a href="delete_book.php?id=<?= $book['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
