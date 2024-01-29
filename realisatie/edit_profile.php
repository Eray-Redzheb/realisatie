<?php
session_start(); // Start the session to access user information

if (!isset($_SESSION['user_id'])) {
    // Redirect to login if the user is not logged in
    header("Location: login.php");
    exit();
}

require_once 'user.php';

$user = new User();
$userId = $_SESSION['user_id'];
$userData = $user->getUserById($userId);

// Handle form submission for updating user information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $updatedData = array(
        'address' => $_POST['address'],
        'city' => $_POST['city'],
        'postal_code' => $_POST['postal_code'],
        'house_number' => $_POST['house_number'],
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'middle_name' => $_POST['middle_name']
    );

    // Update user information in the database
    $user->updateUser($userId, $updatedData);

    // Redirect to the dashboard after updating
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" type="text/css" href="css/user.css">
</head>
<body>
    <h1>Edit Your Profile, <?php echo $userData['username']; ?>!</h1>

    <form method="post" action="edit_profile.php">
        <label for="address">Address:</label>
        <input type="text" name="address" value="<?php echo $userData['address']; ?>" required>

        <label for="city">City:</label>
        <input type="text" name="city" value="<?php echo $userData['city']; ?>" required>

        <label for="postal_code">Postal Code:</label>
        <input type="text" name="postal_code" value="<?php echo $userData['postal_code']; ?>" required>

        <label for="house_number">House Number:</label>
        <input type="text" name="house_number" value="<?php echo $userData['house_number']; ?>" required>

        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" value="<?php echo $userData['first_name']; ?>" required>

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" value="<?php echo $userData['last_name']; ?>" required>

        <label for="middle_name">Middle Name:</label>
        <input type="text" name="middle_name" value="<?php echo $userData['middle_name']; ?>">

        <button type="submit">Save Changes</button>
    </form>

    <!-- Dashboard link -->
    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
