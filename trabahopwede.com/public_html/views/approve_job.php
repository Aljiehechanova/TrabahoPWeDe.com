<?php
session_start();
require_once '../config/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    die("Access denied: Admins only.");
}

$admin_id = $_SESSION['user_id'];

/* Fetch profile */
$stmt = $conn->prepare("SELECT fullname, img FROM users WHERE user_id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
$profileImage = (!empty($admin['img']) && file_exists(__DIR__ . "/../uploads/" . $admin['img']))
    ? "../uploads/" . htmlspecialchars($admin['img'])
    : "../assets/images/alterprofile.png";

/* Handle approval/rejection */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jobpost_id'], $_POST['action'])) {
    $jobpostId = $_POST['jobpost_id'];
    $action = $_POST['action'];
    $appointmentTime = $_POST['appointment_time'] ?? null;

    if (!in_array($action, ['approve', 'reject'])) {
        die("Invalid action.");
    }

    try {
        if ($action === 'approve') {
            $stmt = $conn->prepare("
                UPDATE jobpost 
                SET status = ?, approved_by = ?, appointment_time = ? 
                WHERE jobpost_id = ?
            ");
            $stmt->execute([$action, $admin_id, $appointmentTime, $jobpostId]);
        } else {
            $stmt = $conn->prepare("
                UPDATE jobpost 
                SET status = ?, approved_by = ? 
                WHERE jobpost_id = ?
            ");
            $stmt->execute([$action, $admin_id, $jobpostId]);
        }

        header("Location: approve_job.php");
        exit;
    } catch (PDOException $e) {
        die("Approval error: " . $e->getMessage());
    }
}

/* Fetch pending job posts */
try {
    $stmt = $conn->prepare("
        SELECT 
            jp.jobpost_id, jp.jobpost_title, jp.disability_requirement, 
            jp.years_experience, jp.skills_requirement, jp.optional_skills, 
            jp.status, jp.appointment_time, u.company 
        FROM jobpost jp
        JOIN users u ON jp.user_id = u.user_id
        WHERE jp.status = 'pending'
        ORDER BY jp.jobpost_id DESC
    ");
    $stmt->execute();
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Manage Job Post Approvals - TrabahoPWeDe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      background: #87CEEB;
      font-family: 'Segoe UI', sans-serif;
      padding-top: 80px;
      margin: 0;
    }
    /* Navbar */
    .navbar {
      background: #002147 !important;
      box-shadow: 0 4px 14px rgba(0,0,0,0.4);
    }
    .navbar .navbar-brand span.trabaho { color: #fff; margin-right: 4px; }
    .navbar .navbar-brand span.pwe { color: #FFD700; }
    .navbar-toggler { border: none; }
    .navbar-toggler-icon { filter: invert(1); }
    .navbar-profile-img {
      width: 38px; height: 38px;
      border-radius: 50%; object-fit: cover;
      border: 2px solid #FFD700;
    }
    /* Sidebar */
    #sidebar {
      background: #002147;
      color: #fff;
      min-height: 100vh;
      width: 250px;
      padding: 20px 10px;
      position: fixed;
      top: 0;
      left: 0;
      padding-top: 80px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.4);
    }
    #sidebar nav ul { list-style: none; padding: 0; }
    #sidebar nav ul li { margin: 12px 0; }
    #sidebar nav ul li a {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      padding: 10px 12px;
      display: flex;
      align-items: center;
      gap: 8px;
      border-radius: 8px;
      transition: 0.3s;
    }
    #sidebar nav ul li a:hover,
    #sidebar nav ul li a.active {
      background: #FFD700;
      color: #002147 !important;
    }
    /* Main content */
    .main-content { margin-left: 250px; padding: 20px; }
    @media (max-width: 992px) {
      #sidebar { display: none; }
      .main-content { margin-left: 0; }
    }
    /* Big container */
    .approval-container {
      background: #002147;
      color: #FFD700;
      border-radius: 16px;
      padding: 20px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.4);
    }
    /* Table */
    /* Table */
.table thead {
  background: #00152e;
  color: #FFD700; /* yellow header */
  text-transform: uppercase;
}
.table tbody tr td {
  color: #000; /* black text for readability */
  background: #fff; /* keep cells readable against dark bg */
}
.table tbody tr:hover {
  background: rgba(243, 156, 18, 0.2); /* orange hover */
  color: #000;
}

