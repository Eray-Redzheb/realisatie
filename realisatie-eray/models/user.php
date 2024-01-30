<?php
require_once __DIR__ . '/../database/db.php';
require_once __DIR__ . '/../enum/roles.php';

class User extends Database
{
    public function isUsernameTaken( $username )
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM gebruiker WHERE gebruikersnaam = ?");
        $stmt->execute([ $username ]);
        return $stmt->fetchColumn() > 0;
    }

    public function isEmailTaken( $email )
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM gebruiker WHERE email = ?");
        $stmt->execute([ $email ]);
        return $stmt->fetchColumn() > 0;
    }

    public function register( $username, $password, $email, $address, $city, $postalCode, $houseNumber, $firstName, $lastName, $middleName, $role, $examInfo, $active, $passed )
    {
        // Check if username or email is already taken
        if ( $this->isUsernameTaken($username) ) {
            throw new Exception("Username is already taken. Please choose a different username.");
        }

        if ( $this->isEmailTaken($email) ) {
            throw new Exception("Email is already registered. Please use a different email.");
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert the user into the database
        $stmt = $this->conn->prepare("
            INSERT INTO gebruiker 
            (gebruikersnaam, wachtwoord, email, adres, woonplaats, postcode, huisnr, voornaam, achternaam, tussenvoegsel, rol, exameninformatie, actief, geslaagd) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([ $username, $hashedPassword, $email, $address, $city, $postalCode, $houseNumber, $firstName, $lastName, $middleName, $role, $examInfo, $active, $passed ]);
    }

    public function login( $username, $password )
    {
        $stmt = $this->conn->prepare("SELECT id, wachtwoord FROM gebruiker WHERE gebruikersnaam = ?");
        $stmt->execute([ $username ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ( $user && password_verify($password, $user['wachtwoord']) ) {
            // Login successful
            return $user['id'];
        }

        // Login failed
        return false;
    }

    public function getUserById( $userId )
    {
        $stmt = $this->conn->prepare("SELECT * FROM gebruiker WHERE id = ?");
        $stmt->execute([ $userId ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Checks the currently logged-in user's role in the database. and assigns a role
     *
     * @return Role|void
     */
    public function roleOfLoggedInUser()
    {
        if ( !isset($_SESSION['user_id']) ) {
            // Redirect to log in if the user is not logged in
            header("Location: login.php");
            exit();
        }

        $userData = $this->getUserById($_SESSION['user_id']);

        return match ( $userData['rol'] ) {
            0 => Role::KLANT,
            1 => Role::INSTRUCTUUR,
            2 => Role::EIGENAAR,
        };
    }


    // Update Function for User
    public function updateUser( $userId, $updatedData )
    {
        // Update user information in the database
        $stmt = $this->conn->prepare("
            UPDATE gebruiker 
            SET 
                adres = ?, 
                woonplaats = ?, 
                postcode = ?, 
                huisnr = ?, 
                voornaam = ?, 
                achternaam = ?, 
                tussenvoegsel = ? 
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

    public function updateUserCredentials( $userId, $username, $password )
    {
        // Update the username and password in the database
        $stmt = $this->conn->prepare("UPDATE gebruiker SET gebruikersnaam = ?, wachtwoord = ? WHERE id = ?");
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->execute([ $username, $hashedPassword, $userId ]);
    }

    // Lessons Packages Function
    public function registerLessonPackage( $userId, $lessonPackageId )
    {
        // Check if the user already has this lesson package
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM gebruiker_lespakket WHERE gebruiker_id = ? AND lespakket_id = ?");
        $stmt->execute([ $userId, $lessonPackageId ]);

        if ( $stmt->fetchColumn() > 0 ) {
            throw new Exception("You already have this lesson package.");
        }

        // Insert the lesson package for the user
        $stmt = $this->conn->prepare("
            INSERT INTO gebruiker_lespakket (gebruiker_id, lespakket_id, created_at) VALUES (?, ?, CURRENT_TIMESTAMP)
        ");
        $stmt->execute([ $userId, $lessonPackageId ]);
    }

    public function getAvailableLessonPackages()
    {
        $stmt = $this->conn->prepare("SELECT * FROM lespakket");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserLessonPackages($userId, $role)
    {
        if ($role === Role::KLANT) {
            // For 'klant' role, select packages related to the user
            $stmt = $this->conn->prepare("SELECT lp.* FROM lespakket lp
                                      JOIN gebruiker_lespakket ulp ON lp.lespakket_id = ulp.lespakket_id
                                      WHERE ulp.gebruiker_id = ? AND ulp.cancelled = 0");
            $stmt->execute([$userId]);
        } elseif ($role === Role::INSTRUCTUUR || $role === Role::EIGENAAR) {
            // For 'instructuur' or 'eigenaar' role, select user and associated lespakket
            $stmt = $this->conn->prepare("SELECT g.*, lp.* FROM gebruiker g
                                      JOIN gebruiker_lespakket ulp ON g.id = ulp.gebruiker_id
                                      JOIN lespakket lp ON lp.lespakket_id = ulp.lespakket_id
                                      WHERE ulp.cancelled = 0");
            $stmt->execute();
        } else {
            // Handle unknown roles, you can throw an exception or return an empty array
            throw new Exception("Unknown role encountered.");
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cancelLesson($userId, $lessonId, $cancellationReason)
    {
        $stmt = $this->conn->prepare("
            UPDATE gebruiker_lespakket
            SET cancelled = TRUE, cancellation_reason = ?
            WHERE gebruiker_id = ? AND lespakket_id = ?
        ");
        $stmt->execute([$cancellationReason, $userId, $lessonId]);

    }

    // In the User class

    public function updateLessonPackage($lessonId, $name, $description, $quantity, $price)
    {
        try {
    
            $query = "UPDATE lespakket SET naam = :name, omschrijving = :description, aantal = :quantity, prijs = :price WHERE lespakket_id = :lessonId";
    
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $stmt->bindParam(':lessonId', $lessonId, PDO::PARAM_INT);
            $stmt->execute();
    
    
            return true; // Return true on success
        } catch (Exception $e) {
            return false; // Return false on failure
        }
    }

    public function addSicknessNotification($lessonId, $startDate, $endDate, $explanation, $gebruikerId) {
        $sql = "INSERT INTO ziekmelding (van, tot, toelichting, gebruiker_id, lespakket_id) VALUES (:startDate, :endDate, :explanation, :gebruikerId, :lessonId)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
        $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
        $stmt->bindParam(':explanation', $explanation, PDO::PARAM_STR);
        $stmt->bindParam(':gebruikerId', $gebruikerId, PDO::PARAM_INT);
        $stmt->bindParam(':lessonId', $lessonId, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function getSicknessReports($gebruikerId) {
        $sql = "SELECT ziekmelding_id, van, tot, toelichting, gebruiker_id, lespakket_id FROM ziekmelding WHERE gebruiker_id = :gebruikerId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':gebruikerId', $gebruikerId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
    
    

    public function calculateRemainingLessons( $userId )
    {
        // Get the total lessons from registered packages
        $totalRegisteredLessons = $this->getTotalRegisteredLessons($userId);

        // Get the total lessons from your database or any other source
        $totalLessons = $this->getTotalLessons();

        // Calculate remaining lessons
        $remainingLessons = $totalLessons - $totalRegisteredLessons;

        // Return an array containing both remaining and total lessons
        return [
            'remaining' => $remainingLessons > 0 ? $remainingLessons : 0,
            'total' => $totalLessons,
        ];
    }

    public function getTotalLessons()
    {
        $stmt = $this->conn->prepare("SELECT SUM(aantal) FROM lespakket");
        $stmt->execute();
        $totalLessons = $stmt->fetchColumn();

        return $totalLessons ? $totalLessons : 0;
    }

    private function getTotalRegisteredLessons( $userId )
    {
        $stmt = $this->conn->prepare("SELECT SUM(lp.aantal) FROM lespakket lp
                                  JOIN gebruiker_lespakket ulp ON lp.lespakket_id = ulp.lespakket_id
                                  WHERE ulp.gebruiker_id = ?");
        $stmt->execute([ $userId ]);
        $totalRegisteredLessons = $stmt->fetchColumn();

        return $totalRegisteredLessons ? $totalRegisteredLessons : 0;
    }

    public function getUserLessons( $userId )
    {
        $stmt = $this->conn->prepare("SELECT ulp.*, lp.naam AS package_name, lp.omschrijving AS package_description
                                  FROM gebruiker_lespakket ulp
                                  JOIN lespakket lp ON ulp.lespakket_id = lp.lespakket_id
                                  WHERE ulp.gebruiker_id = ?");
        $stmt->execute([ $userId ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLoggedInUserRole() {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        $userData = $this->getUserById($_SESSION['user_id']);

        return match ($userData['rol']) {
            0 => Role::KLANT,
            1 => Role::INSTRUCTUUR,
            2 => Role::EIGENAAR,
            default => null, // Handle unknown roles
        };
    }
}

// Function for instructor list and client list

class UserList extends Database
{
    public function getAllClients()
    {
        $stmt = $this->conn->prepare("SELECT * FROM gebruiker WHERE rol = :role");
        $stmt->bindValue(':role', Role::KLANT, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllInstructors()
    {
        $role = Role::INSTRUCTUUR;  // Assign the constant to a variable
        $stmt = $this->conn->prepare("SELECT * FROM gebruiker WHERE rol = :role");
        $stmt->bindValue(':role', $role, PDO::PARAM_INT); 
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


?>
