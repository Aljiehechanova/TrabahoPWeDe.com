<?php
session_start();
require_once '../config/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$admin_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("
        SELECT jp.*, ja.appointment_id, ja.appointment_date, ja.appointment_time, ja.status AS appointment_status
        FROM jobpost jp
        LEFT JOIN job_appointments ja ON jp.jobpost_id = ja.jobpost_id
        WHERE jp.status = 'Pending'
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
    <title>Admin Appointment - Trabaho PWeDe</title>
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

      /* Cards */
      .card {
        background-color: #002147;
        border: none;
        color: #FFD700;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.5);
      }
      .card-title { color: #FFD700; }

      /* Buttons */
      .btn-primary {
        background-color: #FFD700;
        color: #002147;
        border: none;
        font-weight: 600;
      }
      .btn-primary:hover { background-color: #FFC300; color:#002147; }
      .btn-outline-success {
        border-color: #27ae60;
        color: #27ae60;
      }
      .btn-outline-success:hover {
        background-color: #27ae60;
        color: #fff;
      }
      .btn-outline-warning {
        border-color: #f39c12;
        color: #f39c12;
      }
      .btn-outline-warning:hover {
        background-color: #f39c12;
        color: #fff;
      }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="addash.php">
          <img src="../assets/images/TrabahoPWeDeLogo.png" alt="Logo" width="40" height="40" class="me-2">
          <span class="trabaho fw-bold">Trabaho</span><span class="pwe fw-bold">PWeDe</span>
        </a>

        <div class="ms-auto d-flex align-items-center">
          <!-- Notification bell -->
          <div class="dropdown me-3">
            <button class="btn btn-outline-light position-relative" type="button" id="notifDropdown" data-bs-toggle="dropdown">
              <i class="bi bi-bell fs-5"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown" style="width: 300px;">
              <li class="dropdown-item text-muted">No new notifications</li>
            </ul>
          </div>

          <div class="dropdown">
            <button class="btn btn-outline-light dropdown-toggle" type="button" id="settingsMenu" data-bs-toggle="dropdown">
              Settings
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingsMenu">
              <li><a class="dropdown-item text-danger" href="login.php">Logout</a></li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

    <!-- Sidebar -->
    <div id="sidebar">
      <nav>
        <ul>
          <li><a href="addash.php">Admin Dashboard</a></li>
          <li><a href="approve_job.php">Approval Job List</a></li>
          <li><a href="adme.php">System Log</a></li>
        </ul>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <div class="mb-4">
        <h2 class="fw-bold text-white border-bottom pb-2">
          <i class="bi bi-calendar-week text-warning me-2"></i> Admin Appointment Panel
        </h2>
        <p class="text-light mb-3">Manage interview schedules and filter candidates based on disability and skill focus.</p>

        <?php
        $filter_disability = $_POST['filter_disability'] ?? '';
        $filter_skill = $_POST['filter_skill'] ?? '';

        $all_disabilities = array_unique(array_filter(array_map(fn($job) => trim($job['disability_requirement']), $jobs)));
        $all_skills = array_unique(array_merge(...array_map(fn($job) => array_map('trim', explode(',', $job['skills_requirement'])), $jobs)));
        sort($all_disabilities);
        sort($all_skills);

        $filtered_jobs = array_filter($jobs, function ($job) use ($filter_disability, $filter_skill) {
          $matches_disability = $filter_disability === '' || $job['disability_requirement'] === $filter_disability;
          $skills_array = array_map('trim', explode(',', $job['skills_requirement']));
          $matches_skill = $filter_skill === '' || in_array($filter_skill, $skills_array);
          return $matches_disability && $matches_skill;
        });
        ?>

        <!-- Filters -->
        <form method="POST" class="row g-3 mb-4">
          <div class="col-md-5">
            <select name="filter_disability" class="form-select rounded-3 shadow-sm">
              <option value="">All Disabilities</option>
              <?php foreach ($all_disabilities as $dis): ?>
                <option value="<?= htmlspecialchars($dis) ?>" <?= $filter_disability == $dis ? 'selected' : '' ?>><?= htmlspecialchars($dis) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-5">
            <select name="filter_skill" class="form-select rounded-3 shadow-sm">
              <option value="">All Skills</option>
              <?php foreach ($all_skills as $skill): ?>
                <option value="<?= htmlspecialchars($skill) ?>" <?= $filter_skill == $skill ? 'selected' : '' ?>><?= htmlspecialchars($skill) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm">
              <i class="bi bi-filter-circle me-1"></i> Filter
            </button>
          </div>
        </form>
      </div>

      <!-- Cards -->
      <div class="row row-cols-1 row-cols-lg-3 g-4">
        <?php foreach ($filtered_jobs as $job): ?>
          <div class="col">
            <div class="card shadow-sm border-0 rounded-4 h-100">
              <div class="card-body p-4">
                <h5 class="card-title fw-bold mb-1">
                  <i class="bi bi-briefcase-fill me-1 text-warning"></i> <?= htmlspecialchars($job['jobpost_title']) ?>
                </h5>
                <p><i class="bi bi-universal-access me-1 text-warning"></i><strong>Disability:</strong> <?= htmlspecialchars($job['disability_requirement']) ?></p>
                <p><i class="bi bi-tools me-1 text-warning"></i><strong>Skills:</strong> <?= htmlspecialchars($job['skills_requirement']) ?></p>
                <p><i class="bi bi-calendar-event me-1 text-warning"></i><strong>Appointment:</strong>
                  <?php if (!empty($job['appointment_date']) && !empty($job['appointment_time'])): ?>
                    <?= htmlspecialchars($job['appointment_date']) ?> @ <?= htmlspecialchars($job['appointment_time']) ?>
                  <?php else: ?>
                    <span class="text-light">Not yet scheduled</span>
                  <?php endif; ?>
                </p>
                <p><i class="bi bi-info-circle me-1 text-warning"></i><strong>Status:</strong>
                  <?php if ($job['appointment_status'] === 'Completed'): ?>
                    <span class="badge bg-success">Completed</span>
                  <?php else: ?>
                    <span class="badge bg-warning"><?= htmlspecialchars($job['appointment_status'] ?? $job['status']) ?></span>
                  <?php endif; ?>
                </p>

                <div class="mt-3">
                  <?php if (!empty($job['appointment_id']) && $job['appointment_status'] !== 'Completed'): ?>
                    <form action="mark_complete.php" method="POST">
                      <input type="hidden" name="appointment_id" value="<?= $job['appointment_id'] ?>">
                      <button class="btn btn-outline-warning btn-sm rounded-pill px-3" type="submit">
                        <i class="bi bi-check-circle me-1"></i> Mark Complete
                      </button>
                    </form>
                  <?php elseif (empty($job['appointment_id'])): ?>
                    <a href="set_appointment.php?jobpost_id=<?= $job['jobpost_id'] ?>" class="btn btn-outline-success btn-sm rounded-pill px-3">
                      <i class="bi bi-calendar-plus me-1"></i> Set Appointment
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="alert alert-warning mt-5">
        <i class="bi bi-info-square-fill me-2"></i>
        Appointments help ensure structure and access—set them thoughtfully to reflect platform integrity.
      </div>
    </div>
</body>
</html>
