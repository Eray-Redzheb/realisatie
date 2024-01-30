<?php
session_start();

require_once __DIR__ . '/../../models/user.php';

$user = new User();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Check if the form is submitted for canceling a lesson
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_lesson_id'])) {
    $lessonIdToCancel = $_POST['cancel_lesson_id'];
    $cancellationReason = $_POST['cancellation_reason'];
    $userId = $_SESSION['user_id'];

    try {
        // Add your logic to cancel the lesson based on $lessonIdToCancel
        // and update the gebruiker_lespakket table
        $user->cancelLesson($userId, $lessonIdToCancel, $cancellationReason);

        // Redirect or display a success message
        header("Location: ../../pages/lespakketten.php");
        exit();
    } catch (Exception $e) {
        // Handle any exceptions
        $error_message = $e->getMessage();
    }
}
?>
