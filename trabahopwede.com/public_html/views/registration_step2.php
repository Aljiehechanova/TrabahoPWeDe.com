<?php
// Start session and error reporting before ANY output
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require '../config/db_connection.php';

if (!isset($_SESSION['session_token'])) {
    die("Session expired. Please start again.");
}

// Handle form submission BEFORE HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $experience_status = $_POST['experience_status'] ?? '';
    $experience_years = $_POST['experience_years'] ?? '';
    $work_field = $_POST['work_field'] ?? '';
    $preferred_work = $_POST['preferred_work'] ?? '';
    $skills = $_POST['skills'] ?? [];

    if (in_array('Other', $skills) && !empty($_POST['otherRequiredSkills'])) {
        $skills = array_merge($skills, explode(',', $_POST['otherRequiredSkills']));
    }

    $skills_string = implode(',', $skills);
    $work_status = $_POST['work_status'] ?? '';
    $shift = $_POST['shift'] ?? '';
    $expected_amount = $_POST['expected_amount'] ?? '';

    $stmt = $conn->prepare("UPDATE qr_data SET experience_status=?, experience_years=?, work_field=?, preferred_work=?, skills=?, work_status=?, shift=?, expected_amount=? WHERE session_token=?");
    $stmt->execute([
        $experience_status,
        $experience_years,
        $work_field,
        $preferred_work,
        $skills_string,
        $work_status,
        $shift,
        $expected_amount,
        $_SESSION['session_token']
    ]);

    header("Location: registration_step3.php");
    exit;
}

// Fetch disability data for preferred work logic
$stmt = $conn->prepare("SELECT disability FROM qr_data WHERE session_token = ?");
$stmt->execute([$_SESSION['session_token']]);
$disability = $stmt->fetchColumn();

$default_disability = explode(" - ", $disability);
$general_disability = trim($default_disability[0]);
$sub_disability = isset($default_disability[1]) ? trim($default_disability[1]) : null;

$preferred_work_options = [
    "Visual" => [
        "Partial" => ["Massage Therapy", "Phone Support", "Clerical Work"],
        "Full" => ["Massage Therapy", "Music", "Telesales"]
    ],
    "Hearing" => [
        "Partial" => ["Data Entry", "IT Jobs", "Tailoring"],
        "Full" => ["Graphic Design", "Art", "Technical Writing"]
    ],
    "Speech" => [
        "Partial" => ["Customer Support", "Sales", "IT Support"],
        "Full" => ["Data Entry", "Digital Design", "Programming"]
    ],
    "Physical" => [
        "Upper Limb" => ["Teaching", "Reception", "Data Entry"],
        "Lower Limb" => ["Remote Work", "Writing", "Tech Support"]
    ],
    "Others" => [
        "Default" => ["Any Desk Job", "Freelance", "Support Tasks"]
    ]
];

$preferred_list = $preferred_work_options[$general_disability][$sub_disability] ??
                  $preferred_work_options[$general_disability]['Default'] ??
                  $preferred_work_options['Others']['Default'];
