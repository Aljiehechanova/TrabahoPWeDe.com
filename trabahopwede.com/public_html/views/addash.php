<?php
session_start();
require_once '../config/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$client_id = $_SESSION['user_id'];

/* ---------------------------
   Fetch profile + basic data
   --------------------------- */
try {
    $stmt = $conn->prepare("SELECT fullname, img, email FROM users WHERE user_id = ?");
    $stmt->execute([$client_id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) die("Client not found.");

    $profileImage = (!empty($client['img']) && file_exists(__DIR__ . "/../uploads/" . $client['img']))
        ? "../uploads/" . htmlspecialchars($client['img'])
        : "../assets/images/alterprofile.png";
} catch (PDOException $e) {
    die("Profile fetch error: " . $e->getMessage());
}

/* ---------------------------
   Dashboard SQL queries
   --------------------------- */
try {
    // all job posts (with poster name)
    $stmt = $conn->prepare("
        SELECT jp.jobpost_id, jp.jobpost_title, jp.disability_requirement,
               DATE(jp.created_at) AS post_date,
               u.user_id AS poster_id, u.fullname AS poster_name
        FROM jobpost jp
        LEFT JOIN users u ON jp.user_id = u.user_id
        ORDER BY jp.created_at ASC
    ");
    $stmt->execute();
    $allJobPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $allJobPosts = [];
}

try {
    $stmt = $conn->query("SELECT COUNT(*) FROM users WHERE user_type = 'job_seeker'");
    $totalApplicants = (int)$stmt->fetchColumn();
} catch (PDOException $e) {
    $totalApplicants = 0;
}

try {
    $stmt = $conn->query("SELECT COUNT(*) FROM workshop_volunteers");
    $totalVolunteers = (int)$stmt->fetchColumn();
} catch (PDOException $e) {
    $totalVolunteers = 0;
}

try {
    $stmt = $conn->prepare("SELECT jobpost_title, COUNT(*) AS cnt FROM jobpost GROUP BY jobpost_title ORDER BY cnt DESC LIMIT 5");
    $stmt->execute();
    $jobTitleData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $jobTitleData = [];
}

/* Build aggregated datasets and name maps in PHP for JS */
$pwdCounts = [];           // label => count
$pwdNamesMap = [];         // label => [names...]
$overtimeCounts = [];      // date => count
$overtimeNamesMap = [];    // date => [title...]
$jobTitleLabels = [];
$jobTitleCounts = [];

foreach ($jobTitleData as $jt) {
    $jobTitleLabels[] = $jt['jobpost_title'];
    $jobTitleCounts[] = (int)$jt['cnt'];
}

// Iterate job posts
foreach ($allJobPosts as $post) {
    $dis = $post['disability_requirement'] ?? 'N/A';
    $dis = ($dis === '' ? 'N/A' : $dis);

    // count per disability
    if (!isset($pwdCounts[$dis])) $pwdCounts[$dis] = 0;
    $pwdCounts[$dis]++;

    // collect poster names (avoid empty)
    $posterName = $post['poster_name'] ?: ($post['jobpost_title'] ?: 'Unknown');
    $pwdNamesMap[$dis][] = $posterName;

    // posts over time
    $date = $post['post_date'] ?? 'N/A';
    if (!isset($overtimeCounts[$date])) $overtimeCounts[$date] = 0;
    $overtimeCounts[$date]++;
    $overtimeNamesMap[$date][] = $post['jobpost_title'] ?: $posterName;
}

// Make counts arrays for JS (sorted by disability label)
$pwdLabels = array_keys($pwdCounts);
$pwdCountsVals = array_values($pwdCounts);

// Sort overtime by date ascending (dates are keys)
ksort($overtimeCounts);
$overtimeLabels = array_keys($overtimeCounts);
$overtimeCountsVals = array_values($overtimeCounts);

/* Volunteers: counts per year-month + names per year-month */
$volRows = [];
try {
    // fetch workshop_volunteers with user name
    $stmt = $conn->prepare("
        SELECT w.*, DATE(w.created_at) AS vol_date,
               YEAR(w.created_at) AS vol_year, MONTH(w.created_at) AS vol_month,
               u.user_id, u.fullname
        FROM workshop_volunteers w
        LEFT JOIN users u ON w.user_id = u.user_id
        ORDER BY w.created_at ASC
    ");
    $stmt->execute();
    $volRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $volRows = [];
}

$volByYearCounts = [];   // year => [1..12] counts
$volNamesMap = [];       // year => monthIndex(0..11) => [names...]

// initialize with some recent years so dropdown isn't empty
$defaultYears = ['2025','2024','2023'];
foreach ($defaultYears as $y) {
    if (!isset($volByYearCounts[$y])) $volByYearCounts[$y] = array_fill(0, 12, 0);
    if (!isset($volNamesMap[$y])) $volNamesMap[$y] = array_fill(0, 12, []);
}

foreach ($volRows as $r) {
    $yr = (string)($r['vol_year'] ?: date('Y'));
    $m = (int)($r['vol_month'] ?: 1); // 1-12
    if (!isset($volByYearCounts[$yr])) $volByYearCounts[$yr] = array_fill(0, 12, 0);
    if (!isset($volNamesMap[$yr])) $volNamesMap[$yr] = array_fill(0, 12, []);
    $volByYearCounts[$yr][$m-1] += 1;
    $volNamesMap[$yr][$m-1][] = $r['fullname'] ?: 'Volunteer';
}

// Convert volByYearCounts into Chart.js friendly objects
$volByYearForJS = [];
foreach ($volByYearCounts as $y => $months) {
    $volByYearForJS[$y] = [
        'labels' => ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
        'datasets' => [[ 'label' => 'Volunteers', 'data' => array_map('intval', $months) ]]
    ];
}

/* Safety: ensure arrays exist (never undefined in JS) */
if (empty($pwdLabels)) {
    $pwdLabels = ['N/A'];
    $pwdCountsVals = [0];
    $pwdNamesMap = ['N/A' => []];
}
if (empty($overtimeLabels)) {
    $overtimeLabels = [];
    $overtimeCountsVals = [];
    $overtimeNamesMap = [];
}

/* Encode to JSON for JS */
$js_pwdLabels = json_encode(array_values($pwdLabels));
$js_pwdCounts = json_encode(array_values($pwdCountsVals));
$js_pwdNamesMap = json_encode($pwdNamesMap);

$js_overtimeLabels = json_encode(array_values($overtimeLabels));
$js_overtimeCounts = json_encode(array_values($overtimeCountsVals));
$js_overtimeNamesMap = json_encode($overtimeNamesMap);

$js_jobTitleLabels = json_encode($jobTitleLabels);
$js_jobTitleCounts = json_encode($jobTitleCounts);

$js_volByYearForJS = json_encode($volByYearForJS);
$js_volNamesMap = json_encode($volNamesMap);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard - Trabaho PWeDe</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Chart.js, html2canvas, jsPDF -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

  <style>
    /* Theme copied from client dashboard */
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

    /* Buttons */
    .btn-primary {
      background-color: #FFD700;
      color: #002147;
      border: none;
      font-weight: 600;
    }
    .btn-primary:hover { background-color: #FFC300; color:#002147; }
    .small-panel { background: #fff; padding: 12px; border-radius: 8px; }
    .list-box { max-height: 320px; overflow:auto; }
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
      <li><a href="addash.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
      <li><a href="approve_job.php"><i class="bi bi-briefcase"></i> Approval Job List</a></li>
      <li><a href="adme.php"><i class="bi bi-gear"></i> System Log</a></li>
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
          <h6>Total Applicants</h6>
          <p class="fs-4 fw-bold"><?= $totalApplicants ?></p>
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

  <div class="overview-box mb-4">
    <h4 class="fw-bold">Admin Overview</h4>
    <i class="bi bi-list-check fs-2"></i>
    <p class="display-6 fw-bold"><?= count($jobTitleData) ?> Job Types</p>
  </div>

  <h3 class="text-center mt-5 mb-4">Analytics Overview</h3>
  <div class="row justify-content-center g-4">
    <!-- PWD Pie -->
    <div class="col-lg-8">
      <div class="card shadow rounded-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Applicants by Disability Type</h5>
            <div>
              <button class="btn btn-primary me-2" onclick="openReportModal('disability')"><i class="bi bi-file-earmark-text"></i> Generate Report</button>
            </div>
          </div>
          <canvas id="pwdPieChart"></canvas>
          <div id="pwdLegend" class="mt-3"></div>
          <div class="mt-3 small-panel">
            <strong>Names (click a slice)</strong>
            <ul id="userListContent" class="list-unstyled mb-0 list-box"></ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Posts Over Time -->
    <div class="col-lg-8">
      <div class="card shadow rounded-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Job Posts Over Time</h5>
          </div>
          <canvas id="jobOverTimeChart"></canvas>
          <div class="mt-3 small-panel">
            <strong>Posts on date (click a point)</strong>
            <ul id="overtimeListContent" class="list-unstyled mb-0 list-box"></ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Volunteers by Month -->
    <div class="col-lg-8">
      <div class="card shadow rounded-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Workshop Volunteers by Month</h5>
            <div class="d-flex align-items-center gap-2">
              <label class="mb-0 me-2">Year:</label>
              <select id="volunteerYear" onchange="filterVolunteersByYear()"></select>
              <button class="btn btn-primary ms-2" onclick="openReportModal('volunteer')"><i class="bi bi-file-earmark-text"></i> Generate Report</button>
            </div>
          </div>
          <canvas id="volunteerBarChart"></canvas>
          <div class="mt-3 small-panel">
            <strong>Volunteers (click a bar)</strong>
            <ul id="volunteerListContent" class="list-unstyled mb-0 list-box"></ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Top Job Titles -->
    <div class="col-lg-8">
      <div class="card shadow rounded-4 mb-5">
        <div class="card-body">
          <h5 class="mb-3">Top Job Titles</h5>
          <canvas id="jobTitlesChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END main content -->

<!-- REPORT PREVIEW MODAL -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="reportModalTitle">Generated Report</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="reportPreview">
        <!-- filled dynamically -->
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-success" id="reportDownloadBtn"><i class="bi bi-download"></i> Download PDF</button>
      </div>
    </div>
  </div>
</div>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* =========================
   Data passed from PHP
   ========================= */
const pwdLabels = <?= $js_pwdLabels ?>;
const pwdCounts = <?= $js_pwdCounts ?>;
const pwdNamesMap = <?= $js_pwdNamesMap ?>;

const overtimeLabels = <?= $js_overtimeLabels ?>;
const overtimeCounts = <?= $js_overtimeCounts ?>;
const overtimeNamesMap = <?= $js_overtimeNamesMap ?>;

const jobTitleLabels = <?= $js_jobTitleLabels ?>;
const jobTitleCounts = <?= $js_jobTitleCounts ?>;

const volByYear = <?= $js_volByYearForJS ?>;     // object: year -> { labels: [...], datasets: [...] }
const volNamesMap = <?= $js_volNamesMap ?>;      // object: year -> [ [names for Jan], [names for Feb], ... ]

/* Chart instances */
let pwdPieChart, jobOverTimeChart, volunteerBarChart, jobTitlesChart;

/* Color palettes */
const blueShades = ['#003366','#004080','#0059b3','#0073e6','#3399ff','#66b2ff','#99ccff','#cce6ff'];
const goldShades = ['#FFD700','#FFC300','#FFB000','#FFA000','#FF8C00'];

/* ---------- Build PWD pie chart ---------- */
function buildPwdPie() {
  const ctx = document.getElementById('pwdPieChart').getContext('2d');
  if (pwdPieChart) pwdPieChart.destroy();
  pwdPieChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: pwdLabels,
      datasets: [{ data: pwdCounts, backgroundColor: blueShades.slice(0, pwdLabels.length) }]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'bottom' } },
      onClick(evt, elements) {
        if (!elements || elements.length === 0) return;
        const idx = elements[0].index;
        const label = this.data.labels[idx];
        showNamesForDisability(label);
      }
    }
  });

  // legend + counts
  const legendEl = document.getElementById('pwdLegend');
  legendEl.innerHTML = '';
  pwdLabels.forEach((l, i) => {
    const color = blueShades[i % blueShades.length];
    const cnt = pwdCounts[i] ?? 0;
    const div = document.createElement('div');
    div.className = 'd-flex align-items-center gap-2 mb-1';
    div.innerHTML = `<div style="width:14px;height:14px;background:${color};border-radius:3px;"></div><small>${l} — <strong>${cnt}</strong></small>`;
    legendEl.appendChild(div);
  });
}

