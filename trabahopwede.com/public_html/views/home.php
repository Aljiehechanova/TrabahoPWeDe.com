<?php
include 'config/db_connection.php';

$client_count = 0;
$workshop_count = 0;
$job_count = 0;

try {
    //Count clients
    $stmt = $conn->query("SELECT COUNT(*) FROM users WHERE user_type = 'client'");
    $client_count = $stmt->fetchColumn();

    //Count workshops
    $stmt = $conn->query("SELECT COUNT(*) FROM workshop");
    $workshop_count = $stmt->fetchColumn();

    //Count jobs
    $stmt = $conn->query("SELECT COUNT(*) FROM jobpost");
    $job_count = $stmt->fetchColumn();
} catch (PDOException $e) {
   $client_count = $workshop_count = $job_count = 0;
}

?>
<?php $status = $_GET['status'] ?? ''; ?>
<!-- Bootstrap Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="statusModalLabel">Message Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php if ($status === 'success'): ?>
          ✅ Your message has been sent successfully!
        <?php elseif ($status === 'error'): ?>
          ❌ Something went wrong. Please try again.
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
  const status = "<?php echo $status; ?>";
  if (status) {
    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
    // Optional: scroll to contact section
    document.getElementById("contact")?.scrollIntoView({ behavior: "smooth" });
  }
