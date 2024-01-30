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

// Get the logged-in user ID
$gebruikerId = $_SESSION['user_id'];

// Get sickness reports for the logged-in user
$sicknessReports = $user->getSicknessReports($gebruikerId);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sickness Reports</title>
</head>
<body>
    <h1>Sickness Reports</h1>

    <?php if (empty($sicknessReports)): ?>
        <p>No sickness reports found.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($sicknessReports as $report): ?>
                <li>
                    <br><strong>Sickness Report ID:</strong> <?php echo $report['ziekmelding_id']; ?><br><br>
                    <strong>Start Date:</strong> <?php echo $report['van']; ?><br>
                    <strong>End Date:</strong> <?php echo $report['tot']; ?><br>
                    <strong>Explanation:</strong> <?php echo $report['toelichting']; ?><br>
                    <strong>User ID:</strong> <?php echo $report['gebruiker_id']; ?><br>
                    <strong>Lesson ID:</strong> <?php echo $report['lespakket_id']; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <a href="dashboard.php">Go back to Dashboard</a>
    <br>
    <a href="../pages/auth/logout.php">Logout</a>
</body>
</html>