?>
<?php include 'accessibility.php'; ?>
<main id="main-content">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Step 6: Resume</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
          background-color: #87CEEB; /* Sky blue background */
        }

        /* Main container */
        .upload-box {
          border: 2px solid #FFD700; /* Yellow border */
          border-radius: 12px;
          padding: 30px;
          max-width: 800px;
          margin: 0 auto;
          background-color: #003366; /* Dark blue container */
          color: #fff; /* White text */
          box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        /* Labels */
        .form-label {
          color: #FFD700; /* Yellow labels */
          font-weight: 600;
        }

        .form-control, 
        .form-select {
          border-radius: 8px;
        }

        /* Step Progress Navigation */
        .step-progress {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 20px;
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
            background: #FFD700; /* Yellow active */
            color: #003366;      /* Dark blue text */
            font-weight: bold;
        }
        .step-progress .inactive {
            background: #f0f0f0;
            color: #6c757d;
        }

        /* Bootstrap Progress Bar override */
        .progress-bar {
            background-color: #FF8C00 !important; /* Orange progress */
            font-weight: bold;
        }

        /* Buttons */
        .btn-primary {
          background-color: #FFD700;
          border: none;
          color: #003366;
          font-weight: bold;
        }
        .btn-primary:hover {
          background-color: #FF8C00; /* Orange hover */
          color: #fff;
        }
        .btn-secondary {
          background-color: #6c757d;
          border: none;
        }
    </style>
</head>
<body class="p-4">

<!-- Step Navigation -->
<div>
  <ol class="step-progress">
    <li class="inactive">1. Upload QR</li>
    <li class="inactive">2. Upload PWD ID</li>
    <li class="inactive">3. Facial Verification</li>
    <li class="inactive">4. Personal Info - Step 1</li>
    <li class="active">5. Preferred Work - Step 2</li>
    <li class="inactive">6. Resume Form</li>
    <li class="inactive">7. Final Confirmation</li>
  </ol>
</div>

<!-- Main container -->
<div class="container py-4">
    <div class="upload-box">
        <h2 class="mb-4 text-center fw-bold">Step 5: Preferred Work</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Do you have any work experience?</label>
                <select name="experience_status" id="experience_status" class="form-select" onchange="handleExperienceChange()" required>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Years of Experience</label>
                <select name="experience_years" id="experience_years" class="form-select" required>
                    <option value="">Select...</option>
                    <option value="1-2 years">1–2 years</option>
                    <option value="3-5 years">3–5 years</option>
                    <option value="5+ years">5+ years</option>
                    <option value="N/A">N/A</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Field of Work</label>
                <input type="text" name="work_field" id="work_field" class="form-control" placeholder="E.g., Admin, Retail">
            </div>

            <div class="mb-3">
                <label class="form-label">Preferred Work</label>
                <select name="preferred_work" class="form-select" required>
                    <option value="">Select...</option>
                    <?php foreach ($preferred_list as $option): ?>
                        <option value="<?= htmlspecialchars($option) ?>"><?= htmlspecialchars($option) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Required Skills</label>
                <select class="form-select" name="skills[]" id="requiredSkills" multiple onchange="toggleOtherField('otherRequiredSkillsField', 'requiredSkills')">
                    <option value="Computer Literacy">Computer Literacy</option>
                    <option value="Graphic Design">Graphic Design</option>
                    <option value="Programming">Programming</option>
                    <option value="Customer Service">Customer Service</option>
                    <option value="Massage Therapy">Massage Therapy</option>
                    <option value="Other">Other</option>
                </select>
                <input type="text" id="otherRequiredSkillsField" name="otherRequiredSkills" class="form-control mt-2" placeholder="Other required skills" style="display: none;">
            </div>

            <div class="mb-3">
                <label class="form-label">Optional Skills</label>
                <select class="form-select" name="optionalSkills[]" id="optionalSkills" multiple onchange="toggleOtherField('otherOptionalSkillsField', 'optionalSkills')">
                    <option value="Video Editing">Video Editing</option>
                    <option value="Content Writing">Content Writing</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Other">Other</option>
                </select>
                <input type="text" id="otherOptionalSkillsField" name="otherOptionalSkills" class="form-control mt-2" placeholder="Other optional skills" style="display: none;">
            </div>

            <div class="mb-3">
                <label class="form-label">Preferred Type of Work</label>
                <select name="work_status" class="form-select" required>
                    <option value="">Select...</option>
                    <option value="Full-time">Full-time</option>
                    <option value="Part-time">Part-time</option>
                    <option value="Freelance">Freelance</option>
                    <option value="Remote">Remote</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Preferred Shift</label>
                <select name="shift" class="form-select" required>
                    <option value="">Select...</option>
                    <option value="Day">Day Shift</option>
                    <option value="Night">Night Shift</option>
                    <option value="Flexible">Flexible</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Expected Monthly Amount</label>
                <select name="expected_amount" class="form-select" required>
                    <option value="">Select...</option>
                    <option value="10000">₱10,000</option>
                    <option value="15000">₱15,000</option>
                    <option value="20000">₱20,000</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Continue</button>
        </form>
    </div>
</div>

<script>
function toggleOtherField(inputId, dropdownId) {
    const input = document.getElementById(inputId);
    const dropdown = document.getElementById(dropdownId);
    input.style.display = Array.from(dropdown.selectedOptions).some(opt => opt.value === "Other") ? "block" : "none";
}

function handleExperienceChange() {
    const experience = document.getElementById('experience_status').value;
    const years = document.getElementById('experience_years');
    const field = document.getElementById('work_field');

    if (experience === 'No') {
        years.value = 'N/A';
        field.value = 'N/A';
        years.setAttribute('readonly', true);
        field.setAttribute('readonly', true);
    } else {
        years.removeAttribute('readonly');
        field.removeAttribute('readonly');
    }
}

document.addEventListener("DOMContentLoaded", function () {
    handleExperienceChange();
});
</script>

<script src="../assets/js/accessibility-tools.js"></script>
</body>
</html>

</main>