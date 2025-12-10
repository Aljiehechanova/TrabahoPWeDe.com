<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../config/db_connection.php';
require '../models/UserModel.php';
require '../controllers/UserController.php';

// If using Composer (preferred)
require '../vendor/autoload.php';

// Initialize models/controllers
$userModel = new UserModel($conn);
$userController = new UserController($conn);

$message = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "❌ Invalid email format.";
    } else {
        $user = $userModel->getUserByEmail($email);

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', time() + 3600); // valid for 1 hour

            // Save token in database
            $userController->storePasswordResetToken($email, $token, $expires);

            // Reset link
            $resetLink = "https://trabahopwede.com/views/reset_password.php?token=$token";

            // Send the reset link using PHPMailer
            $mail = new PHPMailer(true);

            try {

                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'trabahopwede@gmail.com'; // Your Gmail
                $mail->Password = 'evtfdycdecvxyydb';      // Your App Password (not Gmail password)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                // Recipients
                $mail->setFrom('trabahopwede@gmail.com', 'Trabaho PWeDe');
                $mail->addAddress($email); // Send to user
                $mail->addReplyTo('trabahopwede@gmail.com');

                // Email content
                $mail->isHTML(true);
                $mail->Subject = '🔐 Password Reset Request';
                $mail->Body = "
                    <p>Hello,</p>
                    <p>We received a request to reset your password. Click the link below to continue:</p>
                    <p><a href='$resetLink'>$resetLink</a></p>
                    <p>This link is valid for 1 hour.</p>
                    <p>If you didn’t request this, just ignore this message.</p>
                    <br><p>— Trabaho PWeDe</p>
                ";

                $mail->send();
                $message = "✅ A reset link has been sent to your email address.";
                $success = true;
            } catch (Exception $e) {
                $message = "❌ Email could not be sent. Error: {$mail->ErrorInfo}";
            }
        } else {
            $message = "❌ No user found with that email.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password - Trabaho PWeDe</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 2rem;
      background: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      background: #fff;
      padding: 2rem 3rem;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      max-width: 500px;
      width: 100%;
      text-align: center;
    }

    h2 {
      color: #1877f2;
      margin-bottom: 1rem;
    }

    input[type="email"] {
      width: 100%;
      padding: 10px;
      margin: 1rem 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      padding: 10px 20px;
      background: #1877f2;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .message {
      margin-top: 1rem;
      color: <?= $success ? 'green' : 'red' ?>;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Forgot Password</h2>
    <p>Please enter your registered email address. We’ll send you a reset link.</p>

    <form method="POST" action="">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit">Send Reset Link</button>
    </form>

    <?php if ($message): ?>
      <div class="message"><?= $message ?></div>
    <?php endif; ?>
  </div>
</body>
</html>
