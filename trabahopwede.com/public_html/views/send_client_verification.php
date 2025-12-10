
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Client Verification</title>
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
        <h2 class="mb-4 fw-bold">Send Client Verification</h2>
        <div class="upload-box">
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Adjust path if needed
require_once '../config/db_connection.php'; // Your DB connection

function sendClientVerificationEmail($email) {
    global $conn;

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        error_log("Email not found: $email");
        return false;
    }

    $token = bin2hex(random_bytes(16));

    // Save token to DB
    $stmt = $conn->prepare("UPDATE users SET email_token = ?, email_verified = 0 WHERE email = ?");
    $stmt->execute([$token, $email]);

    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'yourgmail@gmail.com';         // Replace this with your Gmail
        $mail->Password = 'your_app_password';           // Use Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Email Content
        $mail->setFrom('yourgmail@gmail.com', 'Trabaho PWeDe Registration');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Client Email Verification';
        $verifyUrl = "http://localhost/client_verify.php?token=$token"; // Replace with your prod domain if hosted

        $mail->Body = "
            <h2>Client Email Verification</h2>
            <p>Please click the link below to verify your email address:</p>
            <a href='$verifyUrl'>Verify Email</a>
            <p>This link will expire or become invalid once used.</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return false;
    }
}
?>

        </div>
    </div>
</body>
</html>
