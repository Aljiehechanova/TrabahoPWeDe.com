<?php
?>
<div id="accessibility-root">
  <!-- Toggle Button -->
  <button id="access-toggle" class="access-toggle" aria-expanded="false" aria-controls="access-content" aria-label="Open accessibility tools">
      <img src="../assets/images/accessibility.png" alt="PWD tools" class="access-icon">
  </button>

  <!-- Panel -->
  <div id="access-content" class="access-content" hidden>
    <h3>PWD Support Tools</h3>

    <div class="access-controls">
      <button class="access-btn" id="btn-grayscale" aria-pressed="false">Grayscale</button>
      <button class="access-btn" id="btn-contrast" aria-pressed="false">High Contrast</button>
      <button class="access-btn" id="btn-readable" aria-pressed="false">Readable Font</button>
    </div>

    <div id="zoom-controls" class="zoom-controls">
      <button class="access-btn" data-zoom="100">Zoom 100%</button>
      <button class="access-btn" data-zoom="150">Zoom 150%</button>
      <button class="access-btn" data-zoom="200">Zoom 200%</button>
      <button class="access-btn" data-zoom="300">Zoom 300%</button>
      <button class="access-btn" data-zoom="default">Reset Zoom</button>
    </div>

    <div class="tts-controls">
      <button class="access-btn" id="toggle-tts" aria-pressed="false">TTS: OFF</button>
    </div>

    <div>
      <button class="access-btn" id="btn-reset">Reset</button>
    </div>
  </div>
</div>

<script>
(function(){
  const $ = s => document.querySelector(s);

  const toggleBtn = $('#access-toggle');
  const content = $('#access-content');
  const btnGrayscale = $('#btn-grayscale');
  const btnContrast = $('#btn-contrast');
  const btnReadable = $('#btn-readable');
  const zoomControls = $('#zoom-controls');
  const toggleTtsBtn = $('#toggle-tts');
  const btnReset = $('#btn-reset');
  const zoomContainer = document.querySelector('main') || document.body;

  let ttsEnabled = false;

  // Toggle panel
  toggleBtn.addEventListener('click', () => {
    const open = content.hidden;
    content.hidden = !open;
    toggleBtn.setAttribute('aria-expanded', open);
  });

  // Style toggles
  function toggleClass(cls, btn) {
    document.documentElement.classList.toggle(cls);
    btn.setAttribute('aria-pressed', document.documentElement.classList.contains(cls));
  }
  btnGrayscale.onclick = () => toggleClass('grayscale', btnGrayscale);
  btnContrast.onclick = () => toggleClass('high-contrast', btnContrast);
  btnReadable.onclick = () => toggleClass('readable-font', btnReadable);

  // Zoom
  zoomControls.addEventListener('click', e => {
    const b = e.target.closest('button[data-zoom]');
    if (!b) return;
    const level = b.getAttribute('data-zoom');
    zoomContainer.style.transform = '';
    if (level !== 'default' && level !== '100') {
      zoomContainer.style.transform = `scale(${parseInt(level)/100})`;
      zoomContainer.style.transformOrigin = 'top left';
    }
  });

  // TTS
  function speak(txt){
    if (!window.speechSynthesis) return;
    window.speechSynthesis.cancel();
    window.speechSynthesis.speak(new SpeechSynthesisUtterance(txt));
  }
  toggleTtsBtn.onclick = () => {
    ttsEnabled = !ttsEnabled;
    toggleTtsBtn.textContent = ttsEnabled ? 'TTS: ON' : 'TTS: OFF';
    toggleTtsBtn.setAttribute('aria-pressed', ttsEnabled);
    if (!ttsEnabled) window.speechSynthesis.cancel();
  };
  document.body.addEventListener('mouseover', e => {
    if (ttsEnabled && e.target.innerText && e.target.innerText.length < 200) {
      speak(e.target.innerText);
    }
  });

  // Reset
  btnReset.onclick = () => {
    document.documentElement.classList.remove('grayscale','high-contrast','readable-font');
    zoomContainer.style.transform = '';
    ttsEnabled = false;
    toggleTtsBtn.textContent = 'TTS: OFF';
    toggleTtsBtn.setAttribute('aria-pressed','false');
    window.speechSynthesis.cancel();
  };
})();
</script>

<style>
/* Container */
#accessibility-root {
  position: fixed;
  top: 80px;
  right: 20px;
  z-index: 99999;
  font-family: Arial, sans-serif;
}

/* Toggle button (round blue button) */
.access-toggle {
  width: 90px;
  height: 90px;
  border: none;
  outline: none;
  background-color: #0326f0;
  cursor: pointer;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 6px 16px rgba(0,0,0,0.35);
  transition: background 0.3s, transform 0.2s;
  padding: 0;
}

.access-toggle:hover {
  background: #0041cc;
  transform: scale(1.05);
}

.access-toggle:focus {
  outline: none;
  box-shadow: 0 0 0 4px rgba(0, 86, 255, 0.4);
}

/* Icon inside button */
.access-toggle .access-icon {
  width: 60%;
  height: auto;
  display: block;
}

/* Panel */
.access-content {
  margin-top: 10px;
  background: #fff;
  border-radius: 12px;
  padding: 16px;
  box-shadow: 0 6px 16px rgba(0,0,0,0.25);
  min-width: 260px;
}
.access-content h3 {
  margin: 0 0 12px;
  font-size: 18px;
  font-weight: bold;
  color: #000;
}

/* Buttons */
.access-btn {
  display: inline-block;
  margin: 6px 4px;
  padding: 10px 16px;
  border-radius: 6px;
  border: none;
  background: #007bff;
  color: #fff;
  cursor: pointer;
  font-size: 14px;
  transition: background 0.3s;
}
.access-btn:hover {
  background: #0056cc;
}
.access-btn[aria-pressed="true"] {
  background: #0041cc;
  color: #fff;
}

/* Reset/Danger button */
.access-btn.danger {
  background: #c62828;
}
.access-btn.danger:hover {
  background: #b71c1c;
}

/* Effects */
.high-contrast, .high-contrast * { background: #000 !important; color: #ff0 !important; }
.grayscale, .grayscale * { filter: grayscale(100%) !important; }
.readable-font, .readable-font * { font-family: Arial, Helvetica, sans-serif !important; }
</style>
