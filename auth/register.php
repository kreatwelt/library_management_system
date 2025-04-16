<?php
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["name"]; 
    $password = $_POST["password"];
    $role = $_POST["role"];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT); 

  
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
    $stmt->execute([
        "username" => $username, 
        "password" => $hashed_password,
        "role" => $role
    ]);

    echo "Registration successful! <a href='login.php'>Login here</a>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../static/style.css">
</head>
<body class="register">
    <div class="container">
        <h2>Register</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <label for="role">Register as:</label>
            <select name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select><br><br>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
