
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qr Parser Api</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .upload-box {
            border: 2px solid #ccc;
            border-radius: 12px;
            padding: 30px;
            max-width: 700px;
            margin: 0 auto;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container py-5 text-center">
        <h2 class="mb-4 fw-bold">Qr Parser Api</h2>
        <div class="upload-box">

<?php
require 'vendor/pwede-qr/vendor/khanamiryan/qrcode-detector-decoder/lib/QrReader.php';

if (isset($_GET['image'])) {
    $filePath = $_GET['image'];

    try {
        $qrcode = new Zxing\QrReader($filePath);
        $text = $qrcode->text();

        if ($text) {
            echo json_encode(["qr_url" => $text]);
        } else {
            echo json_encode(["error" => "QR code unreadable"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "No image specified"]);
}
?>

        </div>
    </div>
</body>
</html>
