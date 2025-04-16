<?php
session_start();
if ($_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../static/style.css">
</head>
<body class="admin-dashboard">
    <div class="container">
        <h2>Admin Dashboard</h2>
        <a href="../admin/manage_users.php">Manage Users</a><br>
        <a href="../admin/view_books.php">Manage Books</a><br>
        <a href="../auth/logout.php">Logout</a>
    </div>
</body>
</html>
