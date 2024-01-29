<?php
session_start();

require_once 'user.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user = new User();
$userId = $_SESSION['user_id'];
$userData = $user->getUserById($userId);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lesson_package_id'])) {
    $lessonPackageId = $_POST['lesson_package_id'];

    try {
        // Register the lesson package for the user
        $user->registerLessonPackage($userId, $lessonPackageId);
        header("Location: new_lespakket.php"); // Redirect to refresh the page
        exit();
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Fetch available lesson packages
$availablePackages = $user->getAvailableLessonPackages();

// Fetch user's lesson packages and remaining lessons
$userLessonPackages = $user->getUserLessonPackages($userId);
$remainingLessons = $user->calculateRemainingLessons($userId);

$lessonsData = $user->calculateRemainingLessons($userId);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Lesson Package</title>
    <link rel="stylesheet" type="text/css" href="css/new_lespakket.css">
</head>
<body>
    <h1>Welcome, <?php echo $userData['username']; ?>!</h1>

    <?php if (isset($error_message)): ?>
        <p>Error: <?php echo $error_message; ?></p>
    <?php endif; ?>

    <h2>Available Lesson Packages:</h2>
    <form method="post" action="new_lespakket.php">
        <select name="lesson_package_id" required>
            <option value="" disabled selected>Select a lesson package</option>
            <?php foreach ($availablePackages as $package): ?>
                <option value="<?php echo $package['id']; ?>"><?php echo $package['name']; ?> - <?php echo $package['description']; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Save</button>
    </form>

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

    <br>
    <a href="lespakketten.php">Ga Terug</a>
    <br>
    <a href="logout.php">Logout</a>
</body>
</html>
