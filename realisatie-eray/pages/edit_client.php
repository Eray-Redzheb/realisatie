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

// Check if the client ID is provided in the query parameters
if (!isset($_GET['id'])) {
    // Redirect to the clients page if the client ID is not provided
    header("Location: clients.php");
    exit();
}

$clientId = $_GET['id'];
$clientData = $user->getUserById($clientId);

// Handle form submission for updating client information
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

    // Update client information in the database
    $user->updateUser($clientId, $updatedData);

    // Redirect to the clients page after updating
    header("Location: clients.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client</title>
    <link rel="stylesheet" type="text/css" href="../css/user.css">
</head>
<body>
    <h1>Edit Client: <?php echo $clientData['gebruikersnaam']; ?></h1>

    <form method="post" action="edit_client.php?id=<?php echo $clientId; ?>">
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?php echo $clientData['gebruikersnaam']; ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $clientData['email']; ?>" required>

        <label for="address">Address:</label>
        <input type="text" name="address" value="<?php echo $clientData['adres']; ?>" required>

        <label for="city">City:</label>
        <input type="text" name="city" value="<?php echo $clientData['woonplaats']; ?>" required>

        <label for="postal_code">Postal Code:</label>
        <input type="text" name="postal_code" value="<?php echo $clientData['postcode']; ?>" required>

        <label for="house_number">House Number:</label>
        <input type="text" name="house_number" value="<?php echo $clientData['huisnr']; ?>" required>

        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" value="<?php echo $clientData['voornaam']; ?>" required>

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" value="<?php echo $clientData['achternaam']; ?>" required>

        <label for="middle_name">Middle Name:</label>
        <input type="text" name="middle_name" value="<?php echo $clientData['tussenvoegsel']; ?>">

        <!-- Add other input fields as needed -->

        <button type="submit">Save Changes</button>
    </form>

    <!-- Clients link -->
    <br>
    <a href="clients.php">Back to Clients</a>
</body>
</html>
