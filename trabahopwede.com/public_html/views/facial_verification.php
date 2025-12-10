<?php
session_start();
require '../config/db_connection.php';

if (!isset($_SESSION['session_token'])) {
    echo "⚠️ Session expired. Please start again.";
    exit;
}

$sessionToken = $_SESSION['session_token'];
$statusMessage = "";

// Fetch existing image if any
$stmt = $conn->prepare("SELECT facial_image_path FROM qr_data WHERE session_token = ?");
$stmt->execute([$sessionToken]);
$currentImage = $stmt->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['facial_image']) && $_FILES['facial_image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($_FILES['facial_image']['type'], $allowedTypes)) {
            $statusMessage = "Invalid file type. Please upload JPG or PNG.";
        } else {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/facial_images/';
            $webDir    = '/uploads/facial_images/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = uniqid() . "." . pathinfo($_FILES['facial_image']['name'], PATHINFO_EXTENSION);
            $filePath = $uploadDir . $fileName;
            $webPath  = $webDir . $fileName;

            move_uploaded_file($_FILES['facial_image']['tmp_name'], $filePath);

            $stmt = $conn->prepare("UPDATE qr_data SET facial_image_path = ?, updated_at = NOW() WHERE session_token = ?");
            if ($stmt->execute([$webPath, $sessionToken])) {
                header("Location: registration_step1.php");
                exit;
            } else {
                $statusMessage = "Database update failed.";
            }
        }
    } else {
        $statusMessage = "Facial image upload failed.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Step 3: Facial Verification</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    html, body {
        height: 100%;
        margin: 0;
        background-color: #87ceeb !important;
    }
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* Step Progress */
    .step-progress {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
        margin: 20px 0 40px;
        padding: 0;
        list-style: none;
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

    /* Upload Box */
    .upload-section {
        max-width: 650px;
        margin: 0 auto 50px;
        text-align: center;
    }
    .upload-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #000;
        margin-bottom: 20px;
    }
    .upload-box {
        background-color: #002147;
        color: #fff;
        border-radius: 20px;
        padding: 40px 30px;
        max-width: 500px;
        margin: 0 auto;
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.4);
        text-align: center;
    }
    .upload-box label {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        display: block;
        color: #FFD700;
    }
    .custom-file-upload {
        display: block;
        width: 100%;
        box-sizing: border-box;
        background: #ffffff;
        border: 2px dashed #FFD700;
        border-radius: 10px;
        padding: 12px 16px;
        text-align: center;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        color: #002147;
        margin-bottom: 20px;
        transition: border-color 0.3s, background 0.3s;
    }
    .custom-file-upload:hover {
        border-color: #fff;
        background: #f9f9f9;
    }
    .custom-file-upload input[type="file"] {
        display: none;
    }
    .upload-box .btn-primary {
        background-color: #FFD700 !important;
        color: #002147 !important;
        font-weight: bold;
        font-size: 18px;
        border: none;
        border-radius: 10px;
        padding: 12px 16px;
        width: 100%;
        transition: background 0.3s, transform 0.1s;
    }
    .upload-box .btn-primary:hover {
        background-color: #e6c200 !important;
        transform: translateY(-2px);
    }
    #preview, .existing-photo {
        max-width: 200px;
        border-radius: 10px;
        margin-top: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    @media (max-width: 768px) {
        .upload-box { padding: 20px; }
        .upload-title { font-size: 1.5rem; }
    }
  </style>
</head>
<body>

  <!-- Steps -->
  <ol class="step-progress">
    <li>1. Upload QR</li>
    <li>2. Upload PWD ID</li>
    <li class="active">3. Facial Verification</li>
    <li>4. Personal Info</li>
    <li>5. Preferred Work</li>
    <li>6. Resume Form</li>
    <li>7. Confirmation</li>
  </ol>

  <!-- Upload Section -->
  <div class="upload-section">
    <div class="upload-title">Step 3: Facial Verification</div>

    <div class="upload-box">
      <?php if (!empty($statusMessage)): ?>
        <div class="alert alert-warning"><?php echo htmlspecialchars($statusMessage); ?></div>
      <?php endif; ?>

      <?php if (!empty($currentImage)): ?>
        <div class="text-center mb-3">
          <p class="text-light">Your current uploaded selfie:</p>
          <img src="<?php echo htmlspecialchars($currentImage); ?>" alt="Current Selfie" class="existing-photo">
        </div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data" class="mb-3">
        <div class="mb-3 text-start">
          <label for="facial_image" class="form-label fs-5 text-light">
            Upload a Selfie (same face as your PWD ID)
          </label>
          <label class="custom-file-upload w-100">
            <input type="file" name="facial_image" id="facial_image" accept="image/*" required>
            <i class="fas fa-upload"></i> Click here to choose your selfie
          </label>
        </div>
        <div class="text-center mb-3">
          <img id="preview" src="" alt="Preview" style="display:none;">
        </div>
        <button type="submit" class="btn btn-primary btn-lg">
          <i class="fas fa-user-check"></i> Verify Face
        </button>
      </form>
    </div>
  </div>

  <script>
  document.getElementById('facial_image').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
          const reader = new FileReader();
          reader.onload = function(ev) {
              const preview = document.getElementById('preview');
              preview.src = ev.target.result;
              preview.style.display = 'block';
          }
          reader.readAsDataURL(file);
      }
  });
  </script>

</body>
</html>
