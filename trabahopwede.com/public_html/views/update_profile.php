

} catch (PDO<?php
require_once(__DIR__ . "/../config/db_connection.php");
session_start();

try {
    $userId = $_POST['user_id'];
    $contact = trim($_POST['contact_number'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $disability = trim($_POST['disability'] ?? '');

    $imgPath = null;

    // Handle uploaded image
    if (!empty($_FILES['img']['name'])) {
        $uploadsDir = '../uploads/';
        if (!file_exists($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }

        $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        $filename = uniqid("user_", true) . '.' . $ext;
        $fullPath = $uploadsDir . $filename;

        if (move_uploaded_file($_FILES['img']['tmp_name'], $fullPath)) {
            $imgPath = $fullPath;
        }
    }

    // Build SQL query
    $sql = "UPDATE users SET 
                contact_number = :contact,
                location = :location,
                description = :description,
                disability = :disability";

    if ($imgPath) {
        $sql .= ", img = :img";
    }

    $sql .= " WHERE user_id = :id";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $params = [
        ':contact' => $contact,
        ':location' => $location,
        ':description' => $description,
        ':disability' => $disability,
        ':id' => $userId
    ];

    if ($imgPath) {
        $params[':img'] = $imgPath;
    }

    $stmt->execute($params);

    // ✅ Redirect to the actual dashboard: userP.php
    header("Location: userP.php?updated=1");
    exit();

} catch (PDOException $e) {
    echo "Error updating profile: " . $e->getMessage();
}
?>

