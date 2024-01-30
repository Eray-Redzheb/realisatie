<?php
session_start(); // Start the session to access user information

if (!isset($_SESSION['user_id'])) {
    // Redirect to login if the user is not logged in
    header("Location: auth/login.php");
    exit();
}



require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/menu.php';

$user = new User();
$userId = $_SESSION['user_id'];  // Assuming user_id is a single value
$userData = $user->getUserById($userId); // Pass the user ID to retrieve user data

$menu = new Menu();
$menu->addItem('Lespakketten', 'lespakketten.php');
$menu->addItem('Overzicht', 'overzicht.php');
$menu->addItem('Members', 'portal.php');
$menu->addItem('Score', 'score.php');
$menu->addItem('Profiel', 'dashboard.php');
$menu->addItem('Logout', '../pages/auth/logout.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../css/dashboard.css">
</head>
<body>

    <div class="navigation">
        <?php $menu->render() ?>
    </div>

    <h1>Welcome, <?php echo $userData['gebruikersnaam']; ?>!</h1>

    <h2>Your Information:</h2>
    <ul>
        <li><strong>Email:</strong> <?php echo $userData['email']; ?></li>
        <li><strong>Address:</strong> <?php echo $userData['adres']; ?></li>
        <li><strong>City:</strong> <?php echo $userData['woonplaats']; ?></li>
        <!-- Add other user information here -->
    </ul>

    <a href="edit_profile.php">Edit Profile</a>

    <!-- <br>
    <a href="logout.php">Logout</a> -->
</body>
</html>