function showNamesForDisability(label) {
  const list = document.getElementById('userListContent');
  list.innerHTML = '';
  const arr = (pwdNamesMap[label] && Array.isArray(pwdNamesMap[label])) ? [...new Set(pwdNamesMap[label])] : [];
  if (arr.length === 0) {
    list.innerHTML = '<li class="text-muted">No names</li>';
    return;
  }
  arr.forEach(name => {
    const li = document.createElement('li');
    li.textContent = name;
    list.appendChild(li);
  });
}

/* ---------- Job posts over time (line) ---------- */
function buildJobOverTime() {
  const ctx = document.getElementById('jobOverTimeChart').getContext('2d');
  if (jobOverTimeChart) jobOverTimeChart.destroy();
  jobOverTimeChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: overtimeLabels,
      datasets: [{
        label: 'Job Posts',
        data: overtimeCounts,
        fill: true,
        backgroundColor: 'rgba(255, 215, 0, 0.18)',
        borderColor: '#FFD700',
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      onClick(evt, elements) {
        if (!elements || elements.length === 0) return;
        const idx = elements[0].index;
        const dateLabel = this.data.labels[idx];
        showPostsForDate(dateLabel);
      }
    }
  });
}

function showPostsForDate(dateLabel) {
  const list = document.getElementById('overtimeListContent');
  list.innerHTML = '';
  const arr = (overtimeNamesMap[dateLabel] && Array.isArray(overtimeNamesMap[dateLabel])) ? overtimeNamesMap[dateLabel] : [];
  if (arr.length === 0) {
    list.innerHTML = '<li class="text-muted">No posts</li>';
    return;
  }
  arr.forEach(title => {
    const li = document.createElement('li');
    li.textContent = title;
    list.appendChild(li);
  });
}

