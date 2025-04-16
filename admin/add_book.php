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
    $isbn = $_POST["ISBN"];
    $genre = $_POST["genre"];
    $year = $_POST["year"];
    $quantity = $_POST["quantity"];

    $stmt = $conn->prepare("INSERT INTO books (title, author, ISBN, genre, year, quantity) VALUES (:title, :author, :ISBN, :genre, :year, :quantity)");
    $stmt->execute(["title" => $title, "author" => $author, "ISBN" =>  $isbn, "genre" => $genre, "year" => $year, "quantity" => $quantity]);

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
            <input type="text" name="ISBN" placeholder="ISBN" required><br><br>
            <input type="text" name="genre" placeholder="genre" required><br><br>
            <input type="text" name="year" placeholder="Year" required><br><br>
            <input type="text" name="quantity" placeholder="quantity" required><br><br>
            <button type="submit">Add Book</button>
            <a href="../dashboard/admin_dashboard.php">Go Home</a>
        </form>

    </div>
</body>
</html>
