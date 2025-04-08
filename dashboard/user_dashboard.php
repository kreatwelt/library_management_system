<?php
session_start();
if ($_SESSION["role"] !== "user") {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../static/style.css">
</head>
<body class="user-dashboard">
    <div class="container">
        <h2>User Dashboard</h2>
        <a href="../user/borrow_book.php">Borrow a Book</a><br>
        <a href="../user/return_book.php">Return a Book</a><br>
        <a href="../user/search_book.php">Search for a Book</a><br>
        <a href="../auth/logout.php">Logout</a>
    </div>
</body>
</html>
