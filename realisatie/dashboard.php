<?php
session_start(); // Start the session to access user information

if (!isset($_SESSION['user_id'])) {
    // Redirect to login if the user is not logged in
    header("Location: login.php");
    exit();
}

require_once 'user.php';
require_once 'menu.php';

$user = new User();
$userId = $_SESSION['user_id'];  // Assuming user_id is a single value
$userData = $user->getUserById($userId); // Pass the user ID to retrieve user data

$menu = new Menu();
$menu->addItem('Lespakketten', 'lespakketten.php');
$menu->addItem('Overzicht', 'overzicht.php');
$menu->addItem('Profiel', 'dashboard.php');
$menu->addItem('Logout', 'logout.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
</head>
<body>

    <div class="navigation">
        <?php
        // Render the menu
        $menu->render();
        ?>
    </div>

    <h1>Welcome, <?php echo $userData['username']; ?>!</h1>

    <h2>Your Information:</h2>
    <ul>
        <li><strong>Email:</strong> <?php echo $userData['email']; ?></li>
        <li><strong>Address:</strong> <?php echo $userData['address']; ?></li>
        <li><strong>City:</strong> <?php echo $userData['city']; ?></li>
        <!-- Add other user information here -->
    </ul>

    <a href="edit_profile.php">Edit Profile</a>

    <!-- <br>
    <a href="logout.php">Logout</a> -->
</body>
</html>