/* ---------- Volunteers bar chart ---------- */
function buildVolunteerChartForYear(year) {
  const ctx = document.getElementById('volunteerBarChart').getContext('2d');
  const data = volByYear[year] ?? {
    labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
    datasets: [{ label: 'Volunteers', data: Array(12).fill(0) }]
  };
  if (volunteerBarChart) volunteerBarChart.destroy();
  volunteerBarChart = new Chart(ctx, {
    type: 'bar',
    data: data,
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      onClick(evt, elements) {
        if (!elements || elements.length === 0) return;
        const idx = elements[0].index; // month index
        showVolunteersForMonth(year, idx);
      }
    }
  });
}

function showVolunteersForMonth(year, monthIndexZeroBased) {
  const list = document.getElementById('volunteerListContent');
  list.innerHTML = '';
  const names = (volNamesMap[year] && Array.isArray(volNamesMap[year][monthIndexZeroBased])) ? volNamesMap[year][monthIndexZeroBased] : [];
  const uniq = [...new Set(names)];
  if (uniq.length === 0) {
    list.innerHTML = '<li class="text-muted">No volunteers</li>';
    return;
  }
  uniq.forEach(n => {
    const li = document.createElement('li');
    li.textContent = n;
    list.appendChild(li);
  });
}

function filterVolunteersByYear() {
  const sel = document.getElementById('volunteerYear');
  const year = sel.value;
  buildVolunteerChartForYear(year);
}

