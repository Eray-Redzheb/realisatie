<?php
session_start();

require_once 'user.php';

$user = new User();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user data
$userId = $_SESSION['user_id'];
$userData = $user->getUserById($userId);

// Fetch user's lesson packages
$userLessonPackages = $user->getUserLessonPackages($userId);
$remainingLessons = $user->calculateRemainingLessons($userId);

$lessonsData = $user->calculateRemainingLessons($userId);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lesson Packages</title>
    <link rel="stylesheet" type="text/css" href="css/lespakketten.css">
</head>
<body>
    <h1>Welcome, <?php echo $userData['username']; ?>!</h1>

    <h2>Your Lesson Packages:</h2>
    <?php if (empty($userLessonPackages)): ?>
        <p>No lesson packages registered.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($userLessonPackages as $package): ?>
                <li><?php echo $package['name']; ?> - <?php echo $package['description']; ?> (<?php echo $package['lessons']; ?> lessons)</li>
            <?php endforeach; ?>
        </ul>
        <p>Remaining Lessons: <?php echo $lessonsData['remaining']; ?></p>
    <?php endif; ?>

    <a href="new_lespakket.php">Nieuw Lespakket</a>
    <br>
    <!-- Add more HTML and functionality as needed -->

    <a href="dashboard.php">Go back to Dashboard</a>
    <br>
    <a href="logout.php">Logout</a>
</body>
</html>
