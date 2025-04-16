<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];


$title = isset($_GET["title"]) ? $_GET["title"] : "";
$author = isset($_GET["author"]) ? $_GET["author"] : "";


$query = "SELECT * FROM books WHERE available = 1";
$params = [];

if (!empty($title)) {
    $query .= " AND title LIKE :title";
    $params["title"] = "%$title%";
}
if (!empty($author)) {
    $query .= " AND author LIKE :author";
    $params["author"] = "%$author%";
}

$stmt = $conn->prepare($query);
$stmt->execute($params);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["borrow_book_id"])) {
    $book_id = $_POST["borrow_book_id"];

   
    $stmt = $conn->prepare("INSERT INTO borrowed_books (user_id, book_id) VALUES (:user_id, :book_id)");
    $stmt->execute(["user_id" => $user_id, "book_id" => $book_id]);

    
    $stmt = $conn->prepare("UPDATE books SET available = 0 WHERE id = :book_id");
    $stmt->execute(["book_id" => $book_id]);

    header("Location: search_book.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Search Books</title>
    <link rel="stylesheet" href="../static/style.css">
</head>
<body class="search-book">
    <div class="container">
        <h2>Search for Books</h2>
        
        
        <form method="GET">
            <input type="text" name="title" placeholder="Title" value="<?= htmlspecialchars($title) ?>">
            <input type="text" name="author" placeholder="Author" value="<?= htmlspecialchars($author) ?>">
            <button type="submit">Search</button>
        </form>

        <h3>Search Results</h3>
        <?php if (empty($books)): ?>
            <p>No books found.</p>
        <?php else: ?>
            <form method="POST">
                <table border="1" cellpadding="10">
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Year</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($books as $book): ?>
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

        <br>
        <a href="../dashboard/user_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
