<?php
session_start();

require_once 'user.php';

$user = new User();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user data
$userId = $_SESSION['user_id'];
$userData = $user->getUserById($userId);

// Fetch user's lesson packages
$userLessonPackages = $user->getUserLessonPackages($userId);
$remainingLessons = $user->calculateRemainingLessons($userId);

$lessonsData = $user->calculateRemainingLessons($userId);

// Check if the form is submitted for canceling a lesson
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_lesson_id'])) {
    $lessonIdToCancel = $_POST['cancel_lesson_id'];

    try {
        // Add your logic to cancel the lesson based on $lessonIdToCancel
        // You might want to have a function like $user->cancelLesson($userId, $lessonIdToCancel);
        // Redirect or display a success message
    } catch (Exception $e) {
        // Handle any exceptions
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
    <link rel="stylesheet" type="text/css" href="css/lespakketten.css">
    <style>
        .comment-form,
        .cancel-form {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo $userData['username']; ?>!</h1>

    <h2>Your Lesson Packages:</h2>
    <?php if (empty($userLessonPackages)): ?>
        <p>No lesson packages registered.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($userLessonPackages as $package): ?>
                <li>
                    <?php echo $package['name']; ?> - <?php echo $package['description']; ?> (<?php echo $package['lessons']; ?> lessons)
                    <form method="post" action="your_current_page.php" style="display: inline;">
                        <input type="hidden" name="cancel_lesson_id" value="<?php echo $package['id']; ?>">
                        <button type="button" onclick="showCancelForm(<?php echo $package['id']; ?>)">Cancel</button>
                    </form>
                    <button type="button" onclick="showCommentForm(<?php echo $package['id']; ?>)">Add Comment</button>

                    <!-- Form for cancellation reason -->
                    <form class="cancel-form" id="cancelForm<?php echo $package['id']; ?>" method="post" action="your_current_page.php">
                        <input type="hidden" name="cancel_lesson_id" value="<?php echo $package['id']; ?>">
                        <label for="cancellation_reason">Cancellation Reason:</label>
                        <textarea name="cancellation_reason" required></textarea>
                        <button type="submit">Submit Cancellation</button>
                    </form>

                    <!-- Form for adding comment -->
                    <form class="comment-form" id="commentForm<?php echo $package['id']; ?>" method="post" action="your_current_page.php">
                        <input type="hidden" name="comment_lesson_id" value="<?php echo $package['id']; ?>">
                        <label for="comment">Your Comment:</label>
                        <textarea name="comment" required></textarea>
                        <button type="submit">Submit Comment</button>
                    </form>

                    <!-- Add a section for displaying and managing comments -->
                    <!-- You need to implement the logic for displaying and managing comments -->
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
    <!-- Add more HTML and functionality as needed -->

    <a href="dashboard.php">Go back to Dashboard</a>
    <br>
    <a href="logout.php">Logout</a>

    <script>
        function showCancelForm(lessonId) {
            hideAllForms();
            document.getElementById('cancelForm' + lessonId).style.display = 'block';
        }

        function showCommentForm(lessonId) {
            hideAllForms();
            document.getElementById('commentForm' + lessonId).style.display = 'block';
        }

        function hideAllForms() {
            var forms = document.querySelectorAll('.cancel-form, .comment-form');
            forms.forEach(function(form) {
                form.style.display = 'none';
            });
        }
    </script>
</body>
</html>
