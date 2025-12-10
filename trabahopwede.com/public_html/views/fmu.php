<?php
require_once '../config/db_connection.php';

if (!isset($_GET['job_id'])) {
    http_response_code(400);
    echo "Missing job_id parameter.";
    exit;
}

$jobId = intval($_GET['job_id']);

// Handle form values
$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] === 'asc' ? 'ASC' : 'DESC';
$top_limit = in_array($_GET['top_limit'] ?? '', ['5', '10', '15']) ? intval($_GET['top_limit']) : 15;

try {
    // Fetch job requirements
    $stmt = $conn->prepare("SELECT disability_requirement, skills_requirement FROM jobpost WHERE jobpost_id = :job_id");
    $stmt->bindParam(':job_id', $jobId);
    $stmt->execute();
    $job = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$job) {
        echo "<p>Job not found.</p>";
        exit;
    }

    $disability = $job['disability_requirement'];
    $skills = array_map('trim', explode(',', $job['skills_requirement']));

    // Match score calculation
    $scoreParts = [];
    $params = [];
    foreach ($skills as $index => $skill) {
        $param = ":skill$index";
        $scoreParts[] = "IF(LOCATE($param, u.skills) > 0, 1, 0)";
        $params[$param] = $skill;
    }
    $scoreSql = implode(' + ', $scoreParts);

    // Final SQL
    $sql = "
        SELECT u.user_id, u.fullname, u.email, u.skills, u.resume, ($scoreSql) AS match_score
        FROM users u
        WHERE u.user_type = 'job_seeker' 
          AND u.disability = :disability
        ORDER BY match_score $sort_order
        LIMIT :top_limit
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':disability', $disability);
    $stmt->bindValue(':top_limit', $top_limit, PDO::PARAM_INT);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $stmt->execute();
    $applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display applicants
    if (empty($applicants)) {
        echo "<p>No matching applicants found.</p>";
    } else {
        foreach ($applicants as $a) {
            echo "<div class='applicant-card mb-4 p-3 border rounded'>";
            echo "<h4>" . htmlspecialchars($a['fullname']) . "</h4>";
            echo "<p>Email: " . htmlspecialchars($a['email']) . "</p>";
            echo "<p>Skills: " . htmlspecialchars($a['skills']) . "</p>";
            echo "<p><strong>Match Score:</strong> " . $a['match_score'] . "</p>";

            // === FLEX CONTAINER FOR BUTTONS ===
            echo "<div class='d-flex gap-2 mt-2'>";

            // Form with hire & on hold buttons
            echo "<form method='POST' action='hire_user.php' class='d-flex gap-2'>";
            echo "<input type='hidden' name='user_id' value='" . $a['user_id'] . "'>";
            echo "<input type='hidden' name='job_id' value='" . $jobId . "'>";
            echo "<button type='submit' name='action' value='hire' class='btn btn-success btn-sm'>Hire</button>";
            echo "<button type='submit' name='action' value='onhold' class='btn btn-warning btn-sm'>On Hold</button>";
            echo "</form>";

            // Resume button aligned in same row
            if (!empty($a['resume'])) {
                echo "<a href='../../" . htmlspecialchars($a['resume']) . "' target='_blank' class='btn btn-info btn-sm'>View Resume</a>";
            }

            echo "</div>"; // close flexbox
            echo "</div>"; // close applicant card
        }
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo "Database error: " . $e->getMessage();
}
?>
