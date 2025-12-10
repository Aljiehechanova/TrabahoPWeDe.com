<?php
require_once '../config/database.php';

if (!isset($_GET['token'])) {
    die("Invalid token.");
}

$token = $_GET['token'];
$stmt = $conn->prepare("SELECT * FROM client WHERE email_token = ? AND email_verified = 0");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Invalid or already verified token.");
}

$data = $result->fetch_assoc();

$fullName = $data['first_name'] . ' ' . $data['middle_initial'] . ' ' . $data['last_name'] . ' ' . $data['suffix'];
$fullDisability = $data['disability'] . ' - ' . $data['disability_subcategory'];
$skills = $data['skills'] ?? '';
$resume = $data['resume'] ?? '';

$insert = $conn->prepare("INSERT INTO users (
    user_type, fullname, email, password, disability, disability_subcategory, birthday, highest_education, experience_status,
    experience_years, experience_field, location, preferred_work, skills, resume, qr_id, img, pwd_id_number, pwd_id_type,
    gov_id_front, gov_id_back, first_name, middle_initial, last_name, suffix, facial_image_path, facial_match_score,
    facial_verified, work_status, work_years, work_field, session_token, created_at, verification_token, is_verified
) VALUES (
    'job_seeker', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), '', '1'
)");

$insert->bind_param(
    "ssssssssssssssssssssssssssssiis",
    $fullName, $data['email'], $data['password'], $data['disability'], $data['disability_subcategory'],
    $data['birthday'], $data['highest_education'], $data['experience_status'], $data['experience_years'],
    $data['experience_field'], $data['location'], $data['preferred_work'], $skills, $resume,
    $data['qr_id_number'], $data['pwd_id_image_path'], $data['pwd_id_number'], $data['gov_id_type'],
    $data['gov_id_front'], $data['gov_id_back'], $data['first_name'], $data['middle_initial'],
    $data['last_name'], $data['suffix'], $data['facial_image_path'], $data['facial_match_score'],
    $data['facial_verified'], $data['work_status'], $data['work_years'], $data['work_field'],
    $data['session_token']
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Email Verification</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    html, body {
      height: 100%;
      margin: 0;
      background-color: #87ceeb !important; /* Sky Blue */
    }

    /* Progress Bar at Top */
    .progressbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 0;
      padding: 0;
      list-style-type: none;
      border-bottom: 4px solid #ccc;
      background: #f8f9fa;
    }

    .progressbar li {
      flex: 1;
      text-align: center;
      padding: 15px 0;
      font-weight: bold;
      background: #e9ecef; /* Inactive */
      color: #000;
      border-right: 1px solid #ccc;
    }

    .progressbar li:last-child {
      border-right: none;
    }

    .progressbar li.active {
      background: #ffc107; /* Yellow */
      color: #000;
    }

    .progressbar li.completed {
      background: #003366; /* Dark Blue */
      color: #fff;
    }

    .verify-box {
      background-color: #003366; /* Dark blue */
      color: white;
      border-radius: 15px;
      padding: 40px 30px;
      max-width: 700px;
      margin: 60px auto;
      box-shadow: 0 6px 25px rgba(0,0,0,0.4);
      text-align: center;
    }

    h2 {
      color: #ffc107;
      font-weight: bold;
    }

    .btn-custom {
      background: #ffc107;
      border: none;
      color: #000;
      font-weight: bold;
      border-radius: 10px;
      padding: 12px 20px;
      transition: 0.3s ease;
    }
    .btn-custom:hover {
      background: #e0a800;
      transform: scale(1.02);
    }
  </style>
</head>
<body>

<!-- Progress Bar -->
<ul class="progressbar">
  <li class="completed">Step 1</li>
  <li class="completed">Step 2</li>
  <li class="completed">Step 3</li>
  <li class="completed">Step 4</li>
  <li class="active">Step 5</li>
</ul>

<!-- Content Box -->
<div class="verify-box">
  <h2><i class="fas fa-envelope-circle-check"></i> Email Verification</h2>

  <div class="mt-4">
    <?php
    if ($insert->execute()) {
        $update = $conn->prepare("UPDATE client SET email_verified = 1 WHERE id = ?");
        $update->bind_param("i", $data['id']);
        $update->execute();
        echo '<div class="alert alert-success fw-bold">✅ Email verified and user registered successfully.</div>';
    } else {
        echo '<div class="alert alert-danger fw-bold">❌ Error: ' . $insert->error . '</div>';
    }

    $stmt->close();
    $conn->close();
    ?>
  </div>

  <a href="../login.php" class="btn btn-custom mt-3"><i class="fas fa-sign-in-alt"></i> Go to Login</a>
</div>

</body>
</html>
