<?php
session_start();
require_once "../config/database.php";

if ($_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

if (isset($_GET["id"])) {
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = :id");
    $stmt->execute(["id" => $_GET["id"]]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("UPDATE books SET title = :title, author = :author, year = :year WHERE id = :id");
    $stmt->execute([
        "title" => $_POST["title"],
        "author" => $_POST["author"],
        "year" => $_POST["year"],
        "id" => $_POST["id"]
    ]);

    header("Location: view_books.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Book</title>
    <link rel="stylesheet" href="../static/style.css">
</head>
<body class="edit-book"> <!-- Apply the 'edit-book' class here -->
    <div class="container">
        <h2>Edit Book</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $book['id'] ?>">
            <input type="text" name="title" value="<?= $book['title'] ?>" required><br><br>
            <input type="text" name="author" value="<?= $book['author'] ?>" required><br><br>
            <input type="number" name="year" value="<?= $book['year'] ?>" required><br><br>
            <button type="submit">Update Book</button>
        </form>
    </div>
</body>
</html>
