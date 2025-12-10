<?php 
session_start();
require_once '../config/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$client_id = $_SESSION['user_id'];

if ($_SESSION['user_type'] !== 'client') {
    header("Location: login.php");
    exit;
}

// Fetch client info
$stmt = $conn->prepare("SELECT fullname, img FROM users WHERE user_id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

// Fallback profile image
$profileImage = (!empty($client['img']) && file_exists(__DIR__ . "/../uploads/" . $client['img']))
    ? "../uploads/" . htmlspecialchars($client['img'])
    : "../assets/images/alterprofile.png";

// Fetch On-Hold applicants
$stmt = $conn->prepare("
    SELECT js.job_id, jp.jobpost_title, u.user_id, u.fullname, u.img, js.status 
    FROM jobstages js
    JOIN users u ON js.user_id = u.user_id
    JOIN jobpost jp ON js.job_id = jp.jobpost_id
    WHERE jp.user_id = ? AND js.status = 'On Hold'
    ORDER BY js.date_updated DESC
");
$stmt->execute([$client_id]);
$applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Applicants On-Hold - Trabaho PWeDe</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <style>
    body {
      background: #87CEEB;
      font-family: 'Segoe UI', sans-serif;
      padding-top: 80px;
    }
    html { scroll-behavior: smooth; }

    /* Navbar */
    .navbar {
      background: #002147 !important;
      box-shadow: 0 4px 14px rgba(0,0,0,0.4);
    }
    .navbar .navbar-brand span.trabaho { color: #fff; }
    .navbar .navbar-brand span.pwe { color: #FFD700; }
    .navbar-toggler { border: none; }
    .navbar-toggler-icon { filter: invert(1); }
    .navbar-profile-img {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #FFD700;
    }
   .navbar {
      background: #002147 !important;
      box-shadow: 0 4px 14px rgba(0,0,0,0.4);
    }
    /* Navbar links */
.navbar-nav .nav-link {
  color: #fff !important;      /* white by default */
  font-weight: 500;
  border-radius: 6px;
  padding: 8px 12px;
  transition: 0.3s;
}

/* On hover or active */
.navbar-nav .nav-link:hover,
.navbar-nav .nav-link.active {
  background-color: #FFD700 !important; /* highlight yellow */
  color: #002147 !important;            /* text turns dark blue */
}

    .navbar .navbar-brand span.trabaho { color: #fff; margin-right: 4px; }
    .navbar .navbar-brand span.pwe { color: #FFD700; }
    .navbar-toggler { border: none; }
    .navbar-toggler-icon { filter: invert(1); }

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
    #sidebar nav ul li a:hover {
      background: #FFD700;
      color: #002147 !important;
    }

    /* Main Content */
    .main-content { margin-left: 250px; padding: 20px; }
    @media (max-width: 992px) {
      #sidebar { display: none; }
      .main-content { margin-left: 0; }
    }

    /* Job Cards (dark blue style) */
    .job-card {
      background: #002147;
      color: #fff;
      border-radius: 16px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.4);
      transition: 0.3s;
    }
    .job-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.6);
    }
    .job-card h5 { color: #FFD700; margin-bottom: 6px; }
    .job-card p { margin: 0; color: #fff; }

    .btn-warning {
      background: #FFD700;
      color: #002147;
      border: none;
      font-weight: 600;
      border-radius: 12px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.4);
      padding: 6px 14px;
    }
    .btn-warning:hover { background: #e6c200; }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="clientD.php">
      <img src="../assets/images/TrabahoPWeDeLogo.png" width="40" height="40" class="me-2">
      <span class="trabaho fw-bold">Trabaho</span><span class="pwe fw-bold">PWeDe</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-lg-none">
        <li class="nav-item"><a class="nav-link" href="clientD.php"><i class="bi bi-graph-up"></i> Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="clientL.php"><i class="bi bi-briefcase"></i> Job List</a></li>
        <li class="nav-item"><a class="nav-link" href="clientW.php"><i class="bi bi-easel"></i> Workshops</a></li>
        <li class="nav-item"><a class="nav-link" href="listofapplicant.php"><i class="bi bi-people"></i> Applicants</a></li>
        <li class="nav-item"><a class="nav-link" href="posting.php"><i class="bi bi-plus-circle"></i> Posting</a></li>
        <li class="nav-item"><a class="nav-link" href="clientM.php"><i class="bi bi-envelope"></i> Inbox</a></li>
      </ul>
      <!-- Right side -->
<div class="d-flex align-items-center ms-auto">
  <!-- Profile + Name (click → clientP.php) -->
  <a href="clientP.php" class="d-flex align-items-center text-decoration-none text-white me-3">
    <img src="<?= $profileImage ?>" alt="Profile" class="navbar-profile-img me-2">
    <span class="fw-semibold"><?= htmlspecialchars($client['fullname']) ?></span>
  </a>

  <!-- Settings Dropdown -->
  <div class="dropdown">
    <button class="btn btn-outline-light dropdown-toggle" type="button" id="settingsMenu" data-bs-toggle="dropdown" aria-expanded="false">
      Settings
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingsMenu">
      <li><a class="dropdown-item" href="clientP.php"><i class="bi bi-person"></i> Edit Profile</a></li>
      <li><a class="dropdown-item" href="#"><i class="bi bi-key"></i> Change Password</a></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item text-danger" href="login.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
    </ul>
  </div>
</div>

    </div>
  </div>
</nav>

<!-- SIDEBAR -->
<div id="sidebar" class="d-none d-lg-block">
  <nav>
    <ul>
      <li><a href="clientD.php"><i class="bi bi-graph-up"></i> Dashboard</a></li>
      <li><a href="clientL.php"><i class="bi bi-briefcase"></i> Job List</a></li>
      <li><a href="clientW.php"><i class="bi bi-easel"></i> Workshops</a></li>
      <li><a href="listofapplicant.php"><i class="bi bi-people"></i> Applicants</a></li>
      <li><a href="posting.php"><i class="bi bi-plus-circle"></i> Posting</a></li>
      <li><a href="clientM.php"><i class="bi bi-envelope"></i> Inbox</a></li>
    </ul>
  </nav>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
  <h1 class="mb-4 text-dark">Applicants On-Hold</h1>

  <?php if (!empty($applicants)): ?>
    <?php foreach ($applicants as $applicant): ?>
      <div class="job-card d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
          <img src="<?= htmlspecialchars($applicant['img']) ?>" class="rounded-circle me-3" width="50" height="50" style="object-fit: cover;">
          <div>
            <h5><?= htmlspecialchars($applicant['fullname']) ?></h5>
            <p><strong>Job:</strong> <?= htmlspecialchars($applicant['jobpost_title']) ?></p>
            <!-- Removed the yellow On Hold badge -->
          </div>
        </div>
        <form method="POST" action="hire_user.php">
          <input type="hidden" name="user_id" value="<?= $applicant['user_id'] ?>">
          <input type="hidden" name="job_id" value="<?= $applicant['job_id'] ?>">
          <input type="hidden" name="action" value="hire">
          <button type="submit" class="btn btn-warning">Hire</button>
        </form>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No applicants currently on hold.</p>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