</script>
<?php include 'accessibility.php'; ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>TRABAHO PWEDE</title>
  <link rel="icon" href="../assets/images/favicon.png">
  <link rel="stylesheet" href="../assets/css/home.css">
  <link rel="stylesheet" href="../assets/css/access.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap JS Bundle (with Modal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
  .map-container { width:100%; height:320px; border-radius:8px; overflow:hidden; }
  .route-info { margin-top:8px; background:#fff; padding:10px; border-radius:6px; box-shadow:0 3px 10px rgba(0,0,0,0.08); display:none; }
  .steps-list { max-height:200px; overflow:auto; margin-top:8px; }
</style>

</head>
<body class="index-page">

<header class="header">
  <div class="container-fluid header-inner">
    
    <!-- Mobile Toggle Button -->
    <div class="mobile-nav-toggle d-md-none" onclick="toggleMobileNav()">☰</div>



    <!-- Mobile Toggle Button -->
<div class="mobile-nav-toggle d-md-none" onclick="toggleMobileNav()">
  <div class="hamburger">
    <span></span><span></span><span></span>
  </div>
</div>

<!-- Left Menu (desktop only) -->
<nav class="navmenu left-nav d-none d-md-flex">
  <ul>
    <li><a href="#hero">Home</a></li>
    <li><a href="#WorkShopCategory">Workshop Category</a></li>
    <li><a href="#WhatsNew">What’s New</a></li>
  </ul>
</nav>

<!-- Center Site Name -->
<div class="sitename">
  <span class="trabaho">Trabaho</span><span class="pwe">PWeDe</span>
</div>

<!-- Right Menu (desktop only) -->
<nav class="navmenu right-nav d-none d-md-flex">
  <ul>
    <li><a href="#GUIDE">Guide</a></li>
    <li><a href="#contact">Contact</a></li>
    <li><a href="views/login.php">Login</a></li>
    <li><a href="views/RC.php">Register</a></li>
  </ul>
</nav>


  </div>
</header>


<div id="zoom-container">

<!-- ========== Main Content (KEEP YOUR MAIN CONTENT HERE) ========== -->
<main class="main">
<!-- ======= Hero Section ======= -->
<section id="hero" class="hero">
  <div class="logo">
    <div class="logo-image">
      <img src="../assets/images/TrabahoPWeDeLogo.png" alt="Logo" class="header-logo">
    </div>
  </div>

  <!-- Background video -->
  <video autoplay muted loop playsinline class="bg-video">
    <source src="../assets/images/Video.mov" type="video/mp4">
    Your browser does not support the video tag.
  </video>

  <!-- Hero content overlay -->
  <div class="container hero-content text-center text-white">
    <h2>Empower and Enhance Your Capabilities</h2>
    <p>Your Gateway to inclusive employment and training opportunities</p>
    <a href="#" class="watch-video btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#videoModal">Watch Video</a>
  </div>
</section>

<!-- Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg m-auto">
    <div class="modal-content bg-dark position-relative p-0 border-0 rounded-0">
      
      <!-- Close Button -->
      <button type="button"
        class="btn-close btn-close-white position-absolute"
        style="top: 15px; right: 15px; z-index: 1056;"
        data-bs-dismiss="modal"
        aria-label="Close"></button>

      <!-- Video Container -->
      <div class="ratio ratio-16x9 m-0">
        <video id="popupVideo" controls>
          <source src="../assets/images/Video.mov" type="video/mp4">
          Your browser does not support HTML5 video.
        </video>
      </div>
    </div>
  </div>
</div>

<section id="stats" class="stats">
  <div class="container">
    <div class="stats-item">
      <i class="fas fa-users icon"></i>
      <span class="purecounter" data-purecounter-start="0" data-purecounter-end="<?= $client_count ?>" data-purecounter-duration="2000">
        <?= $client_count ?>
      </span>
      <p>Clients</p>
    </div>
    <div class="stats-item">
      <i class="fas fa-chalkboard-teacher icon"></i>
      <span class="purecounter" data-purecounter-start="0" data-purecounter-end="<?= $workshop_count ?>" data-purecounter-duration="2000">
        <?= $workshop_count ?>
      </span>
      <p>Workshops</p>
    </div>
    <div class="stats-item">
      <i class="fas fa-briefcase icon"></i>
      <span class="purecounter" data-purecounter-start="0" data-purecounter-end="<?= $job_count ?>" data-purecounter-duration="2000">
        <?= $job_count ?>
      </span>
      <p>Jobs</p>
    </div>
  </div>
</section>



<section id="WorkShopCategory" class="WorkShopCategory">
  
  <!-- Left Side: Text -->
  <div class="text-content" data-aos="fade-left">
    <h3>Welcome to Trabaho PWeDe</h3>
    <p>
      <strong>Trabaho PWeDe</strong> is a web-based platform based in Bacolod City, 
      designed to support Persons with Disabilities (PWDs) by connecting them with 
      inclusive employment opportunities and skill-enhancing programs. Our mission 
      is to promote professional development and equal access to the workforce.
    </p>

    <p class="intro-note">
      The <strong>Workshop Category</strong> is a core offering — delivering programs 
      that empower users with real-world skills and confidence.
    </p>

    <ul>
      <li><strong>Job Readiness Workshops:</strong> Interview preparation and resume writing.</li>
      <li><strong>Empowerment Programs:</strong> Leadership, motivation, and confidence-building.</li>
    </ul>
  </div>

  <!-- Right Side: Image -->
  <div class="image-content" data-aos="fade-right">
    <img src="../assets/images/Generated_image.png" 
         class="workshop-img" 
         alt="Find Your Dream Job Poster">
  </div>

</section>

  <!-- ======= Testimonials Slider ======= -->
<section id="testimonials" class="testimonials py-5">
  <div class="container">
    <div class="carousel position-relative" id="testimonial-carousel">
      <div class="carousel-track-wrapper overflow-hidden">
        <div class="carousel-track d-flex">

          <!-- Web Development Slide -->
          <div class="carousel-slide active flex-shrink-0 text-center p-4">
            <img src="../assets/images/testimonials/testimonials-1.jpg" alt="Web Development" class="img-fluid rounded shadow mb-3">
            <h3>Web Development</h3>
            <p>Learn HTML, CSS, and JavaScript from scratch.</p>
            <div class="testimonial-buttons mt-3">
              <a href="#" class="btn btn-details btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#webDevModal">More Details</a>
              <a href="views/RC.php" class="btn btn-join btn-sm btn-success">Join Now</a>
            </div>
          </div>

          <!-- Graphic Design Slide -->
          <div class="carousel-slide flex-shrink-0 text-center p-4">
            <img src="../assets/images/testimonials/testimonials-2.jpg" alt="Graphic Design" class="img-fluid rounded shadow mb-3">
            <h3>Graphic Design</h3>
            <p>Master Photoshop, Illustrator, and more.</p>
            <div class="testimonial-buttons mt-3">
              <a href="#" class="btn btn-details btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#graphicDesignModal">More Details</a>
              <a href="views/RC.php" class="btn btn-join btn-sm btn-success">Join Now</a>
            </div>
          </div>

          <!-- Digital Marketing Slide -->
          <div class="carousel-slide flex-shrink-0 text-center p-4">
            <img src="../assets/images/testimonials/testimonials-3.jpg" alt="Digital Marketing" class="img-fluid rounded shadow mb-3">
            <h3>Digital Marketing</h3>
            <p>Grow your brand with SEO & Social Media.</p>
            <div class="testimonial-buttons mt-3">
              <a href="#" class="btn btn-details btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#digitalMarketingModal">More Details</a>
              <a href="views/RC.php" class="btn btn-join btn-sm btn-success">Join Now</a>
            </div>
          </div>

        </div>
      </div>

      <!-- Navigation Buttons -->
      <button class="prev-btn btn btn-dark position-absolute top-50 start-0 translate-middle-y">&lt;</button>
      <button class="next-btn btn btn-dark position-absolute top-50 end-0 translate-middle-y">&gt;</button>
    </div>
  </div>
</section>

<!-- ======= Modals ======= -->
<!-- Web Development Modal -->
<div class="modal fade" id="webDevModal" tabindex="-1" aria-labelledby="webDevModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" role="dialog" aria-modal="true">
      <div class="modal-header bg-darkblue text-white">
        <h5 class="modal-title">Web Development</h5>
        <button type="button" class="btn-close btn-close-white fs-3" data-bs-dismiss="modal" aria-label="Close modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Schedule:</strong> August 20–25, 2025 | 9:00 AM – 4:00 PM</p>
        <p><strong>Location:</strong> Bacolod City ICT Center</p>
<div id=\"map-web\" class=\"map-container\"></div>

        <div class="map-container mb-3">
          <div id="map-web" class="map-container"></div>
        </div>

        <div class="d-flex gap-2 my-3">
          <button class="btn btn-info flex-fill" onclick="mockDistance('walking')">🚶 Walking Distance</button>
          <button class="btn btn-warning flex-fill" onclick="mockDistance('driving')">🚌 Ride Info</button>
        </div>
        <div id="distanceOutput" class="p-2 bg-light border rounded text-dark" style="display:none;"></div>

        <p class="mt-3"><strong>Topics:</strong> HTML, CSS, JavaScript, Accessibility Guidelines</p>
        <ul>
          <li>Keyboard Navigation Tutorials</li>
          <li>Screen Reader Friendly Coding</li>
          <li>Magnifier and High-Contrast UI Tips</li>
        </ul>
        <p><strong>Contact:</strong> devtraining@trabahopwede.ph</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-orange" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Graphic Design Modal -->
<div class="modal fade" id="graphicDesignModal" tabindex="-1" aria-labelledby="graphicDesignModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" role="dialog" aria-modal="true">
      <div class="modal-header bg-darkblue text-white">
        <h5 class="modal-title">Graphic Design</h5>
        <button type="button" class="btn-close btn-close-white fs-3" data-bs-dismiss="modal" aria-label="Close modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Schedule:</strong> September 1–6, 2025 | 10:00 AM – 3:00 PM</p>
        <p><strong>Location:</strong> Bacolod Art District</p>
<div id=\"map-design\" class=\"map-container\"></div>

        <div class="map-container mb-3">
          <div id="map-design" class="map-container"></div>
        </div>

        <div class="d-flex gap-2 my-3">
          <button class="btn btn-info flex-fill" onclick="mockDistance('walking')">🚶 Walking Distance</button>
          <button class="btn btn-warning flex-fill" onclick="mockDistance('driving')">🚌 Ride Info</button>
        </div>
        <div id="distanceOutput" class="p-2 bg-light border rounded text-dark" style="display:none;"></div>

        <p class="mt-3"><strong>Topics:</strong> Photoshop, Illustrator, Canva, Color Theory</p>
        <ul>
          <li>Easy UI Tools for Color Blindness</li>
          <li>Templates with Readable Fonts</li>
          <li>Visual Design for Cognitive Disability Awareness</li>
        </ul>
        <p><strong>Contact:</strong> designsupport@trabahopwede.ph</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-orange" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Digital Marketing Modal -->
<div class="modal fade" id="digitalMarketingModal" tabindex="-1" aria-labelledby="digitalMarketingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" role="dialog" aria-modal="true">
      <div class="modal-header bg-darkblue text-white">
        <h5 class="modal-title">Digital Marketing</h5>
        <button type="button" class="btn-close btn-close-white fs-3" data-bs-dismiss="modal" aria-label="Close modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Schedule:</strong> September 10–15, 2025 | 1:00 PM – 5:00 PM</p>
        <p><strong>Location:</strong> Trabaho PWeDe Online + Bacolod Resource Hub</p>
<div id=\"map-marketing\" class=\"map-container\"></div>

        <div class="map-container mb-3">
          <div id="map-marketing" class="map-container"></div>
        </div>

        <div class="d-flex gap-2 my-3">
          <button class="btn btn-info flex-fill" onclick="mockDistance('walking')">🚶 Walking Distance</button>
          <button class="btn btn-warning flex-fill" onclick="mockDistance('driving')">🚌 Ride Info</button>
        </div>
        <div id="distanceOutput" class="p-2 bg-light border rounded text-dark" style="display:none;"></div>

        <p class="mt-3"><strong>Topics:</strong> SEO, Social Media Marketing, Accessible Advertising</p>
        <ul>
          <li>Captioned Video Campaigns</li>
          <li>Inclusive Brand Messaging</li>
          <li>Accessible Email Templates</li>
        </ul>
        <p><strong>Contact:</strong> marketing@trabahopwede.ph</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-orange" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- ======= Custom Styles ======= -->
<style>
  /* ===== Theme Colors ===== */
  .bg-darkblue { background-color: #0d1b4c !important; }
  .text-yellow { color: #ffcc00 !important; }

  .btn-orange {
    background-color: #ff7f32;
    color: #fff;
    border: none;
    padding: 8px 20px;
    border-radius: 8px;
    transition: 0.3s ease;
  }
  .btn-orange:hover { background-color: #ff9c5c; }

  .btn-info {
    background: #00aaff;
    border: none;
    font-weight: 600;
    color: #fff;
  }
  .btn-warning {
    background: #ffc107;
    border: none;
    font-weight: 600;
    color: #000;
  }

/* ===== Carousel Slides ===== */
.carousel-track {
  transition: transform 0.5s ease-in-out;
}
.carousel-slide {
  width: 100%;
  max-width: 100%;
}
@media (min-width: 768px) {
  .carousel-slide { max-width: 50%; }
}
@media (min-width: 992px) {
  .carousel-slide { max-width: 33.33%; }
}

/* ===== Modal Styling ===== */
.modal-dialog {
  margin-top: 3.5rem !important; /* visible header */
  max-width: 95%;
}
.modal-content {
  border-radius: 12px;
  overflow: hidden;
  background-color: #0d1b4c; /* dark blue background */
  color: #fff; /* white text for contrast */
}
.modal-header {
  position: relative;
  padding: 1rem 1.5rem;
  background-color: #0a1540;
  color: #ffcc00; /* yellow title */
}
.modal-header h5 {
  font-weight: 700;
  font-size: 1.2rem;
}
.modal-header .btn-close {
  position: absolute;
  top: 10px;
  right: 12px;
  background: #ff7f32 !important; /* orange close button */
  border-radius: 50%;
  opacity: 1;
  width: 28px;
  height: 28px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.25);
}
.modal-body {
  max-height: 70vh;
  overflow-y: auto;
  padding: 1rem 1.2rem;
}

/* ===== Map Styling ===== */
.map-container {
  margin-bottom: 0 !important; /* no gap below */
}
.map-container iframe {
  width: 100%;
  height: 700px; /* smaller height than 240px */
  border-radius: 10px 10px 0 0; /* only top rounded */
  box-shadow: 0 3px 8px rgba(0,0,0,0.2);
  display: block;
}
@media (max-width: 576px) {
  .map-container iframe {
    height: 150px;
  }
}


/* ===== Distance Buttons (under map) ===== */
.d-flex.gap-2 {
  margin: 0 !important;   /* remove ALL margins */
  padding: 0 !important;  /* ensure no padding adds space */
  gap: 0 !important;      /* no space between buttons */
  display: flex;
}

.d-flex.gap-2 button {
  margin: 0 !important;   /* override Bootstrap's button margins */
  font-size: 1rem;
  font-weight: 700;
  padding: 14px;          /* accessible size */
  border-radius: 0;
  flex: 1;
  line-height: 1.2;       /* prevent extra vertical spacing */
}

.d-flex.gap-2 button:first-child {
  border-radius: 0 0 0 10px; /* bottom-left rounded */
}
.d-flex.gap-2 button:last-child {
  border-radius: 0 0 10px 0; /* bottom-right rounded */
}


/* ===== Carousel Nav Buttons (Left & Right Arrows) ===== */
.prev-btn, .next-btn {
  width: 60px;  /* larger size for PWD accessibility */
  height: 60px;
  border-radius: 50%;
  font-size: 2rem; /* bigger icon */
  display: flex;
  align-items: center;
  justify-content: center;
  border: none;
  background: #ff7f32; /* orange */
  color: #fff;
  box-shadow: 0 4px 10px rgba(0,0,0,0.35);
  opacity: 0.95;
  transition: 0.3s ease;
  z-index: 10;
}
.prev-btn:hover, .next-btn:hover {
  opacity: 1;
  background: #ff9c5c;
}

/* ===== Mobile Responsive ===== */
@media (max-width: 768px) {
  .prev-btn, .next-btn {
    width: 50px;
    height: 50px;
    font-size: 1.6rem;
  }
  .d-flex.gap-2 {
    flex-direction: column;
  }
  .d-flex.gap-2 button {
    width: 100%;
    border-radius: 0 !important; /* stacked, no rounding between */
  }
  .d-flex.gap-2 button:first-child {
    border-radius: 0 0 10px 10px; /* bottom corners rounded */
  }
}


  /* ===== Mobile Responsive ===== */
  @media (max-width: 768px) {
    .prev-btn, .next-btn {
      width: 36px;
      height: 36px;
      font-size: 1.1rem;
    }
    .d-flex.gap-2 {
      flex-direction: column;
    }
    .d-flex.gap-2 button {
      width: 100%;
    }
    .modal-body {
      padding: 0.8rem;
    }
  }
</style>


<!-- ======= Carousel Script + Distance Mock ======= -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const track = document.querySelector(".carousel-track");
  const slides = Array.from(track.children);
  const prevBtn = document.querySelector(".prev-btn");
  const nextBtn = document.querySelector(".next-btn");
  let currentSlide = 0;
  let autoSlideTimeout;

  function updateCarousel(newIndex) {
    track.style.transform = `translateX(-${newIndex * 100}%)`;
    slides.forEach(slide => slide.classList.remove("active"));
    slides[newIndex].classList.add("active");
    currentSlide = newIndex;
    resetAutoSlide();
  }

  function nextSlide() {
    updateCarousel((currentSlide + 1) % slides.length);
  }

  function prevSlide() {
    updateCarousel((currentSlide - 1 + slides.length) % slides.length);
  }

  function resetAutoSlide() {
    clearTimeout(autoSlideTimeout);
    autoSlideTimeout = setTimeout(autoSlideLoop, 10000);
  }

  function autoSlideLoop() {
    nextSlide();
    autoSlideTimeout = setTimeout(autoSlideLoop, 10000);
  }

  if (nextBtn) nextBtn.addEventListener("click", nextSlide);
  if (prevBtn) prevBtn.addEventListener("click", prevSlide);

  resetAutoSlide();
});

function mockDistance(mode) {
  let userLat = 10.675, userLng = 122.95;
  let destLat = 10.68, destLng = 122.96;

  const R = 6371;
  let dLat = (destLat - userLat) * Math.PI / 180;
  let dLng = (destLng - userLng) * Math.PI / 180;
  let a = Math.sin(dLat/2) * Math.sin(dLat/2) +
          Math.cos(userLat * Math.PI/180) * Math.cos(destLat * Math.PI/180) *
          Math.sin(dLng/2) * Math.sin(dLng/2);
  let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
  let distance = R * c;

  let duration;
  if (mode === "walking") {
    duration = (distance / 5 * 60).toFixed(0) + " mins";
  } else {
    duration = (distance / 20 * 60).toFixed(0) + " mins";
  }

  let tips = mode === "driving" ? 
    "💡 Tip: Ride a jeepney with route 'Bata – Downtown' near the plaza." : "";

  let output = document.getElementById("distanceOutput");
  output.style.display = "block";
  output.innerHTML = 
    `<strong>${mode === "walking" ? "🚶 Walking" : "🚌 Ride"}:</strong><br>` +
    `📍 Distance: ${distance.toFixed(2)} km<br>` +
    `⏱ Duration: ${duration}<br>` +
    tips;
}
</script>





    <!-- ======= Stats Section ======= --> 
<!-- ======= Stats Section ======= --> 



   <!-- ======= Whats New ======= --> 
<section id="WhatsNew" class="WhatsNew">
  <div class="container section-title" data-aos="fade-up">
    <h2>What's New</h2>
    <p>Updates</p>
  </div>
  <div class="container card-grid">
    <div class="card" data-aos="zoom-in" data-aos-delay="0">
      <img src="../assets/images/RG.jpg" alt="Resume Evaluator">
      <div class="card-icon">
        <i class="fas fa-file-alt"></i>
      </div>
      <h3>Resume Generator</h3>
      <p>Upload resumes and receive instant feedback.</p>
    </div>
    <div class="card" data-aos="zoom-in" data-aos-delay="200">
      <img src="../assets/images/IJ.jpg" alt="Inclusive Job Board">
      <div class="card-icon">
        <i class="fas fa-briefcase"></i>
      </div>
      <h3>Inclusive Job Board</h3>
      <p>Browse PWD-friendly job listings with filters.</p>
    </div>
    <div class="card" data-aos="zoom-in" data-aos-delay="400">
      <img src="../assets/images/Workshop.png" alt="Workshop & Training">
      <div class="card-icon">
        <i class="fas fa-chalkboard-teacher"></i>
      </div>
      <h3>Workshops</h3>
      <p>Join programs designed to equip PWDs.</p>
    </div>
  </div>
</section>


<section id="GUIDE" class="GUIDE">
  <div class="container section-title">
    <h2>GUIDE</h2>
    <p>Check our manual to learn how to use the website.</p>
  </div>

  <!-- Filter Buttons -->
  <div class="guide-filter">
    <button class="filter-btn active" data-filter="all">All</button>
    <button class="filter-btn" data-filter="login">Login</button>
    <button class="filter-btn" data-filter="register">Register</button>
  </div>

  <!-- Guide Cards Container -->
  <div class="guide-container card-grid">
    <!-- Login Card -->
    <div class="guide-card" data-guide="login" onclick="selectGuide('login')">
      <img src="../assets/images/login.png" alt="How to Login">
      <h4>How to Login</h4>
      <div class="audio-lang-toggle">
        <button onclick="setGuideAudio(event, this, 'login', 'en')">🇬🇧 English</button>
        <button onclick="setGuideAudio(event, this, 'login', 'tl')">🇵🇭 Tagalog</button>
      </div>
      <audio id="audio-login" controls>
        <source src="../assets/audio/HOWTOLOGIN-ENGLISH.mp3" type="audio/mpeg">
      </audio>
    </div>

    <!-- Register Card -->
    <div class="guide-card" data-guide="register" onclick="selectGuide('register')">
      <img src="../assets/images/Register.png" alt="How to Register">
      <h4>How to Register</h4>
      <div class="audio-lang-toggle">
        <button onclick="setGuideAudio(event, this, 'register', 'en')">🇬🇧 English</button>
        <button onclick="setGuideAudio(event, this, 'register', 'tl')">🇵🇭 Tagalog</button>
      </div>
      <audio id="audio-register" controls>
        <source src="../assets/audio/HOWTOREGISTER-ENGLISH.mp3" type="audio/mpeg">
      </audio>
    </div>
  </div>

  <!-- Step-by-step panel -->
<div class="container mt-4 d-flex flex-column align-items-center justify-content-center text-center">
  <button id="toggle-steps-btn" class="btn btn-primary d-none mb-3" onclick="toggleSteps()">Show Step-by-Step Guide</button>

  <div id="steps-panel" class="mt-3" style="display: none; width: 100%; max-width: 600px;">
    <div class="language-toggle mb-2 d-flex justify-content-center gap-2">
      <button onclick="toggleLanguage(currentGuide, 'en')" class="lang-btn">🇬🇧 English</button>
      <button onclick="toggleLanguage(currentGuide, 'tl')" class="lang-btn">🇵🇭 Tagalog</button>
    </div>
    <div id="guide-content"></div>
  </div>
</div>



   <!-- ======= Contact Section ======= -->
<section id="contact" class="contact">
  <div class="container section-title" data-aos="fade-up">
    <h2>Contact</h2>
    <p>Get in touch with us.</p>
  </div>

  <div class="container contact-container">
    <!-- Contact Info Cards -->
    <div class="contact-info">
      <div class="info-card" data-aos="zoom-in" data-aos-delay="100">
        <i class="fas fa-map-marker-alt"></i>
        <h3>Address</h3>
        <p>University of Negros Occidental–Recoletos
            51 Lizares Avenue
            Bacolod City, Negros Occidental 6100</p>
      </div>
      <div class="info-card" data-aos="zoom-in" data-aos-delay="200">
        <i class="fas fa-phone-alt"></i>
        <h3>Call Us</h3>
        <p>+63 930 8461 943</p>
      </div>
      <div class="info-card" data-aos="zoom-in" data-aos-delay="200">
        <i class="fas fa-envelope"></i>
        <h3>Email Us</h3>
        <p>trabahopwede@gmail.com</p>
      </div>
    </div>

    <!-- Contact Form -->
<form class="contact-form" action="send_message.php" method="POST" data-aos="fade-left" data-aos-delay="400">
  <div class="form-group">
    <input type="text" name="name" placeholder="Your Name" required>
    <input type="email" name="email" placeholder="Your Email" required>
  </div>

  <!-- Category buttons (accessible selector) -->
  <div class="form-group">
    <label style="display:block; font-weight:600; margin-bottom:10px; color:#0a1f44;">
      Message Type
    </label>
    <div class="category-selector">
      <button type="button" onclick="setCategory('Concern')">Concern</button>
      <button type="button" onclick="setCategory('Report')">Report</button>
      <button type="button" onclick="setCategory('Issue')">Issue</button>
    </div>
  </div>

  <!-- Subject -->
  <input type="text" id="subject" name="subject" placeholder="Subject">

  <!-- Message -->
  <textarea name="message" placeholder="Message" rows="5" required></textarea>

  <!-- Submit -->
  <button type="submit" class="btn-submit">Send Message</button>
</form>
<script>
function setCategory(type) {
  const subjectInput = document.getElementById("subject");
  // If already starts with a category, replace it
  subjectInput.value = subjectInput.value.replace(/^\[(Concern|Report|Issue)\]\s*/, "");
  subjectInput.value = `[${type}] ` + subjectInput.value;
}
</script>


</main>
</div>
<button onclick="scrollToTop()" id="goTopBtn" title="Go to top">
  <i class="fas fa-arrow-up"></i>
</button>




<!-- ======= Footer ======= -->
<footer class="footer">
  <div class="container">
    <p>&copy; 2025 Trabaho PWeDe. All rights reserved.</p>
  </div>
</footer>

<!-- Scripts -->
<script src="../assets/js/main.js" defer></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });
   function toggleMobileMenu() {
    document.querySelector('.navmenu').classList.toggle('open');
  }

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
   document.addEventListener('DOMContentLoaded', function () {
      const panel = document.getElementById('accessibility-panel');
      const toggleBtn = document.getElementById('access-btn');
      const zoomSelect = document.getElementById('zoom-mode');
      const zoomContainer = document.getElementById('zoom-container');
      let ttsEnabled = false;

      toggleBtn.addEventListener('click', () => {
        panel.classList.toggle('open');
      });

      document.addEventListener('click', (e) => {
        if (!panel.contains(e.target) && !toggleBtn.contains(e.target)) {
          panel.classList.remove('open');
        }
      });

      let isDragging = false, offsetX, offsetY;

      panel.addEventListener('mousedown', function (e) {
        if (!panel.classList.contains('open')) return;
        isDragging = true;
        offsetX = e.clientX - panel.offsetLeft;
        offsetY = e.clientY - panel.offsetTop;
        panel.style.cursor = 'grabbing';
      });

      document.addEventListener('mouseup', () => {
        isDragging = false;
        panel.style.cursor = 'move';
      });

      document.addEventListener('mousemove', (e) => {
        if (isDragging) {
          panel.style.left = `${e.clientX - offsetX}px`;
          panel.style.top = `${e.clientY - offsetY}px`;
        }
      });

      // Page zoom handler with isolated zoom container
      zoomSelect.addEventListener('change', () => {
        const value = zoomSelect.value;
        let scale = 1;
        if (value === '150') scale = 1.5;
        else if (value === '200') scale = 2;
        else if (value === '300') scale = 3;

        zoomContainer.style.transform = `scale(${scale})`;
        zoomContainer.style.transformOrigin = 'top left';
      });

      // TTS
      const speak = (text) => {
        if (!ttsEnabled) return;
        const msg = new SpeechSynthesisUtterance(text);
        window.speechSynthesis.cancel();
        window.speechSynthesis.speak(msg);
      };

      const toggleSpeech = document.getElementById('toggle-speech');
      toggleSpeech.addEventListener('click', () => {
        ttsEnabled = !ttsEnabled;
        toggleSpeech.innerText = ttsEnabled ? 'TTS: ON (Click to turn OFF)' : 'TTS: OFF (Click to turn ON)';
      });

      document.body.addEventListener('mouseover', (e) => {
        if (ttsEnabled && e.target.closest('button, a, p, h1, h2, h3, span')) {
          speak(e.target.innerText);
        }
      });
    });


  function resetView() {
    document.body.classList.remove('grayscale', 'high-contrast', 'readable-font');
    const canvas = document.getElementById('magnifier');
    canvas.style.display = 'none';
    window.speechSynthesis.cancel();
  }

  function toggleMobileNav() {
    const nav = document.getElementById("navmenu");
    nav.classList.toggle("open");
  }

  document.addEventListener("DOMContentLoaded", () => {
    const track = document.querySelector(".carousel-track");
    const slides = Array.from(track.children);
    const prevBtn = document.querySelector(".prev-btn");
    const nextBtn = document.querySelector(".next-btn");
    let currentSlide = 0;

    function updateCarousel(newIndex) {
      slides[currentSlide].classList.remove("active");
      slides[newIndex].classList.add("active");
      currentSlide = newIndex;
    }

    nextBtn.addEventListener("click", () => {
      const nextIndex = (currentSlide + 1) % slides.length;
      updateCarousel(nextIndex);
    });

    prevBtn.addEventListener("click", () => {
      const prevIndex = (currentSlide - 1 + slides.length) % slides.length;
      updateCarousel(prevIndex);
    });

    setInterval(() => {
      const nextIndex = (currentSlide + 1) % slides.length;
      updateCarousel(nextIndex);
    }, 5000);
  });
 window.onscroll = function () {
  const btn = document.getElementById("scrollTopBtn");
  btn.style.display = (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) ? "block" : "none";
};

