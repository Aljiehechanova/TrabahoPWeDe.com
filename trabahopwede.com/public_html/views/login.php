<?php
session_start();
include '../config/db_connection.php';
include '../controllers/UserController.php';

$userController = new UserController($conn);

$error = "";

// Check Remember Me cookie
$rememberedEmail = $_COOKIE['remember_email'] ?? "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $user_type = $_POST['user_type'];

    $user = $userController->login($email, $password);

    if ($user && is_array($user)) {
        if ($user['user_type'] !== $user_type) {
            $error = "User type mismatch. Please select the correct login type.";
        } else {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_type'] = $user['user_type'];

            if (isset($_POST['remember'])) {
                setcookie('remember_email', $email, time() + (86400 * 30), "/");
            } else {
                setcookie('remember_email', '', time() - 3600, "/");
            }

            if ($user['user_type'] === 'job_seeker') {
                header("Location: ../views/userP.php");
            } elseif ($user['user_type'] === 'client') {
                header("Location: ../views/clientP.php");
            } elseif ($user['user_type'] === 'admin') {
                header("Location: ../views/addash.php");
            }
            exit();
        }
    }

    if ($userController->emailExists($email)) {
        $error = "Incorrect password or user type. Please try again.";
    } else {
        $error = "Email not found. Please check your email or register.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trabaho PWeDe - Login</title>
<link rel="stylesheet" href="../assets/css/login.css">
<link rel="stylesheet" href="../assets/css/access.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<style>
/* ============================= */
/* Go Back button outside container */
/* ============================= */
.go-back {
  position: fixed;
  top: 20px;
  left: 20px;
  background-color: orange;
  color: #fff;
  padding: 10px 18px;
  border-radius: 8px;
  font-weight: bold;
  font-size: 16px;
  text-decoration: none;
  border: none;
  cursor: pointer;
  transition: background 0.3s, transform 0.2s;
  z-index: 9999;
}
.go-back:hover, .go-back:focus {
  background-color: #cc7000;
  outline: 2px solid #fff;
  transform: translateY(-2px);
}

/* ============================= */
/* Responsive adjustments */
/* ============================= */
@media (max-width: 576px) {
  .go-back {
    font-size: 13px;
    padding: 8px 14px;
    top: 15px;
    left: 15px;
  }
}
</style>
</head>

<body>
        <?php include 'accessibility.php'; ?>
            <a href="/home" class="go-back">&larr; Go Back</a>
  <main id="main-content">

    <!-- Go Back button -->


    <div class="layout">
      <!-- Left info -->
      <div class="left-info">
        <h2><span style="color: white;">Trabaho</span> <span style="color: #1877f2;">PWeDe</span></h2>
        <p>A Bacolod-based platform helping Persons With Disabilities (PWDs) access inclusive jobs and opportunities.</p>
      </div>

      <!-- Right login -->
      <!-- Right login -->
      <div class="right-login">
        <img src="../assets/images/TrabahoPWeDeLogo.png" alt="Trabaho PWeDe Logo" class="logo">
        <h1>LOGIN TO YOUR ACCOUNT</h1>

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="color: red; margin-bottom: 15px;">
          <?php echo $error; ?>
        </div>
        <?php endif; ?>

        <form action="" method="POST">
          <div class="input-group">
            <input type="text" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($rememberedEmail); ?>">
          </div>
          <div class="input-group" style="position: relative; display: flex; align-items: center;">
            <input type="password" id="password" name="password" placeholder="Password" required>
            <i class="fas fa-eye" id="togglePassword" style="position: absolute; right: 20px; cursor: pointer; color: #666;"></i>
          </div>
          <select name="user_type" required>
            <option value="">Select Login Type</option>
            <option value="job_seeker">Login as Job Seeker</option>
            <option value="client">Login as Job Provider</option>
            <option value="admin">Login as Admin</option>
          </select>
          <div class="checkbox-group">
            <label><input type="checkbox" name="remember"> Remember me</label>
            <a href="forgot_password.php">Forgot password?</a>
          </div>
          <button type="submit" class="btn btn-primary">Sign in</button>
        </form>

        <p class="signup">Don’t have a Trabaho PWeDe account? <a href="RC.php">Sign up</a></p>
      </div>
    </div> <!-- layout -->

    <!-- ✅ Accessibility panel OUTSIDE login card -->



  </main>

  <script src="../assets/js/main.js" defer></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({ duration: 800, once: true });

    function togglePanel() {
      const panel = document.getElementById('accessibility-panel');
      const content = document.getElementById('access-content');
      panel.classList.toggle('open');
      content.style.display = panel.classList.contains('open') ? 'block' : 'none';
    }

    function toggleGrayscale() { document.body.classList.toggle('grayscale'); }
    function toggleContrast() { document.body.classList.toggle('high-contrast'); }
    function toggleReadableFont() { document.body.classList.toggle('readable-font'); }
    function resetView() {
      document.body.classList.remove('grayscale', 'high-contrast', 'readable-font');
      window.speechSynthesis.cancel();
    }

    document.getElementById('togglePassword').addEventListener('click', function () {
      const passwordInput = document.getElementById('password');
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });

    function setZoom(level) {
      const zoomContainer = document.body;
      let scale = 1;
      if (level === '150') scale = 1.5;
      else if (level === '200') scale = 2;
      else if (level === '300') scale = 3;
      zoomContainer.style.transform = `scale(${scale})`;
      zoomContainer.style.transformOrigin = 'top left';
    }
  </script>
</body>
</html>


</main>