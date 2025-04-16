<?php
session_start();
require_once "../config/database.php";

if ($_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

if (isset($_GET["id"])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(["id" => $_GET["id"]]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("UPDATE users SET username = :username, role = :role WHERE id = :id");
    $stmt->execute([
        "username" => $_POST["username"],
        "role" => $_POST["role"],
        "id" => $_POST["id"]
    ]);

    header("Location: manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="../static/style.css">
</head>
<body class="edit-user">
    <div class="container">
        <h2>Edit User</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">
            <input type="text" name="username" value="<?= $user['username'] ?>" required><br><br>
            <select name="role">
                <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
            </select><br><br>
            <button type="submit">Update User</button>
        </form>
    </div>
</body>
</html>
