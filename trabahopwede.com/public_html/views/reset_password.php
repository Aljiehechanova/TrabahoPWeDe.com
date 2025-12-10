<?php
require '../config/db_connection.php';
require '../models/UserModel.php';
require '../controllers/UserController.php';

$userModel = new UserModel($conn);
$userController = new UserController($conn);

$message = "";
$token = $_GET['token'] ?? '';
$showForm = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($password !== $confirm) {
        $message = "❌ Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $message = "❌ Password must be at least 8 characters.";
    } else {
        $resetRequest = $userModel->getResetByToken($token);

        if ($resetRequest) {
            if (strtotime($resetRequest['expires_at']) >= time()) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $userModel->updatePassword($resetRequest['email'], $hashedPassword);
                $userModel->deleteResetToken($resetRequest['email']);

                $message = "✅ Password updated. You may now <a href='login.php'>login</a>.";
                $showForm = false;
            } else {
                $message = "❌ This password reset link has expired.";
                $showForm = false;
            }
        } else {
            $message = "❌ Invalid or used token.";
            $showForm = false;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password - Trabaho PWeDe</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      padding: 20px;
    }

    .container {
      background: white;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      max-width: 400px;
      width: 100%;
    }

    h2 {
      text-align: center;
      color: #1877f2;
    }

    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: #1877f2;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .message {
      margin-top: 15px;
      text-align: center;
      color: #d8000c;
    }

    .message.success {
      color: green;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Reset Password</h2>

    <?php if (!empty($message)): ?>
      <div class="message <?= strpos($message, '✅') !== false ? 'success' : '' ?>">
        <?= $message ?>
      </div>
    <?php endif; ?>

    <?php if ($showForm): ?>
    <form method="POST" action="">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
      <input type="password" name="password" placeholder="New Password" required>
      <input type="password" name="confirm" placeholder="Confirm Password" required>
      <button type="submit">Reset Password</button>
    </form>
    <?php endif; ?>
  </div>
</body>
</html>
