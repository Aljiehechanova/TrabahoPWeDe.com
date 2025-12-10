<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];

try {
    $db = new PDO("mysql:host=localhost;dbname=u227479843_TrabahoPWede", "u227479843_TrabahoPWede", "Happyme012!");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $jobTitle = htmlspecialchars($_POST['jobTitle'] ?? '');
    $disabilityType = htmlspecialchars($_POST['disabilityType'] ?? '');
    $optionalSkills = isset($_POST['optionalSkills']) ? implode(", ", array_map('htmlspecialchars', $_POST['optionalSkills'])) : 'N/A';
    $requiredSkills = isset($_POST['requiredSkills']) ? implode(", ", array_map('htmlspecialchars', $_POST['requiredSkills'])) : '';
    $yearsExperience = htmlspecialchars($_POST['yearsExperience'] ?? 'N/A');
    $shiftHours = htmlspecialchars($_POST['shiftHours'] ?? 'Flexible'); // <-- NEW

    $query = "INSERT INTO jobpost (
        jobpost_title, 
        disability_requirement, 
        skills_requirement, 
        optional_skills,
        years_experience,
        shift,         -- <-- NEW
        user_id
    ) VALUES (
        :jobTitle, 
        :disabilityType, 
        :requiredSkills, 
        :optionalSkills,
        :yearsExperience,
        :shift,         -- <-- NEW
        :user_id
    )";

    try {
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':jobTitle' => $jobTitle,
            ':disabilityType' => $disabilityType,
            ':requiredSkills' => $requiredSkills,
            ':optionalSkills' => $optionalSkills,
            ':yearsExperience' => $yearsExperience,
            ':shift' => $shiftHours,         // <-- NEW
            ':user_id' => $user_id
        ]);

        $jobpost_id = $db->lastInsertId();

        $appointmentStmt = $db->prepare("INSERT INTO job_appointments (jobpost_id) VALUES (:jobpost_id)");
        $appointmentStmt->execute([':jobpost_id' => $jobpost_id]);

        header("Location: posting.php");
        exit();
    } catch (PDOException $e) {
        echo "Error inserting data: " . $e->getMessage();
    }
} else {
    echo "Invalid form submission.";
}
?>
