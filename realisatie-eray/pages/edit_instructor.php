<?php
require_once __DIR__ . '/../database/db.php';
require_once __DIR__ . '/../enum/roles.php';
require_once __DIR__ . '/../models/user.php';

// Start the session
session_start();

$user = new User();

// Check if the logged-in user is an 'eigenaar' (owner)
$loggedInUserRole = $user->getLoggedInUserRole();

// Check if the user is an 'eigenaar'
if ($loggedInUserRole !== Role::EIGENAAR) {
    // Redirect to the dashboard if the user is not an 'eigenaar'
    header("Location: dashboard.php");
    exit();
}

// Check if the instructor ID is provided in the query parameters
if (!isset($_GET['id'])) {
    // Redirect to the instructors page if the instructor ID is not provided
    header("Location: instructors.php");
    exit();
}

$instructorId = $_GET['id'];
$instructorData = $user->getUserById($instructorId);

// Handle form submission for updating instructor information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $updatedData = array(
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'address' => $_POST['address'],
        'city' => $_POST['city'],
        'postal_code' => $_POST['postal_code'],
        'house_number' => $_POST['house_number'],
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'middle_name' => $_POST['middle_name']
        // Add other fields as needed
    );

    // Update instructor information in the database
    $user->updateUser($instructorId, $updatedData);

    // Redirect to the instructors page after updating
    header("Location: instructors.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Instructor</title>
    <link rel="stylesheet" type="text/css" href="../css/user.css">
</head>
<body>
    <h1>Edit Instructor: <?php echo $instructorData['gebruikersnaam']; ?></h1>

    <form method="post" action="edit_instructor.php?id=<?php echo $instructorId; ?>">
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?php echo $instructorData['gebruikersnaam']; ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $instructorData['email']; ?>" required>

        <label for="address">Address:</label>
        <input type="text" name="address" value="<?php echo $instructorData['adres']; ?>" required>

        <label for="city">City:</label>
        <input type="text" name="city" value="<?php echo $instructorData['woonplaats']; ?>" required>

        <label for="postal_code">Postal Code:</label>
        <input type="text" name="postal_code" value="<?php echo $instructorData['postcode']; ?>" required>

        <label for="house_number">House Number:</label>
        <input type="text" name="house_number" value="<?php echo $instructorData['huisnr']; ?>" required>

        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" value="<?php echo $instructorData['voornaam']; ?>" required>

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" value="<?php echo $instructorData['achternaam']; ?>" required>

        <label for="middle_name">Middle Name:</label>
        <input type="text" name="middle_name" value="<?php echo $instructorData['tussenvoegsel']; ?>">

        <!-- Add other input fields as needed -->

        <button type="submit">Save Changes</button>
    </form>

    <!-- Instructors link -->
    <br>
    <a href="instructors.php">Back to Instructors</a>
</body>
</html>
