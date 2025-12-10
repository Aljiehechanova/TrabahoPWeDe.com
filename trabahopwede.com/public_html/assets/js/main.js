(function () {
  "use strict";

  const body = document.body;
  const header = document.querySelector('#header');
  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');
  const scrollTop = document.querySelector('.scroll-top');
  const navLinks = document.querySelectorAll('.navmenu a');

  /* ===============================
     Header Scroll Toggle
  =============================== */
  function toggleScrolled() {
    if (!header) return;
    if (window.scrollY > 100) {
      body.classList.add('scrolled');
      header.classList.add('header-scrolled');
    } else {
      body.classList.remove('scrolled');
      header.classList.remove('header-scrolled');
    }
  }

  /* ===============================
     Mobile Navigation
  =============================== */
  function mobileNavToggle() {
    body.classList.toggle('mobile-nav-active');
    mobileNavToggleBtn?.classList.toggle('bi-list');
    mobileNavToggleBtn?.classList.toggle('bi-x');
  }
  if (mobileNavToggleBtn) mobileNavToggleBtn.addEventListener('click', mobileNavToggle);

  document.querySelectorAll('#navmenu a').forEach(nav => {
    nav.addEventListener('click', () => {
      if (body.classList.contains('mobile-nav-active')) mobileNavToggle();
    });
  });

  /* ===============================
     Scroll-to-Top Button
  =============================== */
  function toggleScrollTop() {
    if (!scrollTop) return;
    window.scrollY > 100
      ? scrollTop.classList.add('active')
      : scrollTop.classList.remove('active');
  }
  if (scrollTop) {
    scrollTop.addEventListener('click', e => {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ===============================
     Scroll Animations (Fade-in/Slide-in)
  =============================== */
  function animateOnScroll() {
    document.querySelectorAll('[data-animate]').forEach(el => {
      const rect = el.getBoundingClientRect();
      if (rect.top < window.innerHeight - 100) {
        el.classList.add('animate');
      }
    });
  }

  /* ===============================
     Custom Slider (Auto + Dots + Arrows)
  =============================== */
  function initSlider() {
    document.querySelectorAll('.slider').forEach(slider => {
      let index = 0;
      const slides = slider.querySelectorAll('.slide');
      const dotsContainer = slider.querySelector('.slider-dots');
      const prevBtn = slider.querySelector('.slider-prev');
      const nextBtn = slider.querySelector('.slider-next');

      // Create dots dynamically
      if (dotsContainer && slides.length > 1) {
        slides.forEach((_, i) => {
          const dot = document.createElement('button');
          dot.classList.add('dot');
          if (i === 0) dot.classList.add('active');
          dot.addEventListener('click', () => {
            index = i;
            showSlide(index);
          });
          dotsContainer.appendChild(dot);
        });
      }

      function showSlide(i) {
        slides.forEach((s, idx) => {
          s.style.transform = `translateX(${100 * (idx - i)}%)`;
        });
        if (dotsContainer) {
          dotsContainer.querySelectorAll('.dot').forEach(dot => dot.classList.remove('active'));
          dotsContainer.children[i].classList.add('active');
        }
      }

      if (prevBtn) prevBtn.addEventListener('click', () => {
        index = (index - 1 + slides.length) % slides.length;
        showSlide(index);
      });

      if (nextBtn) nextBtn.addEventListener('click', () => {
        index = (index + 1) % slides.length;
        showSlide(index);
      });

      // Auto play
      if (slides.length > 1) {
        setInterval(() => {
          index = (index + 1) % slides.length;
          showSlide(index);
        }, 5000);
      }

      showSlide(index);
    });
  }

  /* ===============================
     Counter Animation
  =============================== */
  function initCounters() {
    document.querySelectorAll('.purecounter').forEach(counter => {
      const end = parseInt(counter.dataset.purecounterEnd) || 0;
      const duration = parseInt(counter.dataset.purecounterDuration) || 1000;
      let start = parseInt(counter.dataset.purecounterStart) || 0;
      const increment = (end - start) / (duration / 20);

      function update() {
        start += increment;
        if (start < end) {
          counter.textContent = Math.floor(start);
          setTimeout(update, 20);
        } else {
          counter.textContent = end;
        }
      }
      update();
    });
  }

  /* ===============================
     Lightbox (Video/Image Modal)
  =============================== */
  function initLightbox() {
    document.querySelectorAll('.glightbox').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        const videoSrc = link.getAttribute('href');
        const modal = document.createElement('div');
        modal.className = 'lightbox-modal';
        modal.innerHTML = `
          <div class="lightbox-content">
            <span class="lightbox-close">&times;</span>
            <iframe src="${videoSrc}" frameborder="0" allowfullscreen></iframe>
          </div>`;
        document.body.appendChild(modal);

        modal.querySelector('.lightbox-close').addEventListener('click', () => modal.remove());
        modal.addEventListener('click', e => {
          if (e.target === modal) modal.remove();
        });
      });
    });
  }

  /* ===============================
     Scrollspy
  =============================== */
  function navmenuScrollspy() {
    const scrollY = window.scrollY + 200;
    navLinks.forEach(link => {
      if (!link.hash) return;
      const section = document.querySelector(link.hash);
      if (!section) return;
      if (scrollY >= section.offsetTop && scrollY <= section.offsetTop + section.offsetHeight) {
        document.querySelectorAll('.navmenu a.active').forEach(el => el.classList.remove('active'));
        link.classList.add('active');
      }
    });
  }

  /* ===============================
     Event Listeners
  =============================== */
  window.addEventListener('scroll', () => {
    toggleScrolled();
    toggleScrollTop();
    navmenuScrollspy();
    animateOnScroll();
  });

  window.addEventListener('load', () => {
    toggleScrolled();
    toggleScrollTop();
    animateOnScroll();
    initSlider();
    initCounters();
    initLightbox();
    navmenuScrollspy();

    // Smooth scroll to hash on load
    if (window.location.hash && document.querySelector(window.location.hash)) {
      setTimeout(() => {
        const section = document.querySelector(window.location.hash);
        const offset = parseInt(getComputedStyle(section).scrollMarginTop);
        window.scrollTo({ top: section.offsetTop - offset, behavior: 'smooth' });
      }, 100);
    }
  });
})();