function scrollToTop() {
  window.scrollTo({ top: 0, behavior: "smooth" });
}
</script>

<script>
  const goTopBtn = document.getElementById("goTopBtn");

  window.addEventListener("scroll", () => {
    if (window.scrollY > 100) {
      goTopBtn.classList.add("show");
    } else {
      goTopBtn.classList.remove("show");
    }
  });

  goTopBtn.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
</script>

<script>
  // Filtering guide cards
  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      // Mark active filter
      document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const filter = btn.getAttribute('data-filter');
      const cards = document.querySelectorAll('.guide-card');
      let firstVisible = null;

      cards.forEach(card => {
        const guide = card.getAttribute('data-guide');
        if (filter === 'all' || filter === guide) {
          card.style.display = 'block';
          if (!firstVisible && filter !== 'all') {
            firstVisible = guide;
          }
        } else {
          card.style.display = 'none';
        }
      });

      // Automatically show steps if not "all"
      const stepsPanel = document.getElementById('steps-panel');
      if (filter !== 'all' && firstVisible) {
        showSteps(firstVisible);
      } else {
        stepsPanel.innerHTML = `<p><em>Select a guide card to view step-by-step instructions here.</em></p>`;
      }
    });
  });
</script>
<script>
  const goTopBtn = document.getElementById("goTopBtn");

  window.onscroll = function () {
    if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
      goTopBtn.classList.add("show");
    } else {
      goTopBtn.classList.remove("show");
    }
  };

  goTopBtn.onclick = function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };
