<?php
require_once __DIR__ . '/../../models/user.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = new User();

    // Check login credentials
    $userId = $user->login($username, $password);

    if ($userId !== false) {
        // Login successful
        $_SESSION['user_id'] = $userId;
        header("Location: ../dashboard.php");
        exit();
    } else {
        // Login failed
        echo "Login failed. Please check your username and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../../css/user.css">
</head>
<body>
    <form method="post" action="login.php">
        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</body>
</html>
