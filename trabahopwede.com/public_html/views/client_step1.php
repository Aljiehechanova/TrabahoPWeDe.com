<?php 
session_start();

if (!isset($_SESSION['gov_id_verified'])) {
    die("Please complete Step 1 first.");
}

// Get full name from OCR (fallbacks to empty if not available)
$ocr = $_SESSION['ocr_data'] ?? [];
$fullName = '';

if (isset($ocr['name'])) {
    $fullName = trim($ocr['name']['firstName'] ?? '') . ' ' . trim($ocr['name']['lastName'] ?? '');
    $fullName = htmlspecialchars($fullName);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Client Step 2: Fill Up Info</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    html, body {
      height: 100%;
      margin: 0;
      background-color: #87ceeb !important; /* Sky Blue */
    }

    .upload-box {
      background-color: #003366; /* Dark blue */
      color: white;
      border-radius: 15px;
      padding: 40px 30px;
      max-width: 700px;
      margin: auto;
      box-shadow: 0 6px 25px rgba(0,0,0,0.4);
      text-align: left;
    }

    h2 {
      color: #003366;
      font-weight: bold;
    }

    label {
      font-weight: 600;
    }

    /* Custom Yellow Button */
    .btn-custom {
      background: #ffc107;
      border: none;
      color: #000;
      font-weight: bold;
      border-radius: 10px;
      padding: 14px;
      transition: 0.3s ease;
    }
    .btn-custom:hover:not(:disabled) {
      background: #e0a800;
      transform: scale(1.02);
    }
    /* Keep button yellow even when disabled */
    .btn-custom:disabled {
      background: #ffc107 !important;
      color: #000 !important;
      opacity: 0.8 !important;
      cursor: not-allowed !important;
    }
  </style>
</head>
<body>

<div class="container py-5 text-center">
  <h2 class="mb-4"><i class="fas fa-user-edit"></i> Client Registration: Step 2</h2>

  <div class="upload-box">
    <form id="clientForm" action="client_step2.php" method="POST">

      <div class="mb-3">
        <label for="full_name" class="form-label">Full Name</label>
        <input type="text" name="full_name" id="full_name" class="form-control form-control-lg" required value="<?= $fullName ?>">
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control form-control-lg" required>
      </div>

      <div class="mb-3">
        <label for="business_name" class="form-label">Business Name</label>
        <input type="text" name="business_name" id="business_name" class="form-control form-control-lg" required>
      </div>

      <div class="mb-3">
        <label for="business_type" class="form-label">Business Type</label>
        <input type="text" name="business_type" id="business_type" class="form-control form-control-lg" required>
      </div>

      <!-- Agreement Section -->
      <div class="form-check mt-4">
        <input class="form-check-input" type="checkbox" id="infoCheck">
        <label class="form-check-label" for="infoCheck">
          I confirm that the information provided is accurate to the best of my knowledge.
        </label>
      </div>

      <div class="form-check mt-3">
        <input class="form-check-input" type="checkbox" id="termsCheck" disabled>
        <label class="form-check-label" for="termsCheck">
          I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" class="text-warning fw-bold">Terms and Conditions</a>.
        </label>
      </div>

      <div class="form-check mt-3">
        <input class="form-check-input" type="checkbox" id="privacyCheck" disabled>
        <label class="form-check-label" for="privacyCheck">
          I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal" class="text-warning fw-bold">Privacy Policy</a>.
        </label>
      </div>

      <!-- Button Container -->
      <div class="d-grid mt-4">
        <button type="submit" id="submitBtn" class="btn btn-custom btn-lg" disabled>
          <i class="fas fa-arrow-right"></i> Proceed to Email Verification
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="termsModalLabel" class="fw-bold">Terms and Conditions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-dark" style="max-height:400px; overflow-y:auto;">
        <p>Welcome to <strong>Trabaho PWeDe</strong>. By creating an account and using our services, you agree to the following terms and conditions.</p>
        <p><strong>1. Account Responsibility:</strong> You are responsible for maintaining the confidentiality of your account credentials. Misuse of your account will be your responsibility.</p>
        <p><strong>2. Fair Use:</strong> Clients are expected to provide accurate job postings and fair treatment of applicants. Fraudulent activity may result in suspension.</p>
        <p><strong>3. Compliance with Laws:</strong> All users agree to comply with local, national, and international laws when using this platform.</p>
        <p><strong>4. Termination:</strong> Trabaho PWeDe reserves the right to suspend or terminate accounts that violate these rules.</p>
        <p><strong>5. Amendments:</strong> We may update these terms at any time. It is your responsibility to review updates periodically.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. ...</p>
        <p>Nam nec neque ac justo viverra sollicitudin. ...</p>
        <p class="fw-bold">Scroll to the bottom to unlock the checkbox.</p>
      </div>
    </div>
  </div>
</div>

<!-- Privacy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="privacyModalLabel" class="fw-bold">Privacy Policy</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-dark" style="max-height:400px; overflow-y:auto;">
        <p><strong>Trabaho PWeDe</strong> respects your right to privacy. This policy explains how we collect, use, and protect your personal data.</p>
        <p><strong>1. Data Collection:</strong> We collect only the data necessary for registration, verification, and job matching. ...</p>
        <p><strong>2. Data Use:</strong> Your information will be used solely to connect clients and job seekers. ...</p>
        <p><strong>3. Security:</strong> We use appropriate safeguards to protect your personal data ...</p>
        <p><strong>4. User Rights:</strong> You may request deletion or correction of your data ...</p>
        <p><strong>5. Policy Updates:</strong> We may revise this Privacy Policy from time to time ...</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. ...</p>
        <p>Sed porttitor, turpis sed sollicitudin bibendum ...</p>
        <p class="fw-bold">Scroll to the bottom to unlock the checkbox.</p>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script>
  const infoCheck = document.getElementById('infoCheck');
  const termsCheck = document.getElementById('termsCheck');
  const privacyCheck = document.getElementById('privacyCheck');
  const submitBtn = document.getElementById('submitBtn');

  function updateSubmitButton() {
    submitBtn.disabled = !(infoCheck.checked && termsCheck.checked && privacyCheck.checked);
  }

  // Unlock Terms checkbox after scrolling
  document.getElementById('termsModal').addEventListener('shown.bs.modal', function () {
    const modalBody = this.querySelector('.modal-body');
    modalBody.addEventListener('scroll', function () {
      if (modalBody.scrollTop + modalBody.clientHeight >= modalBody.scrollHeight) {
        termsCheck.disabled = false;
      }
    });
  });

  // Unlock Privacy checkbox after scrolling
  document.getElementById('privacyModal').addEventListener('shown.bs.modal', function () {
    const modalBody = this.querySelector('.modal-body');
    modalBody.addEventListener('scroll', function () {
      if (modalBody.scrollTop + modalBody.clientHeight >= modalBody.scrollHeight) {
        privacyCheck.disabled = false;
      }
    });
  });

  infoCheck.addEventListener('change', updateSubmitButton);
  termsCheck.addEventListener('change', updateSubmitButton);
  privacyCheck.addEventListener('change', updateSubmitButton);
</script>

</body>
</html>
