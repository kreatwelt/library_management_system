<?php
session_start();
require_once "../config/database.php";

if ($_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

if (isset($_GET["id"])) {
    $stmt = $conn->prepare("DELETE FROM books WHERE id = :id");
    $stmt->execute(["id" => $_GET["id"]]);

    header("Location: view_books.php");
    exit();
}
?>
