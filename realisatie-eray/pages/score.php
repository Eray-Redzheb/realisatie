<?php
require_once __DIR__ . '/../database/db.php';

// Function to calculate passing percentage
function calculatePassingPercentage($passed, $total) {
    return ($total > 0) ? round(($passed / $total) * 100, 2) : 0;
}

// Retrieve information from the database
$query = "SELECT SUM(exameninformatie) AS omzet, COUNT(*) AS totalLearners, SUM(geslaagd) AS passedLearners FROM gebruiker WHERE rol = :learnerRole";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':learnerRole', Role::LEERLING);  // Assuming LEERLING is the role for learners
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Perform calculations
$omzet = $result['omzet'];
$totalLearners = $result['totalLearners'];
$passedLearners = $result['passedLearners'];
$passingPercentage = calculatePassingPercentage($passedLearners, $totalLearners);
?>

<!-- HTML structure for the driving school score page -->
<main>
    <section class="content">
        <h1>Driving School Score</h1>

        <div>
            <p>Total Revenue (Omzet): <?php echo $omzet; ?></p>
            <p>Total Active Learners: <?php echo $totalLearners; ?></p>
            <p>Passing Percentage: <?php echo $passingPercentage; ?>%</p>
        </div>
    </section>
</main>

<?php
require_once 'partial/footer.php';
?>