/* Appointment input */
.table input[type="datetime-local"] {
  background: #fff;
  border: 1px solid #002147;
  color: #000;
  border-radius: 6px;
  padding: 4px 6px;
}

    /* Buttons */
    .btn-primary {
      background-color: #FFD700;
      color: #002147;
      border: none;
      font-weight: 600;
    }
    .btn-success {
      background-color: #27ae60;
      border: none;
    }
    .btn-danger {
      background-color: #c0392b;
      border: none;
    }
  </style>
</head>
<body>
  <!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center sitename" href="addash.php">
      <img src="../assets/images/TrabahoPWeDeLogo.png" alt="Logo" width="40" height="40" class="me-2">
      <span class="trabaho fw-bold">Trabaho</span><span class="pwe fw-bold">PWeDe</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMenu">
      <!-- Mobile menu -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-lg-none">
        <li class="nav-item"><a class="nav-link" href="addash.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="approve_job.php"><i class="bi bi-briefcase"></i> Approval Job List</a></li>
        <li class="nav-item"><a class="nav-link" href="adme.php"><i class="bi bi-gear"></i> System Log</a></li>
      </ul>

      <div class="d-flex align-items-center ms-auto">
        <a href="admin_profile.php" class="d-flex align-items-center text-decoration-none text-white me-3">
          <img src="<?= $profileImage ?>" alt="Profile" class="navbar-profile-img me-2">
          <span class="fw-semibold"><?= htmlspecialchars($client['fullname']) ?></span>
        </a>

        <div class="dropdown">
          <button class="btn btn-outline-light dropdown-toggle" type="button" id="settingsMenu" data-bs-toggle="dropdown" aria-expanded="false">
            Settings
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingsMenu">
            <li><a class="dropdown-item" href="admin_profile.php"><i class="bi bi-person"></i> Edit Profile</a></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-key"></i> Change Password</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="../views/login.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- SIDEBAR (desktop) -->
<div id="sidebar" class="d-none d-lg-block">
  <nav>
    <ul>
      <li><a href="addash.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
      <li><a href="approve_job.php"class="active"><i class="bi bi-briefcase"></i> Approval Job List</a></li>
      <li><a href="adme.php"><i class="bi bi-gear"></i> System Log</a></li>
    </ul>
  </nav>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
  <div class="approval-container">
    <h2 class="fw-bold mb-3"><i class="bi bi-journal-check me-2"></i> Job Post Approval Panel</h2>
    <p class="text-warning">Review job posts with care. Approving or rejecting reflects platform standards.</p>
    <div class="table-responsive rounded-4">
      <table class="table align-middle table-hover text-center mb-0">
        <thead>
          <tr>
            <th>Title</th>
            <th>Disability</th>
            <th>Experience</th>
            <th>Required Skills</th>
            <th>Optional Skills</th>
            <th>Company</th>
            <th>Appointment</th>
            <th>Review</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($jobs)): ?>
            <tr><td colspan="8" class="text-muted">No job posts found.</td></tr>
          <?php else: ?>
            <?php foreach ($jobs as $job): ?>
              <tr>
                <td><?= htmlspecialchars($job['jobpost_title']) ?></td>
                <td><?= htmlspecialchars($job['disability_requirement']) ?></td>
                <td><?= htmlspecialchars($job['years_experience']) ?></td>
                <td><?= htmlspecialchars($job['skills_requirement']) ?></td>
                <td><?= htmlspecialchars($job['optional_skills']) ?></td>
                <td><?= htmlspecialchars($job['company']) ?></td>
                <td>
                  <form method="POST" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="jobpost_id" value="<?= $job['jobpost_id'] ?>">
                    <input type="datetime-local" name="appointment_time"
                      value="<?= htmlspecialchars($job['appointment_time'] ?? '') ?>"
                      class="form-control form-control-sm">
                </td>
                <td>
                  <div class="d-flex justify-content-center gap-2">
                    <button type="submit" name="action" value="approve" class="btn btn-success btn-sm rounded-pill px-3">
                      <i class="bi bi-check-lg me-1"></i> Accept
                    </button>
                    <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm rounded-pill px-3">
                      <i class="bi bi-x-lg me-1"></i> Reject
                    </button>
                  </div>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