/* ---------- Top job titles ---------- */
function buildJobTitlesChart() {
  const ctx = document.getElementById('jobTitlesChart').getContext('2d');
  if (jobTitlesChart) jobTitlesChart.destroy();
  jobTitlesChart = new Chart(ctx, {
    type: 'bar',
    data: { labels: jobTitleLabels, datasets: [{ label: 'Top Job Titles', data: jobTitleCounts, backgroundColor: goldShades.slice(0, jobTitleLabels.length) }] },
    options: { responsive: true, plugins: { legend: { display: false } } }
  });
}

/* ---------- Report generation (preview + download) ---------- */
let lastReportPreviewElement = null;

async function openReportModal(type='disability') {
  const modalEl = document.getElementById('reportModal');
  const modal = new bootstrap.Modal(modalEl);
  const modalBody = document.getElementById('reportPreview');
  const title = (type === 'disability') ? 'Applicants by Disability Report' : (type === 'volunteer' ? 'Volunteer Activity Report' : 'Full Analytics Report');
  document.getElementById('reportModalTitle').textContent = title;
  modalBody.innerHTML = ''; // clear

  // Header summary block
  const header = document.createElement('div');
  header.innerHTML = `
    <div class="text-center mb-3">
      <img src="../assets/images/TrabahoPWeDeLogo.png" width="60" alt="logo">
      <h3 style="color:#002147">Trabaho PWeDe</h3>
      <div><small>${new Date().toLocaleString()}</small></div>
    </div>
    <hr>
    <div style="margin-bottom:12px;">
      <strong>Summary</strong>
      <div>Total Applicants: <strong><?= $totalApplicants ?></strong></div>
      <div>Total Volunteers (all time): <strong><?= $totalVolunteers ?></strong></div>
    </div>
  `;
  modalBody.appendChild(header);

  // depending on type, attach relevant chart snapshots & lists
  if (type === 'disability') {
    // include pie chart image & list of all disability groups with counts + sample names
    const pieCanvas = document.getElementById('pwdPieChart');
    const img = document.createElement('img');
    img.src = pieCanvas.toDataURL('image/png');
    img.style.width = '100%';
    modalBody.appendChild(img);

    const listTitle = document.createElement('h5'); listTitle.textContent = 'Names by Disability'; modalBody.appendChild(listTitle);
    const wrap = document.createElement('div');
    for (const lbl of pwdLabels) {
      const section = document.createElement('div');
      section.style.marginBottom = '8px';
      const h = document.createElement('strong'); h.textContent = `${lbl} (${(pwdCounts[pwdLabels.indexOf(lbl)] || 0)})`;
      section.appendChild(h);
      const ul = document.createElement('ul');
      ul.style.marginTop = '6px';
      const arr = pwdNamesMap[lbl] ?? [];
      const uniq = [...new Set(arr)];
      if (uniq.length === 0) {
        const li = document.createElement('li'); li.textContent = 'No names';
        ul.appendChild(li);
      } else {
        uniq.forEach(n => { const li = document.createElement('li'); li.textContent = n; ul.appendChild(li); });
      }
      section.appendChild(ul);
      wrap.appendChild(section);
    }
    modalBody.appendChild(wrap);

  } else if (type === 'volunteer') {
    // include volunteer chart (for currently selected year) + list
    const yearSel = document.getElementById('volunteerYear');
    const year = yearSel ? yearSel.value : Object.keys(volByYear)[0];
    // draw image from chart canvas
    const canvas = document.getElementById('volunteerBarChart');
    const img = document.createElement('img'); img.src = canvas.toDataURL('image/png'); img.style.width = '100%';
    modalBody.appendChild(img);

    const listTitle = document.createElement('h5'); listTitle.textContent = `Volunteers (${year})`; modalBody.appendChild(listTitle);
    const wrap = document.createElement('div');
    const months = (volByYear[year] && volByYear[year].labels) ? volByYear[year].labels : ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
    for (let i = 0; i < months.length; i++) {
      const section = document.createElement('div'); section.style.marginBottom = '6px';
      const h = document.createElement('strong'); h.textContent = `${months[i]}: ${ (volByYear[year] && volByYear[year].datasets && volByYear[year].datasets[0].data[i]) || 0 }`;
      section.appendChild(h);
      const ul = document.createElement('ul');
      const arr = (volNamesMap[year] && volNamesMap[year][i]) ? volNamesMap[year][i] : [];
      const uniq = [...new Set(arr)];
      if (uniq.length === 0) { const li = document.createElement('li'); li.textContent = 'No volunteers'; ul.appendChild(li); }
      else uniq.forEach(n => { const li = document.createElement('li'); li.textContent = n; ul.appendChild(li); });
      section.appendChild(ul);
      wrap.appendChild(section);
    }
    modalBody.appendChild(wrap);

  } else {
    // full analytics: include all charts
    const pieImg = document.createElement('img'); pieImg.src = document.getElementById('pwdPieChart').toDataURL('image/png'); pieImg.style.width='100%'; modalBody.appendChild(pieImg);
    modalBody.appendChild(document.createElement('hr'));
    const volImg = document.createElement('img'); volImg.src = document.getElementById('volunteerBarChart').toDataURL('image/png'); volImg.style.width='100%'; modalBody.appendChild(volImg);
    modalBody.appendChild(document.createElement('hr'));
    const otc = document.createElement('img'); otc.src = document.getElementById('jobOverTimeChart').toDataURL('image/png'); otc.style.width='100%'; modalBody.appendChild(otc);
  }

  lastReportPreviewElement = modalBody; // keep reference for download
  modal.show();

  // wire download button (it will be re-bound each open)
  const downloadBtn = document.getElementById('reportDownloadBtn');
  downloadBtn.onclick = async function () {
    // disable while preparing
    downloadBtn.disabled = true;
    downloadBtn.textContent = 'Preparing...';

    // render the modalBody to canvas
    const canvas = await html2canvas(lastReportPreviewElement, { scale: 2, useCORS: true });
    const imgData = canvas.toDataURL('image/png');

    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF({ orientation: 'portrait', unit: 'pt', format: 'a4' });
    const pageW = pdf.internal.pageSize.getWidth();
    const margin = 36;
    const imgW = pageW - margin * 2;
    const imgProps = pdf.getImageProperties(imgData);
    const imgH = (imgProps.height * imgW) / imgProps.width;
    pdf.addImage(imgData, 'PNG', margin, margin, imgW, imgH, undefined, 'FAST');

    // footer
    pdf.setFontSize(10);
    pdf.setTextColor(120);
    pdf.text(`Generated: ${new Date().toLocaleString()}`, margin, pdf.internal.pageSize.getHeight() - 20);

    const filename = `${title.replace(/\s+/g,'_')}_${new Date().toISOString().slice(0,10)}.pdf`;
    pdf.save(filename);

    downloadBtn.disabled = false;
    downloadBtn.textContent = 'Download PDF';
    // close modal after download
    const bsModal = bootstrap.Modal.getInstance(document.getElementById('reportModal'));
    if (bsModal) bsModal.hide();
  };
}