// ==============================
// PWD Tools JS
// ==============================

// Elements
const wrapper = document.getElementById('content-wrapper') || document.body;
const magnifier = document.getElementById("magnifier");
let magnifierEnabled = false;
let snapshotCanvas = null;

// Toggle Accessibility Panel
document.getElementById("access-btn").addEventListener("click", () => {
  document.getElementById("accessibility-panel").classList.toggle("open");
});

// Toggle Effects
function toggleGrayscale() { wrapper.classList.toggle('grayscale'); }
function toggleContrast() { wrapper.classList.toggle('high-contrast'); }
function toggleReadableFont() { wrapper.classList.toggle('readable-font'); }

// Reset View
function resetView() {
  wrapper.classList.remove('grayscale', 'high-contrast', 'readable-font', 'magnifier-active');
  magnifier.style.display = 'none';
  snapshotCanvas = null;
  document.removeEventListener('mousemove', moveMagnifier);
}

// Magnifier
function toggleMagnifier() {
  magnifierEnabled = !magnifierEnabled;
  if (magnifierEnabled) {
    if (typeof html2canvas === "undefined") {
      console.error("html2canvas library is required for magnifier.");
      return;
    }
    html2canvas(wrapper).then(canvas => {
      snapshotCanvas = canvas;
      wrapper.classList.add('magnifier-active');
      magnifier.style.display = 'block';
      document.addEventListener('mousemove', moveMagnifier);
    });
  } else {
    resetView();
  }
}

function moveMagnifier(e) {
  if (!snapshotCanvas || !magnifier.getContext) return;
  const zoom = 1.5;
  const size = 250;
  magnifier.style.left = `${e.pageX - size / 2}px`;
  magnifier.style.top = `${e.pageY - size / 2}px`;
  const ctx = magnifier.getContext('2d');
  ctx.clearRect(0, 0, size, size);
  ctx.drawImage(snapshotCanvas,
    e.pageX - size / (2 * zoom), e.pageY - size / (2 * zoom),
    size / zoom, size / zoom,
    0, 0, size, size
  );
}

// ==========================
// GUIDE Section Functionality
// ==========================
const stepsData = {
  login: `
    <p>
      <strong>Step 1:</strong> Open the app or website<br>
      <strong>Step 2:</strong> Click "Login"<br>
      <strong>Step 3:</strong> Enter your credentials<br>
      <strong>Step 4:</strong> Tap "Submit"
    </p>
  `,
  register: `
    <p>
      <strong>Step 1:</strong> Click "Sign Up"<br>
      <strong>Step 2:</strong> Fill in name, email, password<br>
      <strong>Step 3:</strong> Click "Create Account"
    </p>
  `,
  dojob: `
    <p>
      <strong>Step 1:</strong> Login<br>
      <strong>Step 2:</strong> Select a task<br>
      <strong>Step 3:</strong> Follow on-screen guide<br>
      <strong>Step 4:</strong> Submit report
    </p>
  `
};

document.addEventListener('DOMContentLoaded', () => {
  const filterBtns = document.querySelectorAll('.filter-btn');
  const guideCards = document.querySelectorAll('.guide-card');
  const stepsPanel = document.getElementById('steps-panel');

  // Filter Buttons Click
  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      // Remove active from all
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const filter = btn.dataset.filter;

      // Show/hide cards
      guideCards.forEach(card => {
        if (filter === 'all' || card.dataset.guide === filter) {
          card.classList.remove('hidden');
        } else {
          card.classList.add('hidden');
        }
      });

      // Reset steps panel if no steps for 'all'
      if (filter === 'all') {
        stepsPanel.innerHTML = `<p>Select a guide to see the steps here.</p>`;
        stepsPanel.classList.remove('active');
      } else {
        stepsPanel.innerHTML = stepsData[filter] || `<p>No steps available.</p>`;
        stepsPanel.classList.add('active');
      }
    });
  });

  // Clicking a guide card shows steps
  guideCards.forEach(card => {
    card.addEventListener('click', () => {
      const guideType = card.dataset.guide;
      stepsPanel.innerHTML = stepsData[guideType] || `<p>No steps available.</p>`;
      stepsPanel.classList.add('active');
    });
  });
});


// Auto-slide every 4 seconds
setInterval(() => {
  currentIndex = (currentIndex + 1) % slides.length;
  moveToSlide(currentIndex);
}, 4000);

// Ensure correct positioning on window resize
window.addEventListener('resize', () => {
  moveToSlide(currentIndex);
});


/* ===============================
   Text-to-Speech on Selection
=============================== */
document.addEventListener("mouseup", () => {
  const selection = window.getSelection().toString().trim();
  if (selection.length > 0) {
    speechSynthesis.cancel();
    speechSynthesis.speak(new SpeechSynthesisUtterance(selection));
  }
});

