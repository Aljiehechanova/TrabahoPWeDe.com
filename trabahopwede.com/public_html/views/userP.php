<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once(__DIR__ . "/../config/db_connection.php");

$userId = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :id");
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit();
}

// Job Recommendations
$recommendStmt = $conn->prepare("
    SELECT * FROM jobpost 
    WHERE disability_requirement = :disability 
    ORDER BY created_at DESC LIMIT 5
");
$recommendStmt->execute(['disability' => $user['disability']]);
$recommendations = $recommendStmt->fetchAll(PDO::FETCH_ASSOC);

// Workshop Recommendations
$workshopStmt = $conn->prepare("
    SELECT * FROM workshop 
    WHERE target_skills = :disability 
    ORDER BY entry_date DESC LIMIT 5
");
$workshopStmt->execute(['disability' => $user['disability']]);
$workshops = $workshopStmt->fetchAll(PDO::FETCH_ASSOC);

// Profile image fallback
$profileImage = (!empty($user['img']) && file_exists(__DIR__ . "/../uploads/" . $user['img']))
    ? "../uploads/" . htmlspecialchars($user['img'])
    : "https://via.placeholder.com/200?text=Profile";

// Resume path fallback
$resumePath = (!empty($user['resume']) && file_exists(__DIR__ . "/../uploads/resumes/" . $user['resume']))
    ? "../uploads/resumes/" . htmlspecialchars($user['resume'])
    : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #87CEEB; /* Sky Blue */
            padding-top: 80px;
            font-family: 'Segoe UI', sans-serif;
        }
        html { scroll-behavior: smooth; }

        /* ========= NAVBAR ========= */
        .navbar { background-color: #002147 !important; }
        .navbar .nav-link {
            color: #fff !important;
            margin: 0 15px;
            font-weight: 500;
        }
        .navbar .nav-link:hover { color: #FFD700 !important; }
        .navbar-toggler { border: none; }
        .navbar-toggler-icon { filter: invert(1); }

        /* ========= PROFILE IMAGE ========= */
        .profile-img, .navbar-profile-img {
            aspect-ratio: 1 / 1;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #FFD700;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            display: block;
        }
        .profile-img {
            width: 160px;
            max-width: 100%;
            margin: 0 auto 15px;
        }
        .navbar-profile-img { width: 44px; height: 44px; }

        /* ========= DASHBOARD GRID ========= */
        #home .dashboard-grid {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 25px;
        }
        @media (max-width: 992px) {
            #home .dashboard-grid { grid-template-columns: 1fr; }
        }

        /* ========= PROFILE SIDEBAR ========= */
        .profile-card {
            background: linear-gradient(160deg, #002147, #0d2f5a);
            color: #fff;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.5);
            text-align: center;
        }
        .profile-card h2 { color: #FFD700; font-size: 1.3rem; margin-bottom: 10px; }
        .profile-card p { font-size: 0.95rem; margin: 5px 0; text-align: left; }

        /* ========= CONTENT SECTIONS ========= */
        .content-section {
            margin-bottom: 25px;
            background: #002147;
            border-radius: 18px;
            padding: 20px 25px;
            color: #fff;
            box-shadow: 0 4px 14px rgba(0,0,0,0.4);
        }
        .content-section h4 {
            color: #FFD700;
            font-size: 1.25rem;
            margin-bottom: 15px;
            border-left: 6px solid #FFA500;
            padding-left: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ========= JOB & WORKSHOP BOXES ========= */
        .job-box {
            background: #FFD700;
            color: #002147;
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        .job-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.3);
        }

        /* ========= BUTTONS ========= */
        .btn-yellow { background: #FFD700; color: #002147; font-weight: 600; border-radius: 8px; }
        .btn-orange { background: #FFA500; color: #fff; font-weight: 600; border-radius: 8px; }
        .btn-darkblue { background: #002147; color: #fff; font-weight: 600; border-radius: 8px; border: 1px solid #fff; }
        .btn-yellow:hover { background: #e6c200; color: #002147; }
        .btn-orange:hover { background: #e69500; color: #fff; }
        .btn-darkblue:hover { background: #0d305a; color: #fff; }

        /* ========= MESSAGES ========= */
        .message-box {
            background: #fff;
            color: #002147;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.25);
        }

        /* ========= TRACKER ========= */
        .tracker-tile {
            background: #FFD700;
            color: #002147;
            padding: 15px;
            border-radius: 12px;
            font-weight: bold;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            margin-bottom: 10px;
        }
        .tracker-tile i { font-size: 24px; margin-bottom: 5px; display: block; }

        /* ========= SECTIONS VISIBILITY ========= */
        .container.dashboard-section { display: none; }
    </style>
</head>
<body>
<?php include 'accessibility.php'; ?>

<main id="main-content">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">

            <!-- Brand -->
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="../assets/images/TrabahoPWeDeLogo.png" alt="Logo" width="40" height="40" class="me-2">
                <span class="fw-bold text-light">Trabaho</span><span class="fw-bold text-warning">PWeDe</span>
            </a>

            <!-- Mobile toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar links -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-3 me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#resume">Resume</a></li>
                    <li class="nav-item"><a class="nav-link" href="#jobs">Jobs</a></li>
                    <li class="nav-item"><a class="nav-link" href="#workshops">Workshops</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tracker">Tracker</a></li>
                    <li class="nav-item"><a class="nav-link" href="#messages">Messages</a></li>
                </ul>

                <!-- Profile + Settings -->
                <div class="d-flex align-items-center">
                    <img src="<?= $profileImage ?>" alt="Profile" class="navbar-profile-img me-2">
                    <span class="fw-semibold text-light"><?= htmlspecialchars($user['fullname']) ?></span>

                    <div class="dropdown ms-3">
                        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Settings
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="userP.php">Edit Profile</a></li>
                            <li><a class="dropdown-item" href="#">Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="login.php">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </nav>

    <!-- HOME Section -->
    <div class="container dashboard-section" id="home">
        <div class="dashboard-grid">

            <!-- Sidebar -->
            <div class="profile-card">
                <img src="<?= $profileImage ?>" class="profile-img" alt="Profile">
                <h2>Welcome, <?= strtoupper(htmlspecialchars($user['fullname'])) ?></h2>

                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Contact:</strong> <?= $user['contact_number'] ? htmlspecialchars($user['contact_number']) : 'N/A' ?></p>
                <p><strong>Description:</strong> <?= $user['description'] ? htmlspecialchars($user['description']) : 'N/A' ?></p>
                <p><strong>Location:</strong> <?= $user['location'] ? htmlspecialchars($user['location']) : 'N/A' ?></p>
                <p><strong>Disability:</strong> <?= $user['disability'] ? htmlspecialchars($user['disability']) : 'N/A' ?></p>

                <p><strong>Status:</strong>
                    <span class="badge <?= $user['is_hiring_enabled'] ? 'bg-success' : 'bg-secondary' ?>">
                        <?= $user['is_hiring_enabled'] ? 'Enabled' : 'Disabled' ?>
                    </span>
                </p>

                <form method="POST" action="toggle_hiring.php">
                    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                    <input type="hidden" name="current_status" value="<?= $user['is_hiring_enabled'] ?>">

                    <button type="submit" class="btn btn-darkblue w-100 mt-2">
                        <?= $user['is_hiring_enabled'] ? 'Disable' : 'Enable' ?> Hiring
                    </button>

                    <button type="button" class="btn btn-yellow w-100 mt-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="bi bi-pencil"></i> Edit Profile
                    </button>
                </form>
            </div>

            <!-- Right Dashboard -->
            <div>
                <?php
                // Define resume path safely
                $resumePath = !empty($user['resume']) ? '/uploads/resumes/' . basename($user['resume']) : null;
                ?>
                
                <div class="content-section" id="resume">
                    <h4><i class="bi bi-file-earmark-text"></i> Resume</h4>
                
                    <?php if ($resumePath): ?>
                        <p><?= htmlspecialchars(basename($resumePath)) ?></p>
                        <div class="d-flex gap-2">
                            <a href="<?= htmlspecialchars($resumePath) ?>" 
                               target="_blank" 
                               class="btn btn-yellow btn-sm">
                                <i class="bi bi-eye"></i> View Resume
                            </a>
                            <button onclick="printResume()" class="btn btn-orange btn-sm">
                                <i class="bi bi-printer"></i> Print Resume
                            </button>
                        </div>
                    <?php else: ?>
                        <p>No resume available.</p>
                    <?php endif; ?>
                </div>

                <!-- Jobs -->
                <div class="content-section" id="jobs">
                    <h4><i class="bi bi-briefcase"></i> Recommended Jobs</h4>
                    <?php if ($recommendations): ?>
                        <?php foreach ($recommendations as $job): ?>
                            <div class="job-box">
                                <strong><?= htmlspecialchars($job['jobpost_title']) ?></strong>
                                <p><strong>Skills:</strong> <?= htmlspecialchars($job['skills_requirement']) ?></p>
                                <button class="btn btn-darkblue btn-sm"><i class="bi bi-info-circle"></i> View Details</button>
                                <button class="btn btn-orange btn-sm"><i class="bi bi-send"></i> Apply</button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No job recommendations yet.</p>
                    <?php endif; ?>
                </div>

                <!-- Workshops -->
                <div class="content-section" id="workshops">
                    <h4><i class="bi bi-easel"></i> Workshops</h4>
                    <?php if ($workshops): ?>
                        <?php foreach ($workshops as $work): ?>
                            <div class="job-box">
                                <strong><?= htmlspecialchars($work['work_title']) ?></strong>
                                <p><strong>Hosted By:</strong> <?= htmlspecialchars($work['hostname']) ?></p>
                                <button class="btn btn-darkblue btn-sm"><i class="bi bi-info-circle"></i> View Details</button>
                                <button class="btn btn-orange btn-sm"><i class="bi bi-send"></i> Apply</button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No workshops available.</p>
                    <?php endif; ?>
                </div>

                <!-- Tracker -->
                <div class="content-section" id="tracker">
                    <h4><i class="bi bi-clipboard-check"></i> Tracker</h4>
                    <div class="tracker-tile"><i class="bi bi-send"></i> Applied<br>5</div>
                    <div class="tracker-tile"><i class="bi bi-calendar-check"></i> Interviews<br>2</div>
                    <div class="tracker-tile"><i class="bi bi-person-check"></i> Hired<br>1</div>
                    <button class="btn btn-yellow btn-sm mt-2"><i class="bi bi-clock-history"></i> View Application History</button>
                </div>

                <!-- Messages -->
                <div class="content-section" id="messages">
                    <h4><i class="bi bi-chat-dots"></i> Messages</h4>
                    <div class="message-box">
                        <p><strong>1 unread message</strong> from XYZ Company.</p>
                        <button class="btn btn-orange btn-sm"><i class="bi bi-reply"></i> Reply</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Contact</label>
                            <input type="text" class="form-control" name="contact_number" value="<?= htmlspecialchars($user['contact_number']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" name="location" value="<?= htmlspecialchars($user['location']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description"><?= htmlspecialchars($user['description']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Disability</label>
                            <input type="text" class="form-control" name="disability" value="<?= htmlspecialchars($user['disability']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" name="img">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function printResume() {
    const resumeUrl = "<?= $resumePath ?>";
    if (resumeUrl) {
        const printWin = window.open(resumeUrl, "_blank");
        printWin.onload = function() {
            printWin.print();
        };
    } else {
        alert("No resume available to print.");
    }
}

// Navigation handling
document.addEventListener('DOMContentLoaded', function () {
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const sections = document.querySelectorAll('.dashboard-section');

    function showSection(id) {
        sections.forEach(sec => sec.style.display = 'none');
        const target = document.querySelector(id);
        if (target) target.style.display = 'block';
    }

    // Show home by default
    showSection('#home');

    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const sectionId = this.getAttribute('href');
            showSection(sectionId);
        });
    });
});
</script>
</body>
</html>
