<?php
require_once 'db.php';

class User extends Database {
    public function isUsernameTaken($username) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    }

    public function isEmailTaken($email) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    public function register($username, $password, $email, $address, $city, $postalCode, $houseNumber, $firstName, $lastName, $middleName) {
        // Check if username or email is already taken
        if ($this->isUsernameTaken($username)) {
            throw new Exception("Username is already taken. Please choose a different username.");
        }

        if ($this->isEmailTaken($email)) {
            throw new Exception("Email is already registered. Please use a different email.");
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert the user into the database
        $stmt = $this->conn->prepare("
            INSERT INTO users 
            (username, password, email, address, city, postal_code, house_number, first_name, last_name, middle_name) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$username, $hashedPassword, $email, $address, $city, $postalCode, $houseNumber, $firstName, $lastName, $middleName]);
    }

    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
    
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            return $user['id'];
        }
    
        // Login failed
        return false;
    }

    public function getUserById($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

// Update Function for User

    public function updateUser($userId, $updatedData) {
        // Update user information in the database
        $stmt = $this->conn->prepare("
            UPDATE users 
            SET 
                address = ?, 
                city = ?, 
                postal_code = ?, 
                house_number = ?, 
                first_name = ?, 
                last_name = ?, 
                middle_name = ? 
            WHERE id = ?
        ");
        $stmt->execute([
            $updatedData['address'],
            $updatedData['city'],
            $updatedData['postal_code'],
            $updatedData['house_number'],
            $updatedData['first_name'],
            $updatedData['last_name'],
            $updatedData['middle_name'],
            $userId
        ]);
    }

    public function updateUserCredentials($userId, $username, $password) {
        // Update the username and password in the database
        $stmt = $this->conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->execute([$username, $hashedPassword, $userId]);
    }

// Lessons Packages Function

public function registerLessonPackage($userId, $lessonPackageId) {
    // Check if the user already has this lesson package
    $stmt = $this->conn->prepare("SELECT COUNT(*) FROM user_lesson_packages WHERE user_id = ? AND lesson_package_id = ?");
    $stmt->execute([$userId, $lessonPackageId]);

    if ($stmt->fetchColumn() > 0) {
        throw new Exception("You already have this lesson package.");
    }

    // Insert the lesson package for the user
    $stmt = $this->conn->prepare("INSERT INTO user_lesson_packages (user_id, lesson_package_id) VALUES (?, ?)");
    $stmt->execute([$userId, $lessonPackageId]);
}

public function getAvailableLessonPackages() {
    $stmt = $this->conn->prepare("SELECT * FROM lesson_packages");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getUserLessonPackages($userId) {
    $stmt = $this->conn->prepare("SELECT lp.* FROM lesson_packages lp
                                  JOIN user_lesson_packages ulp ON lp.id = ulp.lesson_package_id
                                  WHERE ulp.user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function calculateRemainingLessons($userId) {
    // Get the total lessons from registered packages
    $totalRegisteredLessons = $this->getTotalRegisteredLessons($userId);

    // Get the total lessons from your database or any other source
    $totalLessons = $this->getTotalLessons(); // Implement this method

    // Calculate remaining lessons based on your business logic
    $remainingLessons = $totalLessons - $totalRegisteredLessons;

    // Return an array containing both remaining and total lessons
    return [
        'remaining' => $remainingLessons > 0 ? $remainingLessons : 0,
        'total' => $totalLessons,
    ];
}


public function getTotalLessons() {
    $stmt = $this->conn->prepare("SELECT SUM(lessons) FROM lesson_packages");
    $stmt->execute();
    $totalLessons = $stmt->fetchColumn();

    return $totalLessons ? $totalLessons : 0;
}

private function getTotalRegisteredLessons($userId) {
    $stmt = $this->conn->prepare("SELECT SUM(lp.lessons) FROM lesson_packages lp
                                  JOIN user_lesson_packages ulp ON lp.id = ulp.lesson_package_id
                                  WHERE ulp.user_id = ?");
    $stmt->execute([$userId]);
    $totalRegisteredLessons = $stmt->fetchColumn();

    return $totalRegisteredLessons ? $totalRegisteredLessons : 0;
}

// Overzicht Pagina

public function getUserLessons($userId) {
    $stmt = $this->conn->prepare("SELECT ulp.*, lp.name AS package_name, lp.description AS package_description
                                  FROM user_lesson_packages ulp
                                  JOIN lesson_packages lp ON ulp.lesson_package_id = lp.id
                                  WHERE ulp.user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



}
?>
