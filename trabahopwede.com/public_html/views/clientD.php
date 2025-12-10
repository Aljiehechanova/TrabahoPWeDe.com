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

// Fetch client info
try {
    $stmt = $conn->prepare("SELECT fullname, img FROM users WHERE user_id = ?");
    $stmt->execute([$client_id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        die("Client not found.");
    }

    // Dashboard metrics
    $pwdStmt = $conn->query("SELECT COUNT(*) FROM users WHERE user_type = 'job_seeker'");
    $totalPWD = $pwdStmt->fetchColumn();

    $workshopStmt = $conn->query("SELECT COUNT(*) FROM workshop_volunteers");
    $totalVolunteers = $workshopStmt->fetchColumn();

    // Prepare and execute query to get the most common job title
    $stmt = $conn->prepare("SELECT jobpost_title AS job_title, COUNT(*) AS count FROM jobpost GROUP BY jobpost_title ORDER BY count DESC LIMIT 1");
    $stmt->execute();
    $commonJob = $stmt->fetch(PDO::FETCH_ASSOC);

    $clientJobCountStmt = $conn->prepare("SELECT COUNT(*) FROM jobpost WHERE user_id = ?");
    $clientJobCountStmt->execute([$client_id]);
    $clientJobCount = $clientJobCountStmt->fetchColumn();

    // Define variables for display with fallback values
    $jobpost_title = isset($commonJob['job_title']) ? $commonJob['job_title'] : 'N/A';
    // Chart or Card: Most Required Skill
try {
    $stmt = $conn->prepare("SELECT skills_requirement, COUNT(*) AS count FROM jobpost WHERE skills_requirement IS NOT NULL AND skills_requirement != '' GROUP BY skills_requirement ORDER BY count DESC LIMIT 1");
    $stmt->execute();
    $topSkill = $stmt->fetch(PDO::FETCH_ASSOC);

    $skills_requirement = $topSkill ? $topSkill['skills_requirement'] : 'N/A';
} catch (PDOException $e) {
    echo "Error fetching most required skill: " . $e->getMessage();
    $skills_requirement = 'N/A';
}


    // Chart 1: Job Posts by PWD Category
    $stmt = $conn->prepare("SELECT disability_requirement, COUNT(*) AS count FROM jobpost GROUP BY disability_requirement");
    $stmt->execute();
    $pwdData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Chart 2: Job Posts Over Time
    $stmt = $conn->prepare("SELECT DATE(created_at) AS post_date, COUNT(*) AS count FROM jobpost GROUP BY post_date ORDER BY post_date ASC");
    $stmt->execute();
    $overtimeData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Chart 3: Most Common Job Titles
    $stmt = $conn->prepare("SELECT jobpost_title, COUNT(*) AS count FROM jobpost GROUP BY jobpost_title ORDER BY count DESC LIMIT 5");
    $stmt->execute();
    $jobTitleData = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Data fetch error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Client Dashboard - Trabaho PWeDe</title>
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
    #sidebar nav ul li a:hover {
      background: #FFD700;
      color: #002147 !important;
    }

    .main-content { margin-left: 250px; padding: 20px; }

    @media (max-width: 992px) {
      #sidebar { display: none; }
      .main-content { margin-left: 0; }
    }

    /* Navbar profile */
    .navbar-profile-img {
      width: 38px; height: 38px;
      border-radius: 50%; object-fit: cover;
      border: 2px solid #FFD700;
    }

    /* Dashboard cards */
    .dashboard-card {
      display: flex; align-items: center;
      border-radius: 14px; background: #002147;
      color: #FFD700; box-shadow: 0 3px 6px rgba(0,0,0,0.4);
      padding: 20px; transition: transform 0.2s ease-in-out;
    }
    .dashboard-card:hover { transform: scale(1.02); }

    .overview-box {
      background: #002147; border-radius: 14px;
      padding: 20px; color: #FFD700; text-align: center;
      box-shadow: 0 3px 6px rgba(0,0,0,0.4);
    }

    .card.shadow {
      background-color: #002147; border: none;
      color: #FFD700; border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.5);
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
      <li><a class="dropdown-item text-danger" href="../views/login.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
    </ul>
  </div>
</div>


  </div>
</nav>

<!-- SIDEBAR (desktop) -->
<div id="sidebar" class="d-none d-lg-block">
  <nav>
    <ul>
      <li><a href="clientD.php" class="active"><i class="bi bi-graph-up"></i> Dashboard</a></li>
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
        <button class="btn btn-success mb-3" onclick="exportFullDashboard()">
  <i class="bi bi-file-earmark-pdf"></i> Export Dashboard PDF
</button>
  <div class="row mb-4 g-4">
    <div class="col-md-6">
      <div class="dashboard-card">
        <i class="bi bi-people-fill fs-2 me-3"></i>
        <div>
          <h6>Total PWD Users</h6>
          <p class="fs-4 fw-bold"><?= $totalPWD ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="dashboard-card">
        <i class="bi bi-hammer fs-2 me-3"></i>
        <div>
          <h6>Workshop Volunteers</h6>
          <p class="fs-4 fw-bold"><?= $totalVolunteers ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-4 g-4">
    <div class="col-md-6">
      <div class="dashboard-card">
        <i class="bi bi-briefcase-fill fs-2 me-3"></i>
        <div>
          <h6>Most Common Job Offered</h6>
          <p class="fs-5 fw-bold"><?= htmlspecialchars($jobpost_title) ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="dashboard-card">
        <i class="bi bi-tools fs-2 me-3"></i>
        <div>
          <h6>Most Required Skill</h6>
          <p class="fs-5 fw-bold"><?= htmlspecialchars($skills_requirement) ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="overview-box mb-4">
    <h4 class="fw-bold">Client Job Overview</h4>
    <i class="bi bi-list-check fs-2"></i>
    <p class="display-6 fw-bold"><?= $clientJobCount ?> Jobs</p>
  </div>

  <h3 class="text-center mt-5 mb-4">Job Post Analytics Overview</h3>
  <div class="row justify-content-center g-4">
    <div class="col-lg-8">
      <div class="card shadow rounded-4">
        <div class="card-body">
          <canvas id="pwdCategoryChart"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-8">
      <div class="card shadow rounded-4">
        <div class="card-body">
          <canvas id="jobOverTimeChart"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-8">
      <div class="card shadow rounded-4 mb-5">
        <div class="card-body">
          <canvas id="jobTitlesChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const chartColors = {
    blueShades: ['#003366', '#004080', '#0059b3', '#0073e6', '#3399ff'],
    goldShades: ['#FFD700', '#FFC300', '#FFB000', '#FFA000', '#FF8C00']
  };

  new Chart(document.getElementById('pwdCategoryChart'), {
    type: 'bar',
    data: {
      labels: <?= json_encode(array_column($pwdData, 'disability_requirement')) ?>,
      datasets: [{
        label: 'PWD Category',
        data: <?= json_encode(array_column($pwdData, 'count')) ?>,
        backgroundColor: chartColors.blueShades,
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { labels: { color: '#fff' } } },
      scales: {
        x: { ticks: { color: '#FFD700' } },
        y: { ticks: { color: '#FFD700' }, beginAtZero: true }
      }
    }
  });

  new Chart(document.getElementById('jobOverTimeChart'), {
    type: 'line',
    data: {
      labels: <?= json_encode(array_column($overtimeData, 'post_date')) ?>,
      datasets: [{
        label: 'Job Posts Over Time',
        data: <?= json_encode(array_column($overtimeData, 'count')) ?>,
        fill: true,
        backgroundColor: 'rgba(255, 215, 0, 0.3)',
        borderColor: '#FFD700',
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { labels: { color: '#fff' } } },
      scales: {
        x: { ticks: { color: '#FFD700' } },
        y: { ticks: { color: '#FFD700' }, beginAtZero: true }
      }
    }
  });

  new Chart(document.getElementById('jobTitlesChart'), {
    type: 'bar',
    data: {
      labels: <?= json_encode(array_column($jobTitleData, 'jobpost_title')) ?>,
      datasets: [{
        label: 'Top Job Titles',
        data: <?= json_encode(array_column($jobTitleData, 'count')) ?>,
        backgroundColor: chartColors.goldShades,
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { labels: { color: '#fff' } } },
      scales: {
        x: { ticks: { color: '#FFD700' } },
        y: { ticks: { color: '#FFD700' }, beginAtZero: true }
      }
    }
  });
  async function exportFullDashboard() {
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF({ orientation: 'portrait', unit: 'pt', format: 'a4' });

    const element = document.querySelector('.main-content');
    const canvas = await html2canvas(element, { scale: 2, useCORS: true });
    const imgData = canvas.toDataURL('image/png');

    // Get page size
    const pageW = pdf.internal.pageSize.getWidth();
    const pageH = pdf.internal.pageSize.getHeight();
    const margin = 20;

    // Scale dashboard to fit in one page
    const imgProps = pdf.getImageProperties(imgData);
    let imgW = pageW - margin * 2;
    let imgH = (imgProps.height * imgW) / imgProps.width;

    if (imgH > pageH - margin * 2) {
      imgH = pageH - margin * 2;
      imgW = (imgProps.width * imgH) / imgProps.height;
    }

    const x = (pageW - imgW) / 2;
    const y = margin;

    pdf.addImage(imgData, 'PNG', x, y, imgW, imgH);

    // Footer
    pdf.setFontSize(10);
    pdf.setTextColor(120);
    pdf.text(`Generated: ${new Date().toLocaleString()}`, margin, pageH - 10);

    pdf.save(`Dashboard_Report_${new Date().toISOString().slice(0,10)}.pdf`);
  }
</script>
</body>
</html>
