<?php
session_start();
require __DIR__ . '/../config/db_connection.php';

$error_message = null;
$userData = [];
$firstName = $lastName = $suffix = '';

if (!isset($_SESSION['session_token'])) {
    // Session expired, stop execution
    die("⚠️ Your session has expired. Please start again.");
}

$sessionToken = $_SESSION['session_token'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName   = $_POST['first_name'] ?? '';
    $lastName    = $_POST['last_name'] ?? '';
    $suffix      = $_POST['suffix'] ?? '';
    $email       = $_POST['email'] ?? '';
    $education   = $_POST['education'] ?? '';
    $location    = $_POST['location'] ?? '';
    $disability  = $_POST['disability'] ?? '';
    $subcategory = $_POST['disability_subcategory'] ?? '';
    $password    = $_POST['password'] ?? '';

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Save separate disability + subcategory (safer than merging them)
    $_SESSION['selected_disability'] = $disability;

    $stmt = $conn->prepare("UPDATE qr_data SET 
        first_name = ?, 
        last_name = ?, 
        suffix = ?, 
        email = ?, 
        password = ?, 
        highest_education = ?, 
        location = ?, 
        disability = ?, 
        disability_subcategory = ?,
        updated_at = NOW()
        WHERE session_token = ?");

    $stmt->execute([
        $firstName, $lastName, $suffix,
        $email, $hashedPassword, $education,
        $location, $disability, $subcategory,
        $sessionToken
    ]);

    header("Location: registration_step2.php");
    exit;
}

// Prefill user data from DB
$stmt = $conn->prepare("SELECT first_name, last_name, suffix FROM qr_data WHERE session_token = ?");
$stmt->execute([$sessionToken]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

$firstName = $userData['first_name'] ?? '';
$lastName  = $userData['last_name'] ?? '';
$suffix    = $userData['suffix'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Step 4: Personal Information</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #87CEEB; /* Sky blue background */
    }
    .upload-box {
      background-color: #002147; /* Dark Blue */
      color: #fff;
      border-radius: 20px;
      padding: 40px 30px;
      max-width: 700px;
      margin: 0 auto;
      box-shadow: 0 10px 35px rgba(0,0,0,0.4);
      text-align: left;
    }
    .form-label {
      color: #FFD700; /* Yellow */
      font-weight: 600;
      font-size: 16px;
    }
    .form-control, 
    .form-select {
      border-radius: 10px;
      padding: 12px;
      font-size: 16px;
    }
    .btn-primary {
      background-color: #FFD700 !important;
      border: none;
      color: #002147 !important;
      font-weight: bold;
      font-size: 16px;
      border-radius: 10px;
      padding: 12px 20px;
      transition: background 0.3s, transform 0.1s;
    }
    .btn-primary:hover {
      background-color: #e6c200 !important;
      color: #002147 !important;
      transform: translateY(-2px);
    }
    .btn-secondary {
      background-color: #6c757d;
      border: none;
      font-size: 16px;
      border-radius: 10px;
      padding: 12px 20px;
    }
    .step-progress {
      display: flex;
      justify-content: center;
      gap: 15px;
      flex-wrap: wrap;
      margin: 20px 0 40px;
      padding: 0;
      list-style: none;
      background: transparent !important;
      box-shadow: none !important;
    }
    .step-progress li {
      padding: 10px 20px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      color: #6c757d;
      background: #f0f0f0;
      white-space: nowrap;
    }
    .step-progress .active {
      background: #ffc107;
      color: #000;
    }
  </style>
  <script>
    const disabilityMap = {
      "Visual": ["Partial", "Full"],
      "Hearing": ["Partial", "Full"],
      "Speech": ["Partial", "Full"],
      "Physical": ["Upper Limb", "Lower Limb"]
    };
    function updateSubcategories() {
      const disabilitySelect = document.getElementById('disability');
      const subSelect = document.getElementById('disability_subcategory');
      if (!disabilitySelect || !subSelect) return; // stop safely if not found
    
      const general = disabilitySelect.value;
      subSelect.innerHTML = '<option value="">Select subcategory</option>';
      if (disabilityMap[general]) {
        disabilityMap[general].forEach(sub => {
          const opt = document.createElement('option');
          opt.value = sub;
          opt.textContent = sub;
          subSelect.appendChild(opt);
        });
      }
    }
    
    // only run after DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
      updateSubcategories();
    });
  </script>
</head>
<body class="p-4">

  <!-- Step Navigation -->
  <ol class="step-progress">
    <li class="inactive">1. Upload QR</li>
    <li class="inactive">2. Upload PWD ID</li>
    <li class="inactive">3. Facial Verification</li>
    <li class="active">4. Personal Info</li>
    <li class="inactive">5. Preferred Work</li>
    <li class="inactive">6. Resume Form</li>
    <li class="inactive">7. Final Confirmation</li>
  </ol>

  <div class="container py-5 text-center">
    <h2 class="mb-4 fw-bold">Step 4: Personal Information</h2>

    <!-- Error message -->
    <?php if ($error_message): ?>
      <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>

    <!-- Upload Box -->
    <?php if (!$error_message): ?>
    <div class="upload-box">
      <form method="POST">
        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label">First Name</label>
            <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($firstName) ?>" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Last Name</label>
            <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($lastName) ?>" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Suffix</label>
            <input type="text" class="form-control" name="suffix" value="<?= htmlspecialchars($suffix) ?>">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Create Password</label>
          <input type="password" class="form-control" name="password" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Highest Educational Attainment</label>
          <select class="form-select" name="education" required>
            <option value="">Select</option>
            <option value="Elementary">Elementary</option>
            <option value="High School">High School</option>
            <option value="Senior High School">Senior High School</option>
            <option value="College">College</option>
            <option value="Vocational">Vocational</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Barangay in Bacolod</label>
          <select class="form-select" name="location" required>
            <option value="">Select Barangay</option>
            <?php
              $barangays = [
                'Alangilan', 'Alijis', 'Banago', 'Bata', 'Cabug', 'Estefania',
                'Felisa', 'Granada', 'Handumanan', 'Mandalagan', 'Mansilingan',
                'Montevista', 'Pahanocoy', 'Punta Taytay', 'Singcang-Airport',
                'Sum-ag', 'Taculing', 'Tangub', 'Villamonte', 'Vista Alegre'
              ];
              for ($i = 1; $i <= 61; $i++) {
                  $barangays[] = "Barangay $i";
              }
              foreach ($barangays as $brgy) {
                  echo "<option value='$brgy'>$brgy</option>";
              }
            ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Disability</label>
          <select class="form-select" id="disability" name="disability" onchange="updateSubcategories()" required>
            <option value="">Select disability</option>
            <option value="Visual">Visual Impairment</option>
            <option value="Hearing">Hearing Impairment</option>
            <option value="Speech">Speech Impairment</option>
            <option value="Physical">Physical Impairment</option>
            <option value="Others">Others</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Specific Subcategory</label>
          <select class="form-select" id="disability_subcategory" name="disability_subcategory" required>
            <option value="">Select subcategory</option>
          </select>
        </div>

        <div class="d-flex justify-content-between">
          <a href="facial_verification.php" class="btn btn-secondary">Previous</a>
          <button type="submit" class="btn btn-primary">Next</button>
        </div>
      </form>
    </div>
    <?php endif; ?>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', updateSubcategories);
  </script>
</body>
</html>