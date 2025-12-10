<?php
session_start();
require '../config/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$client_id = $_SESSION['user_id'];

// Fetch client info
$stmt = $conn->prepare("SELECT fullname, email, img FROM users WHERE user_id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    die("Client not found.");
}

$loggedInEmail = $client['email'];
$profileImage = (!empty($client['img']) && file_exists(__DIR__ . "/../uploads/" . $client['img']))
    ? "../uploads/" . htmlspecialchars($client['img'])
    : "../assets/images/alterprofile.png";

// Fetch messages
$stmt = $conn->prepare("SELECT sender_email, subject, message FROM messages WHERE receiver_email = ? ORDER BY messages_id DESC");
$stmt->execute([$loggedInEmail]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Client Inbox - Trabaho PWeDe</title>
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

    /* Profile */
    .navbar-profile-img {
      width: 38px; height: 38px;
      border-radius: 50%; object-fit: cover;
      border: 2px solid #FFD700;
    }

    /* Messages */
    .message-card {
      background: #002147;
      color: #FFD700;
      border-radius: 14px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.5);
    }
    .message-card p { margin-bottom: 6px; }
    .btn-custom {
      background-color: #FFD700;
      color: #002147;
      font-weight: 600;
      border-radius: 8px;
    }
    .btn-custom:hover {
      background-color: #e6c200;
      color: #001530;
    }
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
        <li class="nav-item"><a class="nav-link active" href="clientM.php"><i class="bi bi-envelope"></i> Inbox</a></li>
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
      <li><a href="clientM.php" class="active"><i class="bi bi-envelope"></i> Inbox</a></li>
    </ul>
  </nav>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
  <h1 class="text-dark mb-4">Inbox</h1>
  <button class="btn btn-custom mb-3" data-bs-toggle="modal" data-bs-target="#sendModal">
    <i class="bi bi-pencil-square me-1"></i> Compose Message
  </button>

  <?php if (!empty($messages)): ?>
    <?php foreach ($messages as $msg): ?>
      <div class="message-card mb-3">
        <p><strong>From:</strong> <?= htmlspecialchars($msg['sender_email']) ?></p>
        <p><strong>Subject:</strong> <?= htmlspecialchars($msg['subject']) ?></p>
        <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
        <a href="https://mail.google.com/mail/?view=cm&to=<?= urlencode($msg['sender_email']) ?>&su=<?= urlencode('RE: ' . $msg['subject']) ?>"
           target="_blank"
           class="btn btn-custom btn-sm mt-2">
           <i class="bi bi-reply-fill me-1"></i> Reply via Gmail
        </a>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-dark">No messages found.</p>
  <?php endif; ?>
</div>

<!-- COMPOSE MODAL -->
<div class="modal fade" id="sendModal" tabindex="-1" aria-labelledby="sendModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="CSM.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="sendModalLabel">Compose Message</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body px-4 py-3">
          <div class="mb-3">
            <label for="receiver_email" class="form-label fw-semibold">Send To (Email)</label>
            <input type="email" name="receiver_email" id="receiver_email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="subject" class="form-label fw-semibold">Subject</label>
            <input type="text" name="subject" id="subject" class="form-control">
          </div>
          <div class="mb-3">
            <label for="message" class="form-label fw-semibold">Message</label>
            <textarea name="message" id="message" rows="5" class="form-control" required></textarea>
          </div>
          <input type="hidden" name="sender_email" value="<?= htmlspecialchars($loggedInEmail) ?>">
        </div>
        <div class="modal-footer px-4 pb-4">
          <button type="submit" class="btn btn-custom px-4 me-2"><i class="bi bi-send-fill me-1"></i> Send</button>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
