<?php
session_start();

// Ensure user completed Step 1
if (!isset($_SESSION['gov_id_verified'])) {
    die("❌ Please complete Step 1 first.");
}

$message = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic input sanitization
    $fullName = trim($_POST['full_name']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $businessName = trim($_POST['business_name']);
    $businessType = trim($_POST['business_type']);

    if (!$email || !$fullName || !$businessName || !$businessType) {
        $message = "<span style='color: red;'>❌ Invalid input. Please fill all fields correctly.</span>";
    } else {
        // Save to session
        $_SESSION['client_info'] = [
            'full_name' => $fullName,
            'email' => $email,
            'business_name' => $businessName,
            'business_type' => $businessType
        ];

        // Send email verification
        include 'send_client_verification_email.php';
        $success = sendClientVerificationEmail($email);

        if ($success) {
            $message = "<span style='color: green; font-weight: bold;'>✅ Verification code sent to <strong>" . htmlspecialchars($email) . "</strong>.</span><br><a href='verify.php' class='btn btn-custom mt-3'><i class='fas fa-key'></i> Enter Verification Code</a>";
        } else {
            $message = "<span style='color: red;'>❌ Failed to send verification email. Please try again later.</span>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Client Step 2: Email Verification</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    /* Full page background */
    html, body {
      height: 100%;
      margin: 0;
      background-color: #87ceeb !important; /* Sky Blue */
    }

    /* Container box */
    .upload-box {
      background-color: #003366; /* Dark blue */
      color: white;
      border-radius: 15px;
      padding: 40px 30px;
      max-width: 650px;
      margin: auto;
      box-shadow: 0 6px 25px rgba(0,0,0,0.4);
      text-align: left;
    }

    h2 {
      color: #003366;
      font-weight: bold;
    }

    label {
      font-weight: 600;
    }

    /* Buttons */
    .btn-custom {
      background: #ffc107;
      border: none;
      color: #000;
      font-weight: bold;
      border-radius: 10px;
      padding: 12px;
      transition: 0.3s ease;
      text-decoration: none;
    }
    .btn-custom:hover {
      background: #e0a800;
      transform: scale(1.02);
      color: #000;
    }

    /* Mobile */
    @media (max-width: 768px) {
      .upload-box {
        padding: 25px 20px;
      }
      h2 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>

<div class="container py-5 text-center">
  <!-- Heading -->
  <h2 class="mb-4"><i class="fas fa-envelope"></i> Client Step 2: Email Verification</h2>

  <!-- Upload Box -->
  <div class="upload-box">
      <?php if ($message): ?>
        <div class="mb-3">
          <?= $message ?>
        </div>
      <?php endif; ?>

      <?php if (!$success): ?>
      <form method="POST">
        <div class="mb-3">
          <label for="full_name" class="form-label">Full Name</label>
          <input type="text" name="full_name" id="full_name" class="form-control form-control-lg" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" id="email" class="form-control form-control-lg" required>
        </div>

        <div class="mb-3">
          <label for="business_name" class="form-label">Business Name</label>
          <input type="text" name="business_name" id="business_name" class="form-control form-control-lg" required>
        </div>

        <div class="mb-3">
          <label for="business_type" class="form-label">Business Type</label>
          <input type="text" name="business_type" id="business_type" class="form-control form-control-lg" required>
        </div>

        <button type="submit" class="btn btn-custom w-100 mt-3">
          <i class="fas fa-paper-plane"></i> Send Verification Email
        </button>
      </form>
      <?php endif; ?>
  </div>
</div>

</body>
</html>
