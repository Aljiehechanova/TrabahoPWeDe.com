
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify</title>
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
        <h2 class="mb-4 fw-bold">Verify</h2>
        <div class="upload-box">
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify</title>
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
        <h2 class="mb-4 fw-bold">Verify</h2>
        <div class="upload-box">
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../config/db_connection.php';

if (!isset($_GET['token'])) {
    die("Invalid token.");
}

$token = $_GET['token'];
$stmt = $conn->prepare("SELECT * FROM qr_data WHERE email_token = ? AND email_verified = 0");
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

if ($insert->execute()) {
    $update = $conn->prepare("UPDATE qr_data SET email_verified = 1 WHERE id = ?");
    $update->bind_param("i", $data['id']);
    $update->execute();
    echo "Email verified and user registered successfully.";
} else {
    echo "Error: " . $insert->error;
}

$stmt->close();
$conn->close();
?>

        </div>
    </div>
</body>
</html>


if (!isset($_GET['token'])) {
    die("Invalid token.");
}

$token = $_GET['token'];
$stmt = $conn->prepare("SELECT * FROM qr_data WHERE email_token = ? AND email_verified = 0");
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

if ($insert->execute()) {
    $update = $conn->prepare("UPDATE qr_data SET email_verified = 1 WHERE id = ?");
    $update->bind_param("i", $data['id']);
    $update->execute();
    echo "Email verified and user registered successfully.";
} else {
    echo "Error: " . $insert->error;
}

$stmt->close();
$conn->close();
?>

        </div>
    </div>
</body>
</html>
