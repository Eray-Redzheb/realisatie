<?php
require_once __DIR__ . '/../models/user.php';

// Start the session
session_start();

$user = new User();

// Check if the logged-in user is an 'eigenaar' (owner)
$loggedInUserRole = $user->getLoggedInUserRole();

// Check if the user is an 'eigenaar'
if ($loggedInUserRole !== Role::EIGENAAR) {
    // Redirect to another page or display an error message
    header("Location: access_denied.php");
    exit();
}

// Continue with the rest of your code

?>

<!-- portal_eigenaar.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal</title>
    <link rel="stylesheet" type="text/css" href="../css/portal.css">
</head>
<body>
    <ul>
        <li><a href="instructors.php">Instructors</a></li>
        <li><a href="clients.php">Clients</a></li>
    </ul>
</body>
</html>
