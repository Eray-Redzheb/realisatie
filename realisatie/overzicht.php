<?php
session_start();

require_once 'user.php';

// Check of de gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user = new User();
$userId = $_SESSION['user_id'];

// Haal de gebruikerslessen op
$userLessons = $user->getUserLessons($userId);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lessenoverzicht</title>
    <link rel="stylesheet" type="text/css" href="css/lessenoverzicht.css">
</head>
<body>
    <h1>Lessenoverzicht</h1>

    <?php if (empty($userLessons)): ?>
        <p>Geen lessen gevonden.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Instructeur/Leerling</th>
                    <th>Datum en Tijd</th>
                    <th>Ophaaladres</th>
                    <th>Lesdoel</th>
                    <th>Resterend Lessen</th>
                    <th>Opmerkingen</th>
                    <th>Te Behandelen Onderwerpen</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($userLessons as $lesson): ?>
        <tr>
        <td><?php echo isset($lesson['instructor_or_student']) ? $lesson['instructor_or_student'] : ''; ?></td>
        <td><?php echo isset($lesson['date_and_time']) ? $lesson['date_and_time'] : ''; ?></td>
        <td><?php echo isset($lesson['pickup_address']) ? $lesson['pickup_address'] : ''; ?></td>
        <td><?php echo isset($lesson['lesson_goal']) ? $lesson['lesson_goal'] : ''; ?></td>
        <td><?php echo isset($lesson['remaining_lessons']) ? $lesson['remaining_lessons'] : ''; ?></td>
        <td><?php echo isset($lesson['comments']) ? $lesson['comments'] : ''; ?></td>
        <td><?php echo isset($lesson['topics_to_cover']) ? $lesson['topics_to_cover'] : ''; ?></td>
        </tr>
        <?php endforeach; ?>

            </tbody>
        </table>
    <?php endif; ?>

    <br>
    <a href="dashboard.php">Terug naar Dashboard</a>
    <br>
    <a href="logout.php">Uitloggen</a>
</body>
</html>
