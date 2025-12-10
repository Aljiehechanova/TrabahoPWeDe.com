<?php
session_start();
include '../config/db_connection.php';

// Filter days
$filter_days = 1;
if (isset($_GET['days']) && is_numeric($_GET['days']) && $_GET['days'] >= 1 && $_GET['days'] <= 15) {
    $filter_days = (int)$_GET['days'];
}
$time_limit = date('Y-m-d H:i:s', strtotime("-{$filter_days} days"));

// Fetch logs
$logs_query = $conn->prepare("SELECT * FROM users WHERE created_at >= ? ORDER BY created_at DESC");
$logs_query->execute([$time_limit]);
$logs = $logs_query->fetchAll(PDO::FETCH_ASSOC);

// Count metrics
$new_job_seeker = $conn->query("SELECT COUNT(*) FROM users WHERE created_at >= '$time_limit' AND user_type='job_seeker'")->fetchColumn();
$new_clients = $conn->query("SELECT COUNT(*) FROM users WHERE created_at >= '$time_limit' AND user_type='client'")->fetchColumn();
$new_jobs = $conn->query("SELECT COUNT(*) FROM jobpost WHERE created_at >= '$time_limit'")->fetchColumn();
$new_workshops = $conn->query("SELECT COUNT(*) FROM workshop WHERE entry_date >= '$time_limit'")->fetchColumn();

// Workshop details
$ws_query = $conn->prepare("SELECT * FROM workshop WHERE entry_date >= ? ORDER BY entry_date DESC");
$ws_query->execute([$time_limit]);
$workshops = $ws_query->fetchAll(PDO::FETCH_ASSOC);

// Dummy profile (replace later with session values if needed)
$admin_name = $_SESSION['user_name'] ?? "Admin User";
$admin_img = "../assets/images/alterprofile.png";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>System Logs - Trabaho PWeDe</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
      padding-top: 80px; /* below navbar */
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

    .main-content { margin-left: 250px; padding: 20px; }

    @media (max-width: 992px) {
      #sidebar { display: none; }
      .main-content { margin-left: 0; }
    }

    .navbar-profile-img {
      width: 38px; height: 38px;
      border-radius: 50%; object-fit: cover;
      border: 2px solid #FFD700;
    }

    /* Dashboard cards */
    .dashboard-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }
    .card {
      border-radius: 12px;
      color: #FFD700;
      background: #002147;
      box-shadow: 0 4px 12px rgba(0,0,0,0.5);
    }

    /* Tables */
    .table thead th {
      background-color: #00152e;
      color: #FFD700;
      text-transform: uppercase;
    }
    .table tbody td {
      background: #fff;
      color: #000;
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
      <li><a href="approve_job.php"><i class="bi bi-briefcase"></i> Approval Job List</a></li>
      <li><a href="adme.php"class="active"><i class="bi bi-gear"></i> System Log</a></li>
    </ul>
  </nav>
</div>

  <!-- Main content -->
  <div class="main-content">
    <form method="GET" class="d-flex justify-content-end align-items-center gap-2 mb-4">
      <label class="form-label mb-0 text-white">Show last</label>
      <select name="days" class="form-select w-auto" onchange="this.form.submit()">
        <?php for ($i = 1; $i <= 15; $i++): ?>
          <option value="<?= $i ?>" <?= ($filter_days == $i) ? 'selected' : '' ?>><?= $i ?> day<?= ($i > 1 ? 's' : '') ?></option>
        <?php endfor; ?>
      </select>
    </form>

    <div class="dashboard-cards">
      <div class="card p-3"><h6>New Job Seekers</h6><h4><?= $new_job_seeker ?></h4></div>
      <div class="card p-3"><h6>New Clients</h6><h4><?= $new_clients ?></h4></div>
      <div class="card p-3"><h6>Job Posts</h6><h4><?= $new_jobs ?></h4></div>
      <div class="card p-3"><h6>New Workshops</h6><h4><?= $new_workshops ?></h4></div>
    </div>

    <h5 class="text-warning mb-3">User Registration Logs</h5>
    <div class="table-responsive card p-3 mb-4">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>User ID</th>
            <th>Fullname</th>
            <th>Email</th>
            <th>User Type</th>
            <th>Location</th>
            <th>Created At</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($logs) > 0): ?>
            <?php foreach ($logs as $log): ?>
              <tr>
                <td><?= htmlspecialchars($log['user_id']) ?></td>
                <td><?= htmlspecialchars($log['fullname']) ?></td>
                <td><?= htmlspecialchars($log['email']) ?></td>
                <td><?= htmlspecialchars($log['user_type']) ?></td>
                <td><?= htmlspecialchars($log['location']) ?></td>
                <td><?= htmlspecialchars($log['created_at']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-center text-muted">No user logs found in this range.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <h5 class="text-warning mb-3">Workshop Logs</h5>
    <div class="table-responsive card p-3">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Workshop ID</th>
            <th>Title</th>
            <th>Host</th>
            <th>Location</th>
            <th>Entry Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($workshops) > 0): ?>
            <?php foreach ($workshops as $ws): ?>
              <tr>
                <td><?= htmlspecialchars($ws['workshop_id']) ?></td>
                <td><?= htmlspecialchars($ws['work_title']) ?></td>
                <td><?= htmlspecialchars($ws['hostname']) ?></td>
                <td><?= htmlspecialchars($ws['location']) ?></td>
                <td><?= htmlspecialchars($ws['entry_date']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-center text-muted">No workshop logs found in this range.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>
