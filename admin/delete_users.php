<?php
session_start();
require_once "../config/database.php";

if ($_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

if (isset($_GET["id"])) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute(["id" => $_GET["id"]]);

    header("Location: manage_users.php");
    exit();
}
?>