</script>
<script>
let currentGuide = '';
let stepsVisible = false;

const guides = {
  login: {
    en: `<h3>Step-by-Step Login Guide (English)</h3>
    <div class="step-list">
      <div class="step"><strong>Step 1: Access the Login Page</strong><br>Find and click the <strong>Login</strong> button located at the top right corner of the website.</div>
      <div class="step"><strong>Step 2: Enter Your Credentials</strong><br>Type your <strong>email</strong> and <strong>password</strong> in the appropriate fields.<br>In the <em>“Login as”</em> dropdown menu, select your role:<br><strong>User</strong> – if you’re looking for a job<br><strong>Client</strong> – if you want to hire PWD workers<br><strong>Admin</strong> – for system administrators<br>Optional: Check <strong>“Remember Me”</strong> if you want your login details to be remembered next time.<br>Click the <strong>Login</strong> button to continue.</div>
      <div class="step"><strong>Step 3: Dashboard Access or Error</strong><br>If your credentials are correct, you'll be taken to your dashboard.<br>If there's an error, you’ll receive a message. Double-check your email and password for any mistakes.</div>
      <div class="step"><strong>Step 4: Forgot Your Password?</strong><br>If you forgot your password:<br>Click <strong>“Forgot password”</strong>.<br>Enter your email address.<br>Wait for a reset link to arrive in your inbox.<br>Click the link, set a new password, and log in again.</div>
    </div>`,
    tl: `<h3>Hakbang-Hakbang na Gabay sa Pag-login (Tagalog)</h3>
    <div class="step-list">
      <div class="step"><strong>Unang Hakbang: Buksan ang Login Page</strong><br>Hanapin ang <strong>Login</strong> button sa kanang itaas ng website at i-click ito.</div>
      <div class="step"><strong>Ikalawang Hakbang: Ilagay ang Iyong Impormasyon</strong><br>I-type ang iyong <strong>email</strong> at <strong>password</strong> sa tamang mga field.<br>Sa dropdown ng <em>“Login bilang”</em>, pumili ng naaangkop na role:<br><strong>User</strong> – kung ikaw ay naghahanap ng trabaho<br><strong>Client</strong> – kung gusto mong kumuha ng PWD workers<br><strong>Admin</strong> – para sa system administrator<br><strong>Opsyonal:</strong> I-check ang <strong>“Remember Me”</strong> kung gusto mong awtomatikong maalala ang iyong login sa susunod.<br>Pindutin ang <strong>Login</strong> button para magpatuloy.</div>
      <div class="step"><strong>Ikatlong Hakbang: Dashboard o Error Message</strong><br>Kung tama ang credentials, madadala ka sa iyong dashboard.<br>Kung mali ang email o password, makakatanggap ka ng error message. I-double check ang iyong email at password kung may mali.</div>
      <div class="step"><strong>Ikaapat na Hakbang: Nakalimutan ang Password?</strong><br>Kung nakalimutan mo ang iyong password:<br>I-click ang <strong>“Forgot password”</strong><br>Ilagay ang iyong email address<br>Hintayin ang reset link sa iyong inbox<br>I-click ang link, gumawa ng bagong password, at mag-login muli</div>
    </div>`
  },
  register: {
    en: `<h3>How to Register (English)</h3>
    <div class="step-list">
      <div class="step">Click the “Register” button found at the top-right corner of the homepage, beside “Login.”</div>
      <div class="step">Choose your role by selecting either “I’m a job seeker, looking for work” or “I’m a client, appointing for opportunity.” Once selected, click “Create Account.”</div>
      <div class="step">If you're a job seeker (PWD), begin the verification steps below. If you're a client, you may proceed directly to account creation.</div>
      <div class="step">For PWD job seekers, use your device to scan the QR code to verify your PWD ID.</div>
      <div class="step">Upload or take a photo of the front and back of your PWD ID. If you are a client, you only need to provide a valid ID.</div>
      <div class="step">Complete the face recognition process to confirm your identity.</div>
      <div class="step">Once verified, your personal details will automatically appear. You may edit any information if necessary.</div>
      <div class="step">Select your job preference based on your type of disability. This ensures you're matched with suitable job opportunities.</div>
      <div class="step">A resume will be generated for you automatically. However, you also have the option to upload your own resume.</div>
      <div class="step">Finally, enter your email address. A verification link will be sent to your inbox to complete your registration.</div>
    </div>`,
    tl: `<h3>Gabay sa Pagrehistro (Tagalog)</h3>
    <div class="step-list">
      <div class="step">I-click ang “Register” na makikita sa kanang-itaas ng homepage, katabi ng "Login".</div>
      <div class="step">Pumili ng role:<br>I-click ang bilog para sa “I’m a job seeker, looking for work”<br>O “I’m a client, appointing for opportunity”<br>Pagkatapos pumili, i-click ang “Create Account.”</div>
      <div class="step"><strong>Tandaan:</strong><br>Kung ikaw ay Job Seeker (PWD), magsisimula ka sa Step 1.<br>Kung ikaw ay Client, deretso ka na sa Step 2.</div>
      <div class="step"><strong>Para sa PWD Job Seekers:</strong><br>Gamitin ang iyong device para i-scan ang QR code upang i-verify ang iyong PWD ID.<br>Mag-upload o kumuha ng larawan ng harap at likod ng iyong PWD ID.<br>Para sa clients, valid ID lamang ang kailangan.<br>Kumpletuhin ang face recognition bilang patunay ng iyong pagkakakilanlan.<br>Awtomatikong lalabas ang iyong personal na detalye mula sa ID. Pwede mong i-edit kung kinakailangan.<br>Pumili ng job preference batay sa iyong uri ng kapansanan para makahanap ng trabahong angkop sa iyo.<br>Awtomatikong gagawin ang iyong resume. Pwede ka ring mag-upload ng sarili mong resume.<br>Ilagay ang iyong email address at kumpirmahin ito sa pamamagitan ng verification link sa email.</div>
    </div>`
  }
};

