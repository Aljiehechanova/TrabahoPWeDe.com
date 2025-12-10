
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Match Api</title>
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
        <h2 class="mb-4 fw-bold">Face Match Api</h2>
        <div class="upload-box">

<?php
if (isset($_GET['img1']) && isset($_GET['img2'])) {
    // Simulate matching with 90% match rate
    $match = rand(1, 10) <= 9;
    $confidence = $match ? rand(85, 99) : rand(50, 70);
    echo json_encode([
        "match" => $match,
        "confidence" => $confidence
    ]);
} else {
    echo json_encode(["error" => "Missing image parameters"]);
}
?>

        </div>
    </div>
</body>
</html>
