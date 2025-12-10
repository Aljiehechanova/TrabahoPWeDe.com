<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require '../config/db_connection.php';

if (!isset($_SESSION['session_token'])) {
    header("Location: registration_step2.php");
    exit();
}
$session_token = $_SESSION['session_token'];

/* --------------------------------------------------------------
   1. FETCH USER DATA
   -------------------------------------------------------------- */
$stmt = $conn->prepare("
    SELECT first_name, last_name, preferred_work,
           email, location,
           facial_image_path
    FROM qr_data WHERE session_token = ?
");
$stmt->execute([$session_token]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$userData) die("Invalid session.");

/* --------------------------------------------------------------
   2. SECTION TITLE HELPER
   -------------------------------------------------------------- */
function sectionTitle(TCPDF $pdf, string $txt) {
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->SetTextColor(0, 70, 140);
    $pdf->Cell(0, 6, $txt, 0, 1, 'L');
    $pdf->Ln(2);
}

/* --------------------------------------------------------------
   4. POST – UPLOAD RESUME (OPTIONAL)
   -------------------------------------------------------------- */
$uploadError = '';
if (isset($_POST['upload_resume'])) {
    // Only process if a file was actually selected
    if (!empty($_FILES['resume_file']['name']) && $_FILES['resume_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmp  = $_FILES['resume_file']['tmp_name'];
        $fileName = basename($_FILES['resume_file']['name']);
        $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($ext !== 'pdf') {
            $uploadError = "Only PDF files are allowed.";
        } else {
            $newName  = 'resume_'.uniqid().'.pdf';
            $folder   = realpath(__DIR__.'/../uploads/resumes');
            if (!is_dir($folder)) mkdir($folder, 0755, true);
            $filepath = $folder.'/'.$newName;

            if (move_uploaded_file($fileTmp, $filepath)) {
                $dbPath = 'uploads/resumes/'.$newName;
                $upd = $conn->prepare("UPDATE qr_data SET resume = ? WHERE session_token = ?");
                $upd->execute([$dbPath, $session_token]);
                header('Location: final_submit.php');
                exit();
            } else {
                $uploadError = "Failed to save the file. Please try again.";
            }
        }
    } else {
        $uploadError = "Please select a PDF file to upload.";
    }
}

/* --------------------------------------------------------------
   3. POST – GENERATE PDF (default path)
   -------------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['upload_resume'])) {
    $about_me     = $_POST['about_me'] ?? "I am a motivated individual seeking opportunities to apply my skills and grow professionally. I value teamwork, punctuality, and continuous learning.";
    $projects     = $_POST['projects'] ?? "N/A";
    $other_skills = $_POST['other_skills'] ?? "N/A";

    $photoFile = $userData['facial_image_path'] ?? '';
    $photoPath = __DIR__ . '/../uploads/facial_images/' . $photoFile;
    $photoExists = $photoFile && file_exists($photoPath);

    require_once __DIR__ . '/tcpdf/tcpdf.php';
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetMargins(18, 20, 18);
    $pdf->SetAutoPageBreak(true, 18);
    $pdf->AddPage();

    // ... [Your full PDF generation code here] ...

    $filename = 'resume_'.uniqid().'.pdf';
    $folder   = realpath(__DIR__.'/../uploads/resumes');
    if (!is_dir($folder)) mkdir($folder, 0755, true);
    $filepath = $folder.'/'.$filename;
    $pdf->Output($filepath, 'F');

    $dbPath = 'uploads/resumes/'.$filename;
    $upd = $conn->prepare("UPDATE qr_data SET resume = ? WHERE session_token = ?");
    $upd->execute([$dbPath, $session_token]);

    header('Location: final_submit.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Step 6: Resume</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{background:#87CEEB}
        .upload-box{border:2px solid #FFD700;border-radius:12px;padding:30px;max-width:800px;margin:auto;background:#003366;color:#fff;box-shadow:0 4px 15px rgba(0,0,0,.2)}
        .form-label{color:#FFD700;font-weight:600}
        .step-progress{display:flex;justify-content:space-between;flex-wrap:wrap;list-style:none;margin-bottom:30px;padding:0}
        .step-progress li{flex:1;text-align:center;border-radius:8px;margin:5px;padding:10px;font-size:13px;font-weight:600}
        .step-progress .active{background:#FFD700;color:#003366;font-weight:bold}
        .step-progress .inactive{background:#f0f0f0;color:#6c757d}
        .btn-primary{background:#FFD700;border:none;color:#003366;font-weight:bold}
        .btn-primary:hover{background:#FF8C00;color:#fff}
        .btn-success{background:#28a745;border:none}
        .btn-success:hover{background:#218838}
    </style>
</head>
<body class="p-4">

<div style="margin:20px 0;">
    <ol class="step-progress">
        <li class="inactive">1. Upload QR</li>
        <li class="inactive">2. Upload PWD ID</li>
        <li class="inactive">3. Facial Verification</li>
        <li class="inactive">4. Personal Info - Step 1</li>
        <li class="inactive">5. Preferred Work - Step 2</li>
        <li class="active">6. Resume Form</li>
        <li class="inactive">7. Final Confirmation</li>
    </ol>
</div>

<div class="container py-4">
    <div class="upload-box">
        <h2 class="mb-4 text-center fw-bold">Step 6: Resume</h2>

        <form method="POST" enctype="multipart/form-data">
            <!-- About Me -->
            <div class="mb-3">
                <label class="form-label">About Me</label>
                <textarea name="about_me" rows="5" class="form-control" placeholder="Tell us about yourself...">I am a motivated individual seeking opportunities to apply my skills and grow professionally. I value teamwork, punctuality, and continuous learning.</textarea>
            </div>

            <!-- Projects -->
            <div class="mb-3">
                <label class="form-label">Projects</label>
                <textarea name="projects" rows="4" class="form-control" placeholder="List your projects..."></textarea>
            </div>

            <!-- Other Skills -->
            <div class="mb-3">
                <label class="form-label">Other Skills</label>
                <textarea name="other_skills" rows="3" class="form-control" placeholder="Any other skills?"></textarea>
            </div>

            <!-- Generate Resume Button -->
            <button type="submit" class="btn btn-primary w-100 mb-3">
                Generate Resume & Continue
            </button>

            <!-- Optional Upload Section -->
            <div class="text-center">
                <p class="mb-2"><strong>OR</strong></p>
                <button type="button" class="btn btn-outline-light btn-sm" data-bs-toggle="collapse" data-bs-target="#uploadCollapse">
                    Upload Your Own Resume (PDF) – Optional
                </button>
            </div>

            <div class="collapse mt-3" id="uploadCollapse">
                <?php if (!empty($uploadError)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($uploadError); ?></div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Select Your Resume (PDF)</label>
                    <input type="file" name="resume_file" accept=".pdf" class="form-control">
                    <small class="text-muted">Leave empty if you want to generate one instead.</small>
                </div>

                <button type="submit" name="upload_resume" class="btn btn-success w-100">
                    Upload & Continue
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/accessibility-tools.js"></script>
</body>
</html>