<?php
session_start();

require_once __DIR__ . '/../models/user.php';
require_once  __DIR__ . '/../enum/roles.php';

$user = new User();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// Retrieve user data
$userId = $_SESSION['user_id'];
$userData = $user->getUserById($userId);
$userRole = $user->roleOfLoggedInUser();

// Fetch user's lesson packages
$userLessonPackages = $user->getUserLessonPackages($userId, $userRole);
$remainingLessons = $user->calculateRemainingLessons($userId);

$lessonsData = $user->calculateRemainingLessons($userId);

// Check if the form is submitted for canceling a lesson
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_lesson_id'])) {
    $lessonIdToCancel = $_POST['cancel_lesson_id'];

    try {
        // Redirect or display a success message
    } catch (Exception $e) {
        // Handle any exceptions
        $error_message = $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_sickness'])) {
    // Handle sickness form submission
    $lessonId = $_POST['ziekmelding_lespakket_id'];
    $startDate = $_POST['sickness_start_date'];
    $endDate = $_POST['sickness_end_date'];
    $explanation = $_POST['sickness_explanation'];
 
    try {
        $user->addSicknessNotification($lessonId, $startDate, $endDate, $explanation, $userId);
       // Redirect or show success message
       header("Location: lespakketten.php");
       exit();
    } catch (Exception $e) {
       // Handle the error
       $error_message = $e->getMessage();
    }
 }


