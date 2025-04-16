<?php
session_start();
include '../config/database.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $username = $_POST['username']; 
    $password = $_POST['password'];

   
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
    $stmt->execute([$username, $role]); 
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) { 
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['username'];


        header("Location: ../dashboard/" . ($role == 'admin' ? "admin_dashboard.php" : "user_dashboard.php"));
        exit();
    } else {
        echo "Invalid username, password, or role selection."; 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Library System</title>
    <link rel="stylesheet" href="/library_management_system/static/style.css">
</head>
<body class="login">
    <div class="login-container">
        <h2>Login</h2>
        <?php if ($message): ?>
            <p class="error"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="role">Login as:</label>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            
            <label for="username">Username:</label>
            <input type="text" name="username" required>  

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="/library_management_system/auth/register.php">Register here</a></p>
    </div>
</body>
</html>
