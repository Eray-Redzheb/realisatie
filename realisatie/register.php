<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" type="text/css" href="css/user.css">
</head>
<body>
    <?php
    require_once 'user.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $postalCode = $_POST['postal_code'];
        $houseNumber = $_POST['house_number'];
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $middleName = $_POST['middle_name'];

        $user = new User();
        $user->register($username, $password, $email, $address, $city, $postalCode, $houseNumber, $firstName, $lastName, $middleName);

        // Redirect to login.php after successful registration
        header("Location: login.php");
        exit();
    }
    ?>

<form method="post" action="register.php">
    <label for="username">Username:</label>
    <input type="text" name="username" required>

    <label for="password">Password:</label>
    <input type="password" name="password" required>

    <label for="email">Email:</label>
    <input type="email" name="email" required>

    <label for="address">Address:</label>
    <input type="text" name="address" required>

    <label for="city">City:</label>
    <input type="text" name="city" required>

    <label for="postal_code">Postal Code:</label>
    <input type="text" name="postal_code" required>

    <label for="house_number">House Number:</label>
    <input type="text" name="house_number" required>

    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" required>

    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" required>

    <label for="middle_name">Middle Name:</label>
    <input type="text" name="middle_name">

    <button type="submit">Register</button>
</form>

</body>
</html>