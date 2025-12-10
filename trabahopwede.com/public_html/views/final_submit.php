<?php
session_start();

if (!isset($_SESSION['session_token'])) {
    die("Session expired. Please start again.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['terms']) && isset($_POST['privacy']) && isset($_POST['accuracy'])) {
        // All checkboxes confirmed, proceed
        header("Location: send_verification_email.php");
        exit();
    } else {
        echo "<script>alert('Please confirm all the checkboxes before submitting.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Final Submit</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .locked-checkbox {
            position: relative;
            padding-left: 2rem;
        }
        .locked-checkbox input[type="checkbox"] {
            position: absolute;
            left: 0;
            top: 0.2rem;
            accent-color: #198754;
        }
        .locked-checkbox label::before {
            content: "\1F512";
            position: absolute;
            left: 0;
            top: 0.2rem;
            font-size: 1.2rem;
        }
        .disabled input[type="checkbox"] {
            pointer-events: none;
        }
        .disabled label {
            opacity: 0.6;
        }
    </style>
</head>
<body class="bg-light p-4">
<div class="container">
    <h2>Step 7: Confirmation & Submission</h2>
    <form method="POST" onsubmit="return validateForm();">
        <!-- Terms Checkbox -->
        <div class="form-check locked-checkbox mb-3 disabled" id="terms-container">
            <input class="form-check-input" type="checkbox" id="terms" name="terms" disabled required>
            <label class="form-check-label" for="terms">
                I have read and agree to the 
                <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</button>.
            </label>
        </div>

        <!-- Privacy Checkbox -->
        <div class="form-check locked-checkbox mb-3 disabled" id="privacy-container">
            <input class="form-check-input" type="checkbox" id="privacy" name="privacy" disabled required>
            <label class="form-check-label" for="privacy">
                I have read and agree to the 
                <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</button>.
            </label>
        </div>

        <!-- Accuracy Checkbox -->
        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="accuracy" name="accuracy" required>
            <label class="form-check-label" for="accuracy">
                I confirm that all the information I provided is true and correct.
            </label>
        </div>

        <!-- Navigation Buttons -->
        <div class="d-flex justify-content-between">
            <a href="registration_step3.php" class="btn btn-secondary">Previous</a>
            <button type="submit" class="btn btn-success">Submit Registration</button>
        </div>
    </form>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Effective Date:</strong> January 1, 2025</p>
        <p>Welcome to <strong>TrabahoPwede</strong>, a job-matching platform dedicated to providing inclusive employment opportunities for Persons with Disabilities (PWDs). By registering or using this platform, you agree to the following terms:</p>
        
        <p><strong>1. Eligibility</strong></p>
        <ul>
          <li>You must be at least 18 years old and a person with a verified disability.</li>
          <li>False representation of disability status may result in disqualification and account termination.</li>
        </ul>

        <p><strong>2. Use of Platform</strong></p>
        <ul>
          <li>You agree to use the platform only for lawful and employment-seeking purposes.</li>
          <li>Misuse of the system, including spamming or submitting false information, is prohibited.</li>
        </ul>

        <p><strong>3. Account and Registration</strong></p>
        <ul>
          <li>You are responsible for providing accurate and updated information.</li>
          <li>Keep your login credentials confidential at all times.</li>
        </ul>

        <p><strong>4. Verification Process</strong></p>
        <ul>
          <li>We may collect your PWD ID, facial image, and other documents for verification.</li>
          <li>Failure to provide valid documents may restrict your access to the platform.</li>
        </ul>

        <p><strong>5. Resume and Applications</strong></p>
        <ul>
          <li>Resumes may be generated automatically based on your input.</li>
          <li>You are responsible for reviewing your resume before applying to jobs.</li>
        </ul>

        <p><strong>6. Job Matching</strong></p>
        <ul>
          <li>We do not guarantee employment, but we strive to connect you with verified employers.</li>
        </ul>

        <p><strong>7. Intellectual Property</strong></p>
        <ul>
          <li>All content, including system and templates, belong to TrabahoPwede unless otherwise noted.</li>
        </ul>

        <p><strong>8. Termination</strong></p>
        <ul>
          <li>Accounts may be suspended or terminated for violations, abuse, or fraudulent documentation.</li>
        </ul>

        <p><strong>9. Limitation of Liability</strong></p>
        <ul>
          <li>TrabahoPwede is not liable for any loss or damages arising from the use of the platform.</li>
        </ul>

        <p><strong>10. Changes to Terms</strong></p>
        <p>These terms may be revised periodically. Continued use means you accept any changes made.</p>

        <p>For questions or clarifications, contact us at: <strong>trabahopwede@gmail.com</strong></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="unlockCheckbox('terms')">I Agree</button>
      </div>
    </div>
  </div>
</div>

<!-- Privacy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Effective Date:</strong> January 1, 2025</p>
        <p>TrabahoPwede is committed to protecting the personal data and dignity of our users, especially Persons with Disabilities (PWDs). This Privacy Policy outlines how we collect, use, store, and protect your information.</p>

        <p><strong>1. Information We Collect</strong></p>
        <ul>
          <li><strong>Personal Identifiers:</strong> Name, address, email, date of birth.</li>
          <li><strong>Disability Information:</strong> PWD ID, type of disability, supporting documents.</li>
          <li><strong>Facial Data:</strong> Photo for facial recognition (used only for authentication).</li>
          <li><strong>Employment Data:</strong> Resume, education, skills, work experience.</li>
        </ul>

        <p><strong>2. Purpose of Collection</strong></p>
        <ul>
          <li>To verify your identity as a PWD.</li>
          <li>To assist in job-matching and resume generation.</li>
          <li>To communicate job updates and employer feedback.</li>
        </ul>

        <p><strong>3. Data Sharing</strong></p>
        <ul>
          <li>Your profile and resume may be shared with verified employers only.</li>
          <li>We do not sell or rent your data to any third parties.</li>
          <li>Legal authorities may be granted access if required by law.</li>
        </ul>

        <p><strong>4. Security</strong></p>
        <ul>
          <li>We use secure storage and encrypted protocols to protect your data.</li>
          <li>Only authorized staff may access sensitive data.</li>
        </ul>

        <p><strong>5. Your Rights</strong></p>
        <ul>
          <li>You may request to update or delete your data at any time.</li>
          <li>Withdrawing consent may limit access to some features.</li>
        </ul>

        <p><strong>6. Data Retention</strong></p>
        <ul>
          <li>We retain your data as long as your account is active or as required by law.</li>
        </ul>

        <p><strong>7. Cookies</strong></p>
        <ul>
          <li>We may use cookies to improve user experience and site analytics.</li>
        </ul>

        <p><strong>8. Updates to Policy</strong></p>
        <p>This policy may be updated periodically. You will be notified of major changes.</p>

        <p>For data-related concerns or questions, email us at: <strong>trabahopwede@gmail.com</strong></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="unlockCheckbox('privacy')">I Agree</button>
      </div>
    </div>
  </div>
</div>


<!-- Scripts -->
<script>
function unlockCheckbox(id) {
    const checkbox = document.getElementById(id);
    const container = document.getElementById(id + '-container');
    checkbox.disabled = false;
    container.classList.remove("disabled");
}

function validateForm() {
    const terms = document.getElementById('terms').checked;
    const privacy = document.getElementById('privacy').checked;
    const accuracy = document.getElementById('accuracy').checked;
    if (!terms || !privacy || !accuracy) {
        alert("Please confirm all the checkboxes after reading.");
        return false;
    }
    return true;
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
