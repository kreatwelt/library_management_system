<?php
session_start();
require_once "../config/database.php";

if ($_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

$users = $conn->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="../static/style.css">
</head>
<body class="manage-users"> 
    <div class="container">
    <a href="../dashboard/admin_dashboard.php"> Home</a>
        <h2>Manage Users</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user["id"] ?></td>
                    <td><?= $user["username"] ?></td>
                    <td><?= $user["role"] ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $user['id'] ?>">Edit</a> 
                        <a href="delete_users.php?id=<?= $user['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