const timelines = {
  login: {
    en: [{time:0,step:0},{time:6,step:1},{time:18,step:2},{time:28,step:3}],
    tl: [{time:0,step:0},{time:7,step:1},{time:20,step:2},{time:32,step:3}]
  },
  register: {
    en: [{time:0,step:0},{time:5,step:1},{time:12,step:2},{time:20,step:3},{time:28,step:4},{time:36,step:5},{time:44,step:6},{time:52,step:7},{time:60,step:8}],
    tl: [{time:0,step:0},{time:6,step:1},{time:14,step:2},{time:22,step:3}]
  }
};

function setGuideAudio(event, btn, guide, lang) {
  event.stopPropagation();
  const audio = document.getElementById('audio-' + guide);
  const source = audio.querySelector('source');
  
  let fileName = '';
  if (guide === 'login') {
    fileName = `HOWTOLOGIN-${lang === 'en' ? 'ENGLISH' : 'TAGALOG'}`;
  } else if (guide === 'register') {
    fileName = `REGISTER${lang === 'en' ? 'ENGLISH' : 'TAGALOG'}`;
  }
  
  source.src = `../assets/audio/${fileName}.mp3`;
  audio.load();
  audio.play().catch(() => {});

  // Active button
  btn.parentElement.querySelectorAll('button').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  // Show steps
  currentGuide = guide;
  document.getElementById('steps-panel').style.display = 'block';
  document.getElementById('guide-content').innerHTML = guides[guide][lang];
  document.getElementById('toggle-steps-btn').classList.add('d-none');

  // Sync highlight
  audio.ontimeupdate = () => {
    const t = audio.currentTime;
    const timeline = timelines[guide][lang] || [];
    let active = -1;
    for (let i = timeline.length - 1; i >= 0; i--) {
      if (t >= timeline[i].time) { active = timeline[i].step; break; }
    }
    document.querySelectorAll('#guide-content .step').forEach((el, i) => {
      el.classList.toggle('highlight', i === active);
    });
  };
}

