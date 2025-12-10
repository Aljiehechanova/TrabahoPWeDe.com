<?php
session_start();
require_once '../config/db_connection.php';

if (!isset($_POST['user_id'], $_POST['current_status'])) {
    die("Invalid request.");
}

$user_id = $_POST['user_id'];
$current_status = (int)$_POST['current_status'];
$new_status = $current_status ? 0 : 1;

try {
    $stmt = $conn->prepare("UPDATE users SET is_hiring_enabled = ? WHERE user_id = ?");
    $stmt->execute([$new_status, $user_id]);

    $_SESSION['message'] = $new_status ? "Hiring account enabled." : "Hiring account disabled.";
    header("Location: userP.php");
    exit;
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
