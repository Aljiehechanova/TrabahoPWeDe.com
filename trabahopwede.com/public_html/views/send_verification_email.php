
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Verification Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .upload-box {
            border: 2px solid #ccc;
            border-radius: 12px;
            padding: 30px;
            max-width: 700px;
            margin: 0 auto;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container py-5 text-center">
        <h2 class="mb-4 fw-bold">Send Verification Email</h2>
        <div class="upload-box">
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
require_once '../config/db_connection.php';
session_start();

$sessionToken = $_SESSION['session_token'] ?? '';
if (!$sessionToken) {
    die("Session expired.");
}

// Get email from qr_data
$stmt = $conn->prepare("SELECT email, qr_id_number FROM qr_data WHERE session_token = ?");
$stmt->execute([$sessionToken]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data || empty($data['email'])) {
    die("Email not found. Please provide your email.");
}

$email = $data['email'];
$qr_id = $data['qr_id_number'];
$token = bin2hex(random_bytes(16));

// Save token to qr_data
$stmt = $conn->prepare("UPDATE qr_data SET email_token = ?, email_verified = 0 WHERE session_token = ?");
$stmt->execute([$token, $sessionToken]);

// Send email
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'trabahopwede@gmail.com';
    $mail->Password = 'evtfdycdecvxyydb';  // Use app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom('trabahopwede@gmail.com', 'Trabaho PWeDe');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Verify Your Email';
    $mail->Body = "
        <h3>Confirm Your Email</h3>
        <p>Click the link below to verify:</p>
        <a href='https://trabahopwede.com/views/verify.php?token=$token'>Verify My Email</a>
    ";

    $mail->send();
    echo "Verification email sent!";
} catch (Exception $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}
?>
        </div>
    </div>
</body>
</html>
