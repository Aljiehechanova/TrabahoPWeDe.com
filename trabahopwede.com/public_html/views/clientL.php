<?php  
session_start();
require_once '../config/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$client_id = $_SESSION['user_id'];

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'client') {
    die("Access denied: Only clients can access this page.");
}

try {
    $stmt = $conn->prepare("SELECT * FROM jobpost WHERE user_id = ?");
    $stmt->execute([$client_id]);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

try {
    $stmt = $conn->prepare("SELECT fullname, img FROM users WHERE user_id = ?");
    $stmt->execute([$client_id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        die("Client not found.");
    }
} catch (PDOException $e) {
    die("Client fetch error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Client Job List - Trabaho PWeDe</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap + Icons -->
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

  .navbar-nav .nav-link {
    color: #fff !important;
    border-radius: 6px;
    padding: 8px 12px;
    transition: 0.3s;
  }
  .navbar-nav .nav-link:hover,
  .navbar-nav .nav-link.active {
    background: #FFD700 !important;
    color: #002147 !important;
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

  /* Job Grid */
  .job-post-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 20px;
    padding: 10px 0;
  }

  /* Job Card */
  .job-card {
    background: #002147;
    color: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.3);
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform 0.3s ease;
  }
  .job-card:hover { transform: translateY(-4px); }
  .job-card-header h5 i { color: #FFD700; }
  .job-card-body p i { color: #FFA500; }

  /* === APPLICANT CARD (ONE ROW) === */
  .applicant-card {
    background: #FFD700;
    color: #002147;
    border-radius: 10px;
    padding: 12px 15px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.2);
    flex-wrap: nowrap;
  }

  .applicant-info {
    display: flex;
    align-items: center;
    gap: 25px;
    flex: 1;
    font-size: 0.95rem;
  }
  .applicant-info strong {
    font-size: 1rem;
    white-space: nowrap;
  }

  /* --- Applicant Actions (fix buttons horizontal) --- */
  .applicant-actions {
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
    align-items: center;
    gap: 15px;
    flex-wrap: nowrap;
  }
  .applicant-actions .btn {
    width: 120px;
    height: 42px;
    font-weight: 600;
    border-radius: 6px;
    font-size: 0.9rem;
    text-align: center;
    white-space: nowrap;
  }

  /* Specific button colors */
  .btn-hire { background: #28a745; color: #fff; }
  .btn-hire:hover { background: #218838; }

  .btn-hold { background: #ff9800; color: #fff; }
  .btn-hold:hover { background: #e68900; }

  .btn-resume { background: #17a2b8; color: #fff; }
  .btn-resume:hover { background: #117a8b; }

  @media (max-width: 900px) {
    .applicant-card { flex-direction: column; align-items: flex-start; }
    .applicant-info { flex-direction: column; align-items: flex-start; gap: 5px; }
    .applicant-actions { width: 100%; justify-content: flex-start; flex-wrap: wrap; }
  }

  /* === MODAL === */
  #matchingModal {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    justify-content: center;
    align-items: center;
  }
  .modal-content {
    background: #002147;
    color: #fff;
    padding: 25px;
    border-radius: 12px;
    width: 95%;
    max-width: 1000px;
    max-height: 85vh;
    overflow-y: auto;
    position: relative;
    box-shadow: 0 6px 14px rgba(0,0,0,0.4);
  }
  .close-btn {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 36px;
    font-weight: bold;
    cursor: pointer;
    color: #FFD700;
  }
  .close-btn:hover { color: #fff; }
</style>

</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center sitename" href="clientD.php">
      <img src="../assets/images/TrabahoPWeDeLogo.png" alt="Logo" width="40" height="40" class="me-2">
      <span class="trabaho fw-bold">Trabaho</span><span class="pwe fw-bold">PWeDe</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMenu">
      <!-- Mobile menu -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-lg-none">
        <li class="nav-item"><a class="nav-link" href="clientD.php"><i class="bi bi-graph-up"></i> Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="clientL.php"><i class="bi bi-briefcase"></i> Job List</a></li>
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
      <li><a href="clientL.php" class="active"><i class="bi bi-briefcase"></i> Job List</a></li>
      <li><a href="clientW.php"><i class="bi bi-easel"></i> Workshops</a></li>
      <li><a href="listofapplicant.php"><i class="bi bi-people"></i> Applicants</a></li>
      <li><a href="posting.php"><i class="bi bi-plus-circle"></i> Posting</a></li>
      <li><a href="clientM.php"><i class="bi bi-envelope"></i> Inbox</a></li>
    </ul>
  </nav>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
  <h1 class="fw-bold text-dark">Job List Posts</h1>
  <div class="job-post-grid">
    <?php if (!empty($jobs)): ?>
      <?php foreach ($jobs as $job): ?>
        <div class="job-card">
          <div class="job-card-header d-flex justify-content-between align-items-center">
            <h5><i class="bi bi-briefcase-fill"></i> <?= htmlspecialchars($job['jobpost_title']) ?></h5>
            <?php
              $statusColor = match ($job['status']) {
                'Open' => 'success',
                'Closed' => 'secondary',
                default => 'dark'
              };
            ?>
            <span class="badge bg-<?= $statusColor ?>">
              <i class="bi bi-lightbulb-fill"></i> <?= htmlspecialchars($job['status']) ?>
            </span>
          </div>
          <div class="job-card-body">
            <p><i class="bi bi-person-wheelchair"></i> <strong>Disability Type:</strong> <?= htmlspecialchars($job['disability_requirement']) ?></p>
            <p><i class="bi bi-tools"></i> <strong>Required Skills:</strong> <?= htmlspecialchars($job['skills_requirement']) ?></p>
            <p><i class="bi bi-stars"></i> <strong>Optional Skills:</strong> <?= htmlspecialchars($job['optional_skills'] ?? 'N/A') ?></p>
          </div>
          <div class="job-card-footer d-flex justify-content-between">
            <button class="btn btn-primary btn-sm" onclick="viewMatches(<?= $job['jobpost_id'] ?>)">
              <i class="bi bi-people-fill"></i> View Matching Applicants
            </button>
            <form method="POST" action="remove_job.php" onsubmit="return confirm('Are you sure you want to delete this job post?');" class="d-inline">
              <input type="hidden" name="jobpost_id" value="<?= $job['jobpost_id'] ?>">
              <button type="submit" class="btn btn-danger btn-sm">
                <i class="bi bi-trash-fill"></i> Remove
              </button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p><i class="bi bi-emoji-frown"></i> No job posts found.</p>
    <?php endif; ?>
  </div>
</div>

<!-- Modal -->
<div id="matchingModal" class="modal">
  <div class="modal-content">
    <span class="close-btn" onclick="closeModal()">&times;</span>
    <div id="modal-body">
      <div id="filter-form" class="mb-3">
        <label for="sortOrder">Sort by Match Score:</label>
        <select id="sortOrder" class="form-select">
          <option value="desc">Highest</option>
          <option value="asc">Lowest</option>
        </select>
        <label for="topLimit" class="mt-2">Show Top:</label>
        <select id="topLimit" class="form-select">
          <option value="5">Top 5</option>
          <option value="10">Top 10</option>
          <option value="15">Top 15</option>
        </select>
        <button class="btn btn-sm btn-primary mt-2" onclick="applyFilter()">Apply Filter</button>
      </div>
      <div id="applicant-results"></div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let currentJobId = null;

function viewMatches(jobId) {
    currentJobId = jobId;
    applyFilter();
    document.getElementById('matchingModal').style.display = 'flex';
}

function applyFilter() {
    const sortOrder = document.getElementById('sortOrder').value;
    const topLimit = document.getElementById('topLimit').value;
    const url = `fmu.php?job_id=${currentJobId}&sort_order=${sortOrder}&top_limit=${topLimit}`;

    fetch(url)
        .then(response => response.text())
        .then(data => {
            document.getElementById('applicant-results').innerHTML = data;
        })
        .catch(error => {
            document.getElementById('applicant-results').innerHTML = "Error fetching applicants.";
        });
}

function closeModal() {
    document.getElementById('matchingModal').style.display = 'none';
    currentJobId = null;
}
</script>
</body>
</html>
