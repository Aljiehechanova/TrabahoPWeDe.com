<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../config/db_connection.php';

if (!isset($_SESSION['session_token'])) {
    echo "❌ Session not found. Please start registration from Step 1.";
    exit;
}

$sessionToken = $_SESSION['session_token'];

// Directories
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/pwd_ids/';
$webDir    = '/uploads/pwd_ids/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$statusMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pwd_id_image'])) {
    $file = $_FILES['pwd_id_image'];
    $fileName = basename($file['name']);
    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tif', 'tiff', 'webp', 'jfif'];

    if (!in_array($extension, $validExtensions)) {
        $statusMessage = "❌ Invalid file extension. Please upload a valid image format.";
    } else {
        // Handle jfif conversion
        if ($extension === 'jfif') {
            $image = imagecreatefromjpeg($file['tmp_name']);
            $newFilePath = $uploadDir . time() . '_converted.jpg';
            imagejpeg($image, $newFilePath, 100);
            imagedestroy($image);
            $serverPath = $newFilePath;
            $webPath = $webDir . basename($newFilePath);
        } else {
            $serverPath = $uploadDir . time() . '_pwd_id.' . $extension;
            $webPath    = $webDir . basename($serverPath);
            move_uploaded_file($file['tmp_name'], $serverPath);
        }

        // OCR
        $apiKey = 'helloworld'; // Replace with your OCR.space API key
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.ocr.space/parse/image',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'apikey' => $apiKey,
                'language' => 'eng',
                'isOverlayRequired' => 'false',
                'file' => new CURLFile(realpath($serverPath))
            ],
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            die("❌ CURL Error: " . curl_error($curl));
        }
        curl_close($curl);

        $result = json_decode($response, true);

        if (!isset($result['ParsedResults'][0]['ParsedText'])) {
            die("❌ OCR response invalid or empty:<br><pre>" . print_r($result, true) . "</pre>");
        }

        $parsedText = $result['ParsedResults'][0]['ParsedText'];

        // Default values
        $fullName = $pwdIdNumber = $disability = $birthday = $location = "";
        $firstName = $lastName = $middleInitial = $suffix = "";

        // Parse name
        if (preg_match('/([A-Z]+, [A-Z ]+)/', $parsedText, $matches)) {
            $fullName = trim($matches[1]);
            if (preg_match('/([A-Z]+), ([A-Z]+)(?: ([A-Z]) )?(JR|SR|III)?/i', $fullName, $parts)) {
                $lastName = trim($parts[1]);
                $firstName = trim($parts[2]);
                $middleInitial = $parts[3] ?? '';
                $suffix = strtoupper(trim($parts[4] ?? ''));
            }
        }

        // Parse ID number
        if (preg_match('/\d{2}-\d{4}-\d{3}-\d{7}/', $parsedText, $matches)) {
            $pwdIdNumber = trim($matches[0]);
        }

        // Disability
        if (preg_match('/(DISABILITY.*?)\n/i', $parsedText, $matches)) {
            $disability = trim($matches[1]);
        }

        // Birthday
        if (preg_match('/\b[A-Z]{3} \d{2}, \d{4}\b/', $parsedText, $matches)) {
            $birthday = date('Y-m-d', strtotime($matches[0]));
        }

        // Location
        if (preg_match('/\b[A-Z][a-z]+(?: [A-Z][a-z]+)*, Bacolod City\b/', $parsedText, $matches)) {
            $rawLocation = trim($matches[0]);
            $location = trim(str_ireplace('Bacolod City', '', $rawLocation));
        }

        // Update DB
        $stmt = $conn->prepare("UPDATE qr_data 
            SET pwd_id_image_path = ?, pwd_id_number = ?, pwd_full_name = ?, 
                first_name = ?, middle_initial = ?, last_name = ?, suffix = ?, 
                disability = ?, birthday = ?, location = ?, updated_at = NOW() 
            WHERE session_token = ?");
        $stmt->execute([
            $webPath, $pwdIdNumber, $fullName, 
            $firstName, $middleInitial, $lastName, $suffix, 
            $disability, $birthday, $location, $sessionToken
        ]);

        header("Location: facial_verification.php");
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $statusMessage = "❌ No image uploaded.";
}
?>

