<?php
session_start();
require_once "../config/database.php";

if ($_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $year = $_POST["year"];

    $stmt = $conn->prepare("INSERT INTO books (title, author, year) VALUES (:title, :author, :year)");
    $stmt->execute(["title" => $title, "author" => $author, "year" => $year]);

    header("Location: view_books.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Book</title>
    <link rel="stylesheet" href="../static/style.css">
</head>
<body class="add-book">
    <div class="container">
        <h2>Add Book</h2>
        <form method="POST">
            <input type="text" name="title" placeholder="Book Title" required><br><br>
            <input type="text" name="author" placeholder="Author" required><br><br>
            <input type="number" name="year" placeholder="Year" required><br><br>
            <button type="submit">Add Book</button>
        </form>
    </div>
</body>
</html>