function selectGuide(guide) {
  currentGuide = guide;
  stepsVisible = false;
  document.getElementById('steps-panel').style.display = 'none';
  document.getElementById('toggle-steps-btn').classList.remove('d-none');
  document.getElementById('toggle-steps-btn').innerText = 'Show Step-by-Step Guide';
}

function toggleSteps() {
  stepsVisible = !stepsVisible;
  const panel = document.getElementById('steps-panel');
  panel.style.display = stepsVisible ? 'block' : 'none';
  if (stepsVisible) {
    document.getElementById('guide-content').innerHTML = guides[currentGuide]['en'];
  }
  document.getElementById('toggle-steps-btn').innerText = stepsVisible ? 'Hide Step-by-Step Guide' : 'Show Step-by-Step Guide';
}

function toggleLanguage(guide, lang) {
  if (guides[guide] && guides[guide][lang]) {
    document.getElementById('guide-content').innerHTML = guides[guide][lang];
  }
}

// Filter
document.querySelectorAll('.filter-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const filter = btn.dataset.filter;
    document.querySelectorAll('.guide-card').forEach(card => {
      card.style.display = (filter === 'all' || card.dataset.guide === filter) ? 'block' : 'none';
    });
    document.getElementById('steps-panel').style.display = 'none';
    document.getElementById('toggle-steps-btn').classList.add('d-none');
  });
});
</script>

