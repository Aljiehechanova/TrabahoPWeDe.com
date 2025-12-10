<?php
require '../config/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $sender = filter_var(trim($_POST['sender_email']), FILTER_SANITIZE_EMAIL);
    $receiver = filter_var(trim($_POST['receiver_email']), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Optional: Basic validation
    if (!filter_var($receiver, FILTER_VALIDATE_EMAIL)) {
        header("Location: clientM.php?error=invalid_email");
        exit;
    }

    try {
        $stmt = $conn->prepare("INSERT INTO messages (sender_email, receiver_email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$sender, $receiver, $subject, $message]);

        header("Location: clientM.php?success=1");
        exit;
    } catch (PDOException $e) {
        // Log error in real-world usage
        header("Location: clientM.php?error=database");
        exit;
    }
}
?>
