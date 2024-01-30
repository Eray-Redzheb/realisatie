<?php
require_once __DIR__ . '/../database/db.php';
require_once __DIR__ . '/../enum/roles.php';
require_once __DIR__ . '/../models/user.php';

// Start the session
session_start();

$userList = new UserList();
$user = new User();

// Check if the logged-in user is an 'eigenaar' (owner)
$loggedInUserRole = $user->getLoggedInUserRole();

if ($loggedInUserRole == Role::EIGENAAR) {
    // Get all instructors if the user is an 'eigenaar'
    $instructors = $userList->getAllInstructors();
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>All Instructors</title>
        <link rel="stylesheet" type="text/css" href="../css/lijst.css">
    </head>
    <body>
        <h1>All Instructors</h1>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Action</th> <!-- New column for action buttons -->
            </tr>

            <?php foreach ($instructors as $instructor): ?>
                <tr>
                    <td><?php echo $instructor['id']; ?></td>
                    <td><?php echo $instructor['gebruikersnaam']; ?></td>
                    <td><?php echo $instructor['email']; ?></td>
                    <!-- Action button for editing instructor -->
                    <td>
                        <a href="edit_instructor.php?id=<?php echo $instructor['id']; ?>">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>

        <ul>
        <li><a href="rapport.php">Reports</a></li>
        </ul>

    </body>
    </html>

    <?php
} else {
    // Redirect to the dashboard if the user is not an 'eigenaar'
    header("Location: dashboard.php");
    exit();
}
?>