<link rel="stylesheet" href="../assets/css/accessibility.css">
<script src="../assets/js/accessibility.js"></script>
<div id="accessibility-panel" class="draggable" onclick="togglePanel()">
  <img src="../assets/images/accessibility.png" alt="PWD tools">
  <div id="access-content" style="display: none;">
    <h3 style="color: black;">PWD Support Tools</h3>
    <button onclick="toggleGrayscale()"><i class="fas fa-adjust"></i> Grayscale</button>
    <button onclick="toggleContrast()"><i class="fas fa-low-vision"></i> High Contrast</button>
    <button onclick="toggleReadableFont()"><i class="fas fa-font"></i> Readable Font</button>
    <div id="zoom-controls">
      <button onclick="setZoom('150')">Zoom 150%</button>
      <button onclick="setZoom('200')">Zoom 200%</button>
      <button onclick="setZoom('300')">Zoom 300%</button>
      <button onclick="setZoom('default')">Reset Zoom</button>
    </div>
    <button id="toggle-tts">TTS: OFF (Click to turn ON)</button>
    <button onclick="resetView()"><i class="fas fa-undo"></i> Reset</button>
  </div>
</div>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload PWD ID</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/access.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            background-color: #87ceeb !important; /* Sky Blue */
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .upload-box {
            border: none;
            border-radius: 16px;
            padding: 30px;
            max-width: 600px;
            margin: 0 auto;
            background-color: #002147; /* Dark Blue */
            color: #fff;
            box-shadow: 0 8px 25px rgba(0,0,0,0.35);
        }

        .btn-primary {
            background-color: #FFD700 !important;
            color: #000 !important;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            width: 100%;
            transition: background 0.3s;
        }

        .btn-primary:hover {
            background-color: #e6c200 !important;
            color: #000 !important;
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

        /* Responsive */
        @media (max-width: 768px) {
            .upload-box {
                padding: 20px;
                max-width: 95%;
            }
        }
    </style>
</head>
<body>
<?php include 'accessibility.php'; ?>
<main id="main-content">

<div id="zoom-container">
<div class="container py-5 text-center">
  <!-- Steps -->
  <ol class="step-progress">
    <li class="inactive">1. Upload QR</li>
    <li class="active">2. Upload PWD ID</li>
    <li class="inactive">3. Facial Verification</li>
    <li class="inactive">4. Personal Info</li>
    <li class="inactive">5. Preferred Work</li>
    <li class="inactive">6. Resume Form</li>
    <li class="inactive">7. Confirmation</li>
  </ol>

<style>
.progress-steps ol {
  display: flex;
  justify-content: space-between;
  padding: 0;
  margin: 0;
  list-style: none;
  width: 100%;
}

.progress-steps ol li {
  flex: 1; /* make each step equal width */
  text-align: center;
  margin: 0 5px;
}

.progress-steps ol li div {
  padding: 10px;
  border-radius: 5px;
  background: #e9ecef;
  color: #000;
  font-weight: 600;
}

.progress-steps ol li.active div {
  background: #FFD700; /* yellow highlight */
  color: #000;
}
</style>

    <div class="container py-5 text-center">
        <h2 class="mb-4 fw-bold text-dark">Step 2: Upload PWD ID</h2>
        <div class="upload-box">
            <?php if (!empty($statusMessage)): ?>
                <div class="alert alert-warning"><?php echo $statusMessage; ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="mb-3">
                <div class="mb-3 text-start">
                    <label for="pwd_id_image" class="form-label fs-5">Upload Image of PWD ID</label>
                    <input type="file" class="form-control form-control-lg" name="pwd_id_image" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary btn-lg mt-2">
                    <i class="fas fa-id-card"></i> Upload and Continue
                </button>
            </form>
        </div>
    </div>
</div>
<script src="../assets/js/accessibility-tools.js"></script>
</body>
</html>

</main>