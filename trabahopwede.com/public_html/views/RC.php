<!DOCTYPE html>
<?php include 'accessibility.php'; ?>
<html lang="en">
<script>
    function redirectToPage() {
      let jobSeeker = document.getElementById("job-seeker").checked;
      let client = document.getElementById("client").checked;

      if (jobSeeker) {
        window.location.href = "upload_qr.php";
      } else if (client) {
        window.location.href = "client_upload.php";
      } else {
        alert("Please select an option before proceeding.");
      }
    }
</script>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trabaho PWeDe - Choice</title>
  <link rel="stylesheet" href="../assets/css/access.css">
  <style>
    /* General Page */
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #87CEEB; /* Sky Blue */
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    /* Choice Box */
    .choice-container {
      background: #002147; /* Dark Blue */
      color: #fff;
      border-radius: 16px;
      padding: 40px 30px;
      box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.3);
      max-width: 480px;
      width: 90%;
      text-align: center;
      animation: fadeIn 0.6s ease-in-out;
    }

    .choice-container h1 {
      margin-bottom: 25px;
      font-size: 24px;
      font-weight: bold;
    }

    /* Options */
    .options {
      display: flex;
      flex-direction: column;
      gap: 18px;
      margin-bottom: 25px;
    }

    .option {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      border: 2px solid transparent;
      transition: all 0.3s ease;
    }

    .option:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: translateY(-2px);
    }

    .option input {
      display: none;
    }

    .option label {
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      padding: 16px;
    }

    .option p {
      margin: 0;
      font-size: 16px;
    }

    /* Highlight selected option */
    .option input:checked + label {
      border: 2px solid #FFD700;
      background: rgba(255, 215, 0, 0.15);
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
    }

    /* Primary Button */
    .btn-primary {
      width: 100%;
      padding: 14px;
      background-color: #FFD700;
      color: #000;
      font-size: 18px;
      font-weight: bold;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #FFC107;
      transform: scale(1.03);
    }

    /* Links */
    .choice-container p {
      margin-top: 18px;
      font-size: 15px;
    }

    .choice-container a {
      color: #FFD700;
      font-weight: bold;
      text-decoration: none;
    }

    .choice-container a:hover {
      text-decoration: underline;
    }

    /* Go Back Button */
    .go-back-btn {
      margin-top: 20px;
      display: block;
      width: 200px;
      margin-left: auto;
      margin-right: auto;
      padding: 12px;
      font-size: 1.1rem;
      text-align: center;
      background-color: #6c757d;
      color: #fff;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      transition: background-color 0.3s;
    }

    .go-back-btn:hover {
      background-color: #5a6268;
    }

    /* Animation */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Responsive */
    @media (max-width: 576px) {
      .choice-container {
        padding: 30px 20px;
      }
      .btn-primary {
        font-size: 16px;
        padding: 12px;
      }
      .option p {
        font-size: 15px;
      }
    }
  </style>
</head>
<body class="index-page">
<main id="main-content">
 <!-- Choice Box -->
  <div class="choice-container">
    <h1>Join as Job Seeker or Client</h1>
    <div class="options">
      <div class="option">
        <input type="radio" id="job-seeker" name="user-type">
        <label for="job-seeker">
          <p>I'm a job seeker, looking for work</p>
        </label>
      </div>
      <div class="option">
        <input type="radio" id="client" name="user-type">
        <label for="client">
          <p>I'm a client, appoint for opportunity</p>
        </label>
      </div>
    </div>

    <button class="btn-primary" onclick="redirectToPage()">Create Account</button>
    <p>Already have an account? <a href="login.php">Log In</a></p>
    <a href="login.php" class="go-back-btn">Go Back</a>
  </div>
</body>

<!-- Scripts -->
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

  function toggleGrayscale() {
    document.body.classList.toggle('grayscale');
  }

  function toggleContrast() {
    document.body.classList.toggle('high-contrast');
  }

  function toggleReadableFont() {
    document.body.classList.toggle('readable-font');
  }

  function toggleMagnifier() {
    const canvas = document.getElementById('magnifier');
    canvas.style.display = canvas.style.display === 'block' ? 'none' : 'block';
    if (canvas.style.display === 'block') {
      document.addEventListener('mousemove', drawMagnifier);
    } else {
      document.removeEventListener('mousemove', drawMagnifier);
    }
  }

  function drawMagnifier(e) {
    const canvas = document.getElementById('magnifier');
    const ctx = canvas.getContext('2d');
    const x = e.clientX;
    const y = e.clientY;
    html2canvas(document.body).then(screenshot => {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ctx.drawImage(screenshot, x - 50, y - 50, 100, 100, 0, 0, 250, 250);
      canvas.style.position = 'absolute';
      canvas.style.left = x + 20 + 'px';
      canvas.style.top = y + 20 + 'px';
    });
  }

  function speakText() {
    const text = document.body.innerText;
    const synth = window.speechSynthesis;
    const utter = new SpeechSynthesisUtterance(text);
    synth.speak(utter);
  }

  function resetView() {
    document.body.classList.remove('grayscale', 'high-contrast', 'readable-font');
    const canvas = document.getElementById('magnifier');
    canvas.style.display = 'none';
    window.speechSynthesis.cancel();
  }
</script>
</html>

</main>