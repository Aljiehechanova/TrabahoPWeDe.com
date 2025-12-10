<?php
session_start();
require_once '../config/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['qr_image']) || $_FILES['qr_image']['error'] !== UPLOAD_ERR_OK) {
        die('<span style="color: red;">❌ No image uploaded.</span>');
    }

    $tmp_name = $_FILES['qr_image']['tmp_name'];

    // Send image to QR decoding API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.qrserver.com/v1/read-qr-code/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'file' => new CURLFile($tmp_name)
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (!empty($data[0]['symbol'][0]['data'])) {
        $qr_url = $data[0]['symbol'][0]['data'];

        // Extract QR ID
        parse_str(parse_url($qr_url, PHP_URL_QUERY), $queryParams);
        $qr_id = $queryParams['content'] ?? null;

        if (!$qr_id) {
            die('<span style="color: red;">❌ QR content missing.</span>');
        }

        // Check if QR already exists
        $stmt = $conn->prepare("SELECT * FROM qr_data WHERE qr_id_number = ?");
        $stmt->execute([$qr_id]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['qr_id_number'] = $qr_id;
            $_SESSION['session_token'] = $row['session_token'];
            header("Location: upload_pwd_id.php");
            exit;
        }

        // Scrape QR details
        $html = file_get_contents($qr_url);
        preg_match('/<img[^>]+src="([^"]+)"/', $html, $imgMatch);
        preg_match('/<h1[^>]*>(.*?)<\/h1>/', $html, $nameMatch);

        $qr_full_name = $nameMatch[1] ?? "Unknown";
        $qr_img = $imgMatch[1] ?? "";

        // Insert into DB
        $session_token = bin2hex(random_bytes(16));
        $insert = $conn->prepare("INSERT INTO qr_data (qr_raw_content, qr_id_number, pwd_full_name, session_token) VALUES (?, ?, ?, ?)");
        $insert->execute([$qr_url, $qr_id, $qr_full_name, $session_token]);

        $_SESSION['qr_id_number'] = $qr_id;
        $_SESSION['session_token'] = $session_token;
        header("Location: upload_pwd_id.php");
        exit;
    } else {
        echo '<span style="color: red;">❌ Failed to decode QR.</span>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload QR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="../assets/css/home.css">
  <link rel="stylesheet" href="../assets/css/access.css">

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
    }

    h2 {
      color: #003366;
    }

    /* Step Progress */
    .step-progress {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      margin-bottom: 30px;
      padding: 0;
      list-style: none;
    }
    .step-progress li {
      flex: 1;
      text-align: center;
      border-radius: 8px;
      margin: 5px;
      padding: 10px;
      font-size: 13px;
      font-weight: 600;
    }
    .step-progress .active {
      background: #ffc107; /* Yellow highlight */
      color: #000;
    }
    .step-progress .inactive {
      background: #f0f0f0;
      color: #6c757d;
    }

    /* Input & Options */
    .form-control {
      border-radius: 10px;
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
      .step-progress li {
        font-size: 11px;
        padding: 8px;
      }
    }
  </style>
</head>

<body>
<?php include 'accessibility.php'; ?>
<main id="main-content">

<div class="container py-5 text-center">
  <!-- Steps -->
  <ol class="step-progress">
    <li class="active">1. Upload QR</li>
    <li class="inactive">2. Upload PWD ID</li>
    <li class="inactive">3. Facial Verification</li>
    <li class="inactive">4. Personal Info</li>
    <li class="inactive">5. Preferred Work</li>
    <li class="inactive">6. Resume Form</li>
    <li class="inactive">7. Confirmation</li>
  </ol>

  <!-- Upload Box -->
  <h2 class="mb-4 fw-bold">Step 1: Upload Bacolod QR</h2>
  <div class="upload-box">
      <?php if (!empty($message)) echo "<div class='alert alert-danger'>$message</div>"; ?>

      <form method="POST" enctype="multipart/form-data" class="mb-3">
          <div class="mb-3 text-start">
              <label for="qr_image" class="form-label fs-5 fw-semibold">Upload QR Image</label>
              <input type="file" class="form-control form-control-lg" name="qr_image" id="qr_image" accept="image/*" required>
          </div>
          <button type="submit" class="btn btn-custom w-100 mt-2">
              <i class="fas fa-qrcode"></i> Upload and Scan
          </button>
      </form>

      <p class="mt-3">Or scan on mobile: <a href="mobile_qr_generator.php" class="text-warning fw-bold">Generate Mobile QR</a></p>
  </div>
</div>

<script src="../assets/js/accessibility.js"></script>
<script src="../assets/js/home.js" defer></script>
</body>
</html>

</main>