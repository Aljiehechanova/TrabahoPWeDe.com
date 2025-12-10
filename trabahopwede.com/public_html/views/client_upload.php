<?php
session_start();
require_once '../config/db_connection.php';

$message = "";
$uploadSuccess = false;

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $govIdType = $_POST['gov_id_type'];
    $frontImage = $_FILES['gov_id_front'];
    $backImage = $_FILES['gov_id_back'];

    // Create directory if it doesn't exist
    $uploadDir = "../uploads/gov_ids/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Upload both images
    $frontFileName = time() . "_front_" . basename($frontImage['name']);
    $backFileName = time() . "_back_" . basename($backImage['name']);
    $frontPath = $uploadDir . $frontFileName;
    $backPath = $uploadDir . $backFileName;

    if (move_uploaded_file($frontImage['tmp_name'], $frontPath) && move_uploaded_file($backImage['tmp_name'], $backPath)) {
        // Save to database
        $stmt = $conn->prepare("UPDATE users SET gov_id_type = ?, gov_id_front = ?, gov_id_back = ? WHERE user_id = ?");
        $stmt->execute([$govIdType, $frontFileName, $backFileName, $_SESSION['user_id']]);

        $uploadSuccess = true;
        $message = "Government ID uploaded successfully.";
        header("Location: facial_verification.php"); // proceed to next step
        exit;
    } else {
        $message = "Failed to upload both files.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload Government ID</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    /* Full page background fix */
    html, body {
        height: 100%;
        margin: 0;
        background-color: #87ceeb !important; /* Sky Blue */
    }

    /* Container */
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

    /* Buttons */
    .btn-custom {
      background: #ffc107;
      border: none;
      color: #000;
      font-weight: bold;
      border-radius: 10px;
      padding: 14px;
      transition: 0.3s ease;
    }
    .btn-custom:hover {
      background: #e0a800;
      transform: scale(1.02);
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
  <h2 class="mb-4 fw-bold"><i class="fas fa-id-card"></i> Upload Government ID</h2>

  <!-- Upload Box -->
  <div class="upload-box">
      <?php if ($message): ?>
        <div class="alert alert-<?php echo $uploadSuccess ? 'success' : 'danger'; ?>">
          <?= $message ?>
        </div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="gov_id_type" class="form-label fs-5 fw-semibold">Type of Government ID</label>
          <select name="gov_id_type" id="gov_id_type" class="form-select" required>
            <option value="" disabled selected>Select an ID</option>
            <option value="PhilHealth">PhilHealth</option>
            <option value="SSS">SSS</option>
            <option value="UMID">UMID</option>
            <option value="Passport">Passport</option>
            <option value="Driver's License">Driver's License</option>
            <option value="National ID">National ID</option>
            <option value="Others">Others</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="gov_id_front" class="form-label fs-5 fw-semibold">Upload Front Photo</label>
          <input type="file" name="gov_id_front" id="gov_id_front" class="form-control form-control-lg" accept="image/*" required>
        </div>

        <div class="mb-3">
          <label for="gov_id_back" class="form-label fs-5 fw-semibold">Upload Back Photo</label>
          <input type="file" name="gov_id_back" id="gov_id_back" class="form-control form-control-lg" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-custom w-100 mt-3">
          <i class="fas fa-arrow-right"></i> Next Step: Facial Verification
        </button>
      </form>
  </div>
</div>

</body>
</html>