// Check if the form is submitted for editing a lesson package
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_lesson_id'])) {
    $editLessonId = $_POST['edit_lesson_id'];
    $editedName = $_POST['edited_name'];
    $editedDescription = $_POST['edited_description'];
    $editedQuantity = $_POST['edited_quantity'];
    $editedPrice = $_POST['edited_price'];

    try {
        // Update the lesson package in the database
        $user->updateLessonPackage($editLessonId, $editedName, $editedDescription, $editedQuantity, $editedPrice);
        // Redirect to refresh the page or handle success
        header("Location: lespakketten.php");
        exit();
    } catch (Exception $e) {
        // Handle the error as needed
        $error_message = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lesson Packages</title>
    <link rel="stylesheet" type="text/css" href="../css/lespakketten.css">
    <style>
        .comment-form,
        .cancel-form,
        .edit-form {
            display: none;
        }
    </style>
</head>
<body>
<h1>Welcome <?php echo $userData['gebruikersnaam'] ?></h1>

<h2><?php echo $userRole === Role::KLANT ? 'Your Lesson Packages:' : 'All Lessons:';?></h2>

<?php if (empty($userLessonPackages)): ?>
    <p>No lesson packages registered.</p>
<?php else: ?>
    <ul>
        <?php foreach ($userLessonPackages as $package): ?>
            <li>
                <?php if ($userRole !== Role::KLANT): ?>
                    <?php
                    // Display username and email for 'instructuur' or 'eigenaar'
                    echo '<strong>' . $package['gebruikersnaam'] . '</strong> - ' . $package['email'] . '<br>';
                    ?>
                <?php endif; ?>

                <?php echo $package['naam']; ?> - <?php echo $package['omschrijving']; ?> (<?php echo $package['aantal']; ?> lessons)

                <form method="post" action="../backend/lespakket/cancel.php" style="display: inline;">
                    <br>
                    <br>
                    <input type="hidden" name="cancel_lesson_id" value="<?php echo $package['lespakket_id']; ?>">
                    <button type="button" onclick="showCancelForm(<?php echo $package['lespakket_id']; ?>)">Cancel</button>
                </form>

                <!-- --- -->

                <?php if ($userRole === Role::INSTRUCTUUR): ?>
                 <form method="post" action="lespakketten.php" style="display: inline;">
                  <input type="hidden" name="ziekmelding_lespakket_id" value="<?php echo $package['lespakket_id']; ?>">
                    <button type="button" onclick="showSicknessForm(<?php echo $package['lespakket_id']; ?>)">Sickness Notification</button>

            <div id="sicknessForm<?php echo $package['lespakket_id']; ?>" style="display: none;">
            <label for="sickness_start_date">Start Date:</label>
            <input type="date" name="sickness_start_date" required>
            <label for="sickness_end_date">End Date:</label>
            <input type="date" name="sickness_end_date" required>
            <label for="sickness_explanation">Explanation:</label>
            <textarea name="sickness_explanation" required></textarea>
            <button type="submit" name="submit_sickness">Submit Sickness Notification</button>
            </div>
                </form>
                    <?php endif; ?>


                <!-- --- -->

                <form method="post" action="lespakketten.php" style="display: inline;">
                <?php if ($userRole === Role::INSTRUCTUUR || $userRole === Role::EIGENAAR): ?>
                    <br>
                    <br>
                    <input type="hidden" name="edit_lesson_id" value="<?php echo $package['lespakket_id']; ?>">
                    <button type="button" onclick="toggleEditForm(<?php echo $package['lespakket_id']; ?>)">Edit</button>
        
              <div id="editForm<?php echo $package['lespakket_id']; ?>" style="display: none;">
                <label for="edited_name">Edited Name:</label>
                <input type="text" name="edited_name" value="<?php echo $package['naam']; ?>" required>
                <label for="edited_description">Edited Description:</label>
            <textarea name="edited_description" required><?php echo $package['omschrijving']; ?></textarea>
            
            <!-- Add new input fields for lesson quantity and price -->
            <label for="edited_quantity">Edited Quantity:</label>
            <input type="number" name="edited_quantity" value="<?php echo $package['aantal']; ?>" required>

            <label for="edited_price">Edited Price:</label>
            <input type="text" name="edited_price" value="<?php echo $package['prijs']; ?>" required>

            <button type="submit">Save Changes</button>
        </div>
    <?php endif; ?>
</form>

                <button type="button" onclick="showCommentForm(<?php echo $package['lespakket_id']; ?>)">Add Comment</button>

                <!-- Form for cancellation reason -->
                <form class="cancel-form" id="cancelForm<?php echo $package['lespakket_id']; ?>" method="post" action="../backend/lespakket/cancel.php">
                    <br>
                    <br>
                    <input type="hidden" name="cancel_lesson_id" value="<?php echo $package['lespakket_id']; ?>">
                    <label for="cancellation_reason">Cancellation Reason:</label>
                    <textarea name="cancellation_reason" required></textarea>
                    <button type="submit">Submit Cancellation</button>
                </form>

                <!-- Form for editing lesson package -->
                <form class="edit-form" id="editForm<?php echo $package['lespakket_id']; ?>" method="post" action="lespakketten.php">
                    <br>
                    <br>
                    <input type="hidden" name="edit_lesson_id" value="<?php echo $package['lespakket_id']; ?>">
                    <label for="edited_name">Edited Name:</label>
                    <input type="text" name="edited_name" value="<?php echo $package['naam']; ?>" required>
                    <label for="edited_description">Edited Description:</label>
                    <textarea name="edited_description" required><?php echo $package['omschrijving']; ?></textarea>
                    <button type="submit">Save Changes</button>
                </form>

                <!-- Form for adding comment -->
                <form class="comment-form" id="commentForm<?php echo $package['lespakket_id']; ?>" method="post" action="lesson_packages.php">
                    <br>
                    <br>
                    <input type="hidden" name="comment_lesson_id" value="<?php echo $package['lespakket_id']; ?>">
                    <label for="comment">Your Comment:</label>
                    <textarea name="comment" required></textarea>
                    <button type="submit">Submit Comment</button>
                </form>

                <div>
                    <!-- Display comments here -->
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

    <p>Remaining Lessons: <?php echo $lessonsData['remaining']; ?></p>
<?php endif; ?>

<a href="new_lespakket.php">Nieuw Lespakket</a>
<br>

<a href="dashboard.php">Go back to Dashboard</a>
<br>
<a href="../pages/auth/logout.php">Logout</a>

<script>
    function showCancelForm(lessonId) {
        hideAllForms();
        document.getElementById('cancelForm' + lessonId).style.display = 'block';
    }

    function toggleEditForm(lessonId) {
        var editForm = document.getElementById('editForm' + lessonId);
        if (editForm.style.display === 'none' || editForm.style.display === '') {
            editForm.style.display = 'block';
        } else {
            editForm.style.display = 'none';
        }
    }

    function showSicknessForm(lespakketId) {
        var sicknessForm = document.getElementById('sicknessForm' + lespakketId);
        if (sicknessForm.style.display === 'none' || sicknessForm.style.display === '') {
            sicknessForm.style.display = 'block';
        } else {
            sicknessForm.style.display = 'none';
        }
    }

    function showCommentForm(lessonId) {
        hideAllForms();
        document.getElementById('commentForm' + lessonId).style.display = 'block';
    }

    function hideAllForms() {
        var forms = document.querySelectorAll('.cancel-form, .edit-form, .comment-form');
        forms.forEach(function (form) {
            form.style.display = 'none';
        });
    }
</script>

</body>
</html>