<script>
document.getElementById('contactForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  const res = await fetch(this.action, {
    method: 'POST',
    body: formData
  });
  const result = await res.text();
  alert(result); // Shows response (success or error)
});
</script>
<script>
  window.onload = function () {
    const goTopBtn = document.getElementById("goTopBtn");

    window.addEventListener("scroll", () => {
      if (window.scrollY > 100) {
        goTopBtn.classList.add("show");
      } else {
        goTopBtn.classList.remove("show");
      }
    });

    goTopBtn.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  };
</script>

<script>
// ===== Magnifier =====
let magnifierEnabled = false;
const magnifier = document.createElement("div");
magnifier.className = "magnifier";
document.body.appendChild(magnifier);

document.addEventListener("mousemove", function (e) {
  if (!magnifierEnabled) return;

  const main = document.querySelector("main");
  const rect = main.getBoundingClientRect();
  const zoom = 2;

  if (
    e.clientX < rect.left || e.clientX > rect.right ||
    e.clientY < rect.top || e.clientY > rect.bottom
  ) {
    magnifier.style.display = "none";
    return;
  }

  magnifier.style.display = "block";
  magnifier.style.left = `${e.pageX - 60}px`;
  magnifier.style.top = `${e.pageY - 60}px`;
  magnifier.style.backgroundColor = "#fff";
  magnifier.style.backgroundImage = `none`;
});

// ===== TTS =====
let ttsEnabled = false;
const ttsBtn = document.getElementById("toggle-tts");

if (ttsBtn) {
  ttsBtn.addEventListener("click", () => {
    ttsEnabled = !ttsEnabled;
    ttsBtn.innerText = ttsEnabled ? "TTS: ON (Click to turn OFF)" : "TTS: OFF (Click to turn ON)";
    if (!ttsEnabled) speechSynthesis.cancel();
  });

  document.body.addEventListener("mouseover", (e) => {
    if (!ttsEnabled) return;
    const text = e.target.innerText || e.target.alt;
    if (text && text.trim().length > 0 && text.trim().length < 250) {
      const utter = new SpeechSynthesisUtterance(text.trim());
      speechSynthesis.cancel();
      speechSynthesis.speak(utter);
    }
  });
}
</script>
<script>
// Magnifier setup
let magnifierEnabled = false;
const magnifier = document.createElement("div");
magnifier.className = "magnifier";
document.body.appendChild(magnifier);

document.addEventListener("mousemove", function (e) {
  if (!magnifierEnabled) return;

  const main = document.querySelector("main");
  if (!main) return;
  const rect = main.getBoundingClientRect();
  const zoom = 2;

  if (
    e.clientX < rect.left || e.clientX > rect.right ||
    e.clientY < rect.top || e.clientY > rect.bottom
  ) {
    magnifier.style.display = "none";
    return;
  }

  magnifier.style.display = "block";
  magnifier.style.left = `${e.pageX - 60}px`;
  magnifier.style.top = `${e.pageY - 60}px`;
  magnifier.style.backgroundColor = "#fff";
  magnifier.style.backgroundImage = `none`;
});

function setZoom(level) {
  const main = document.querySelector("main");
  if (!main) return;

  main.classList.remove("zoom-150", "zoom-200", "zoom-300");
  if (level !== "default") {
    main.classList.add(`zoom-${level}`);
    magnifierEnabled = true;
    magnifier.style.display = "block";
  } else {
    magnifierEnabled = false;
    magnifier.style.display = "none";
  }
}
</script>
<script>
  const zoomContainer = document.getElementById('zoom-container') || document.body;

  function setZoom(level) {
    let scale = 1;
    if (level === '100') scale = 1;
    else if (level === '150') scale = 1.5;
    else if (level === '200') scale = 2.0;
    else if (level === '300') scale = 3.0;
    else scale = 1;

    zoomContainer.style.transform = `scale(${scale})`;
    zoomContainer.style.transformOrigin = 'top left';
  }