/* ---------- Initialization ---------- */
document.addEventListener('DOMContentLoaded', function() {
  // populate volunteerYear select
  const yearSel = document.getElementById('volunteerYear');
  yearSel.innerHTML = '';
  const years = Object.keys(volByYear);
  years.sort((a,b) => b - a); // descending
  if (years.length === 0) years.push(new Date().getFullYear().toString());
  years.forEach(y => {
    const opt = document.createElement('option'); opt.value = y; opt.textContent = y;
    yearSel.appendChild(opt);
  });

  // build charts
  buildPwdPie();
  buildJobOverTime();
  buildVolunteerChartForYear(yearSel.value || years[0]);
  buildJobTitlesChart();

  // click outside modal content closes (Bootstrap handles it)
});

/* Expose to global for inline handlers if needed */
window.openReportModal = openReportModal;
window.filterVolunteersByYear = filterVolunteersByYear;
window.showNamesForDisability = showNamesForDisability;
window.showVolunteersForMonth = showVolunteersForMonth;
window.showPostsForDate = showPostsForDate;

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

  // Scale the entire dashboard to fit one A4 page
  const imgProps = pdf.getImageProperties(imgData);
  let imgW = pageW - margin * 2;
  let imgH = (imgProps.height * imgW) / imgProps.width;

  // If the image is taller than page height, shrink it proportionally
  if (imgH > pageH - margin * 2) {
    imgH = pageH - margin * 2;
    imgW = (imgProps.width * imgH) / imgProps.height;
  }

  const x = (pageW - imgW) / 2;
  const y = (pageH - imgH) / 2;

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
