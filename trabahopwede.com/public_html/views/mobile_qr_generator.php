
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/phpqrcode/qrlib.php';

$token = bin2hex(random_bytes(16));
$url = "https://trabahopwede.com/views/upload_qr.php?session_token=" . $token;

$uploadsDir = "../uploads/";
if (!file_exists($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

$imgPath = $uploadsDir . "mobile_qr_" . $token . ".png";
QRcode::png($url, $imgPath, QR_ECLEVEL_L, 6);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Mobile QR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/access.css">
  <style>
    body.accessible-body {
      font-size: clamp(1.25rem, 2.5vw, 2rem);
      line-height: 1.6;
      padding: 1.5rem;
      background-color: #e6f0ff;
    }
    h2, h3, label {
      font-size: clamp(1.5rem, 3vw, 2.5rem);
    }
    input, select, button {
      font-size: 1.25rem !important;
      padding: 0.75rem !important;
    }
    .btn {
      width: 100%;
      margin-top: 1rem;
    }
  </style>

  <style>
    body {
      background-color: #000;
      color: black;
      height: 100vh;
      margin: 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      font-size: 1.5rem;
    }
    img {
      max-width: 90vw;
      max-height: 70vh;
      margin-top: 20px;
      border: 5px solid #fff;
      border-radius: 12px;
    }
    h2 {
      font-size: 2rem;
    }
    @media (min-width: 768px) {
      body {
        font-size: 1.75rem;
      }
      h2 {
        font-size: 2.5rem;
      }
    }
  </style>
</head>
<body class="accessible-body">
  <div id="accessibility-bar" class="accessibility-bar"></div>

  <h2>Scan this QR Code to Continue on Mobile</h2>
  <img src="<?php echo $imgPath; ?>" alt="Mobile QR Code">

  <script src="../assets/js/accessibility-tools.js"></script>
</body>
</html>