</script>

<script>
  window.addEventListener("scroll", function () {
  const header = document.querySelector(".header");
  header.classList.toggle("scrolled", window.scrollY > 50);
});
</script>
...
<script>
function toggleMobileNav() {
  const leftNav = document.querySelector('.left-nav');
  const rightNav = document.querySelector('.right-nav');
  const sitename = document.querySelector('.sitename');

  // Toggle menus
  leftNav.classList.toggle('show');
  rightNav.classList.toggle('show');

  // Hide site name when menu is open
  if (leftNav.classList.contains('show') || rightNav.classList.contains('show')) {
    sitename.style.display = 'none';
  } else {
    sitename.style.display = '';
  }
}
</script>
<script>
function toggleMobileNav() {
  const leftNav = document.querySelector('.left-nav');
  const rightNav = document.querySelector('.right-nav');
  const sitename = document.querySelector('.sitename');

  leftNav.classList.toggle('show');
  rightNav.classList.toggle('show');

  if (leftNav.classList.contains('show') || rightNav.classList.contains('show')) {
    sitename.style.display = 'none';
  } else {
    sitename.style.display = '';
  }
}
</script>
<script>
function toggleMobileNav(forceClose = false) {
  let menu = document.querySelector(".mobile-menu");
  let overlay = document.querySelector(".mobile-overlay");
  let toggleBtn = document.querySelector(".mobile-nav-toggle");

  // Create menu & overlay if not in DOM
  if (!menu) {
    menu = document.createElement("div");
    menu.className = "mobile-menu";
    document.body.appendChild(menu);

    // Clone links
    const leftLinks = document.querySelector(".left-nav ul").cloneNode(true);
    const rightLinks = document.querySelector(".right-nav ul").cloneNode(true);
    menu.appendChild(leftLinks);
    menu.appendChild(document.createElement("hr"));
    menu.appendChild(rightLinks);

    // Overlay
    overlay = document.createElement("div");
    overlay.className = "mobile-overlay";
    overlay.onclick = () => toggleMobileNav(true);
    document.body.appendChild(overlay);

    // Auto-close menu when link is clicked
    menu.querySelectorAll("a").forEach(link => {
      link.addEventListener("click", () => toggleMobileNav(true));
    });
  }

  // Toggle menu open/close
  if (forceClose) {
    menu.classList.remove("active");
    overlay.classList.remove("active");
    toggleBtn.classList.remove("active");
  } else {
    menu.classList.toggle("active");
    overlay.classList.toggle("active");
    toggleBtn.classList.toggle("active");
  }
}
</script>



<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// Hardcoded origin (Downtown Bacolod) and destinations
const ORIGIN = [10.6765, 122.9621]; // downtown
const DESTINATIONS = {
  web: [10.6840, 122.9560],      // Bacolod City ICT Center
  design: [10.6769, 122.9677],   // Bacolod Art District
  marketing: [10.6931, 122.9580] // Bacolod Resource Hub
};

const maps = {};
const routeLayers = {};
const startMarkers = {};
const destMarkers = {};

function initMapOnce(key, mapId) {
  if (maps[key]) return;
  const coords = DESTINATIONS[key];
  const map = L.map(mapId).setView(coords, 14);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);
  maps[key] = map;
  // add destination marker
  destMarkers[key] = L.marker(coords).addTo(map).bindPopup("Destination").openPopup();
  // add origin marker
  startMarkers[key] = L.marker(ORIGIN, {icon: L.icon({iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png'})}).addTo(map).bindPopup("Downtown (Origin)");
}

// Fetch route from OSRM and draw it
async function drawRoute(key) {
  const map = maps[key];
  if (!map) return;
  // remove existing layer
  if (routeLayers[key]) {
    map.removeLayer(routeLayers[key]);
    routeLayers[key] = null;
  }
  const src = ORIGIN;
  const dst = DESTINATIONS[key];
  const url = `https://router.project-osrm.org/route/v1/driving/${src[1]},${src[0]};${dst[1]},${dst[0]}?overview=full&geometries=geojson&steps=true`;
  try {
    const res = await fetch(url);
    const data = await res.json();
    if (data.code !== 'Ok' || !data.routes || data.routes.length === 0) {
      console.error('No route', data);
      return;
    }
    const route = data.routes[0];
    const coords = route.geometry.coordinates.map(c => [c[1], c[0]]);
    routeLayers[key] = L.polyline(coords, {color:'#1f78b4', weight:5, opacity:0.9}).addTo(map);
    map.fitBounds(routeLayers[key].getBounds(), {padding:[40,40]});
    // show summary and steps
    const infoDiv = document.getElementById('distanceOutput-' + key);
    if (infoDiv) {
      infoDiv.style.display = 'block';
      const distKm = (route.distance/1000).toFixed(2);
      const durMin = Math.round(route.duration/60);
      let html = `<strong>From:</strong> Downtown (Origin)<br><strong>To:</strong> Destination<br><strong>Distance:</strong> ${distKm} km<br><strong>Duration:</strong> ${durMin} mins`;
      html += '<div class="steps-list"><strong>Steps:</strong><ol>';
      if (route.legs && route.legs.length) {
        const steps = route.legs[0].steps;
        for (let s of steps) {
          html += `<li>${s.maneuver.instruction || s.name || s.maneuver.type} <small>(${(s.distance/1000).toFixed(2)} km)</small></li>`;
        }
      }
      html += '</ol></div>';
      infoDiv.innerHTML = html;
    }
  } catch (err) {
    console.error('Route fetch error', err);
  }
}

// Initialize maps when modals open and draw route
document.addEventListener('DOMContentLoaded', function() {
  const mapping = [
    {modalId: 'webDevModal', key: 'web', mapId: 'map-web'},
    {modalId: 'graphicDesignModal', key: 'design', mapId: 'map-design'},
    {modalId: 'digitalMarketingModal', key: 'marketing', mapId: 'map-marketing'}
  ];
  mapping.forEach(item => {
    const modalEl = document.getElementById(item.modalId);
    if (!modalEl) return;
    modalEl.addEventListener('shown.bs.modal', function() {
      initMapOnce(item.key, item.mapId);
      setTimeout(()=> { drawRoute(item.key); }, 300); // slight delay to ensure map sizing
    });
  });
});
</script>

</body>
</html>
