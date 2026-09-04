(function () {
 'use strict';
 document.addEventListener('touchmove', function (e) {
 if (e.target.closest('.scrollable')) {
 return;
 }
 e.preventDefault();
 }, { passive: false });
 document.addEventListener('wheel', function (e) {
 if (e.target.closest('.scrollable')) {
 return;
 }
 e.preventDefault();
 }, { passive: false });
 document.addEventListener('keydown', function (e) {
 if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
 return;
 }
 const blocked = [' ', 'ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight',
 'PageUp', 'PageDown', 'Home', 'End'];
 if (blocked.includes(e.key)) {
 e.preventDefault();
 }
 });
 function updateClock() {
 var el = document.getElementById('clock');
 if (!el) return;
 var now = new Date();
 var h = String(now.getHours()).padStart(2, '0');
 var m = String(now.getMinutes()).padStart(2, '0');
 el.textContent = h + ':' + m;
 }
 updateClock();
 setInterval(updateClock, 15000);
 window.navigateToPage = function (url) {
 if (typeof Livewire !== 'undefined' && typeof Livewire.navigate === 'function') {
 Livewire.navigate(url);
 } else {
 window.location.href = url;
 }
 };
 var overlayRevealTimer = null;

 function ensurePageTransitionOverlay() {
 var frame = document.getElementById('mobile-frame') || document.body;
 var overlay = document.getElementById('page-transition-overlay');
 if (!overlay) {
 overlay = document.createElement('div');
 overlay.id = 'page-transition-overlay';
 overlay.innerHTML = '<div class="transition-content"><div class="transition-title">✦ MEMUAT... ✦</div><div class="transition-bar-container"><div class="transition-bar-fill"></div></div></div>';
 frame.appendChild(overlay);
 }
 return overlay;
 }

 function waitForPageAssets() {
 var promises = [];
 if (document.fonts && document.fonts.ready) {
 promises.push(document.fonts.ready.catch(function () {}));
 }
 promises.push(new Promise(function (resolve) {
 setTimeout(resolve, 250);
 }));
 var root = document.getElementById('mobile-frame') || document.body;
 if (root) {
 root.querySelectorAll('img[src]').forEach(function (img) {
 if (!img.complete) {
 promises.push(new Promise(function (resolve) {
 img.addEventListener('load', resolve, { once: true });
 img.addEventListener('error', resolve, { once: true });
 }));
 }
 });
 }
 return Promise.all(promises);
 }

 function fadeOutPageOverlay() {
 var overlay = ensurePageTransitionOverlay();
 if (!overlay || overlay.classList.contains('fade-out')) {
 return;
 }
 if (overlayRevealTimer) {
 clearTimeout(overlayRevealTimer);
 }
 var revealed = false;
 function reveal() {
 if (revealed) {
 return;
 }
 revealed = true;
 requestAnimationFrame(function () {
 overlay.classList.add('fade-out');
 });
 }
 waitForPageAssets().then(reveal).catch(reveal);
 overlayRevealTimer = setTimeout(reveal, 3500);
 }

 function initCurrentPage() {
 fadeOutPageOverlay();
 if (typeof window.updateCarousel === 'function' && document.getElementById('carousel-track')) {
 setTimeout(window.updateCarousel, 50);
 setTimeout(window.updateCarousel, 400);
 }
 if (typeof window.initJalurPreview === 'function' && document.getElementById('jalur-preview-container')) {
 var nameEl = document.getElementById('jalur-name');
 window.initJalurPreview('jalur-preview-container', nameEl ? 'jalur-name' : undefined);
 }
 if (typeof window.initPageUI === 'function') {
 window.initPageUI();
 }
 document.dispatchEvent(new CustomEvent('game:page-ready', {
 detail: { url: window.location.pathname }
 }));
 }

 document.addEventListener('DOMContentLoaded', initCurrentPage);
 document.addEventListener('livewire:navigating', function () {
 var overlay = ensurePageTransitionOverlay();
 overlay.classList.remove('fade-out');
 });
 document.addEventListener('livewire:navigated', initCurrentPage);
 window.addEventListener('pageshow', function (e) {
 if (e.persisted) {
 initCurrentPage();
 }
 });
 window.recolorCharacterImage = function (scene, sourceKey, customColors) {
 const sourceTexture = scene.textures.get(sourceKey);
 const sourceImage = sourceTexture.getSourceImage();
 const canvas = document.createElement('canvas');
 canvas.width = sourceImage.width;
 canvas.height = sourceImage.height;
 const ctx = canvas.getContext('2d');
 ctx.drawImage(sourceImage, 0, 0);
 const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
 const data = imgData.data;
 const targetHair = Phaser.Display.Color.HexStringToColor(customColors.hair);
 const targetShirt = Phaser.Display.Color.HexStringToColor(customColors.shirt);
 const targetPants = Phaser.Display.Color.HexStringToColor(customColors.pants);
 const targetPaddle = Phaser.Display.Color.HexStringToColor(customColors.paddle);
 for (let i = 0; i < data.length; i += 4) {
 const r = data[i];
 const g = data[i + 1];
 const b = data[i + 2];
 const a = data[i + 3];
 if (a < 10) continue;
 if (r < 40 && g < 40 && b < 40) continue;
 if (r - g > 100 && r - b > 100) {
 const factor = Math.min(1.2, r / 199);
 data[i] = Math.min(255, targetHair.r * factor);
 data[i + 1] = Math.min(255, targetHair.g * factor);
 data[i + 2] = Math.min(255, targetHair.b * factor);
 }
 else if (g - r > 50 && g - b > 40) {
 const factor = Math.min(1.2, g / 122);
 data[i] = Math.min(255, targetPants.r * factor);
 data[i + 1] = Math.min(255, targetPants.g * factor);
 data[i + 2] = Math.min(255, targetPants.b * factor);
 }
 else if (b - r > 80 && b - g > 40) {
 const factor = Math.min(1.2, b / 203);
 data[i] = Math.min(255, targetPaddle.r * factor);
 data[i + 1] = Math.min(255, targetPaddle.g * factor);
 data[i + 2] = Math.min(255, targetPaddle.b * factor);
 }
 else if (Math.abs(r - g) < 20 && Math.abs(g - b) < 20 && Math.abs(r - b) < 20) {
 const factor = Math.min(1.2, ((r + g + b) / 3) / 78);
 data[i] = Math.min(255, targetShirt.r * factor);
 data[i + 1] = Math.min(255, targetShirt.g * factor);
 data[i + 2] = Math.min(255, targetShirt.b * factor);
 }
 }
 ctx.putImageData(imgData, 0, 0);
 return canvas;
 };

 (function () {
  var bgmSrc = '/game_pacu/assets/sound/bgm.ogg';

  function isArenaBgmSuppressed() {
   return window._arenaBgmSuppressed === true;
  }

  function applyMuteState(audio) {
   if (isArenaBgmSuppressed()) {
    audio.volume = 0;
    audio.muted = true;
    if (!audio.paused) {
     audio.pause();
    }
    return;
   }
   var isMuted = localStorage.getItem('bgm_muted') === 'true';
   if (isMuted) {
    audio.volume = 0;
    audio.muted = true;
   } else {
    audio.volume = 0.5;
    audio.muted = false;
   }
  }

  function tryPlay(audio) {
   applyMuteState(audio);
   if (isArenaBgmSuppressed() || audio.muted) {
    return;
   }
   audio.play().catch(function (err) {
    console.log('[BGM] Autoplay blocked:', err);
   });
  }

  function initBGM() {
   // Jika globalBGM masih ada & src-nya benar, tidak perlu buat baru
   if (window.globalBGM && window.globalBGM.src && window.globalBGM.src.includes('bgm')) {
    applyMuteState(window.globalBGM);
    // Jika sedang pause & tidak muted, lanjutkan (jangan restart)
    if (window.globalBGM.paused && !window.globalBGM.muted && !isArenaBgmSuppressed()) {
     window.globalBGM.play().catch(function (err) {
      console.log('[BGM] Resume failed:', err);
     });
    }
    return;
   }

   // Buat instance baru hanya jika belum ada
   var bgm = new Audio(bgmSrc);
   bgm.loop = true;
   bgm.preload = 'auto';
   window.globalBGM = bgm;

   // Tandai bahwa BGM sudah diinisialisasi di sesi ini
   sessionStorage.setItem('bgm_initialized', '1');

   tryPlay(bgm);

   // Fallback: mulai setelah interaksi pertama (autoplay policy mobile)
   var playOnInteraction = function () {
    if (isArenaBgmSuppressed()) return;
    if (window.globalBGM && window.globalBGM.paused) {
     tryPlay(window.globalBGM);
    }
    document.removeEventListener('pointerdown', playOnInteraction);
    document.removeEventListener('touchstart', playOnInteraction);
    document.removeEventListener('keydown', playOnInteraction);
   };
   document.addEventListener('pointerdown', playOnInteraction, { once: true });
   document.addEventListener('touchstart', playOnInteraction, { once: true, passive: true });
   document.addEventListener('keydown', playOnInteraction, { once: true });
  }

  // Handle visibility change: pause saat app di background, resume saat kembali
  document.addEventListener('visibilitychange', function () {
   if (!window.globalBGM) return;
   if (document.hidden) {
    // App masuk background (Android home button, dll)
    window.globalBGM.pause();
   } else {
    // App kembali ke foreground, resume (bukan restart)
    var isMuted = localStorage.getItem('bgm_muted') === 'true';
    if (!isMuted && !isArenaBgmSuppressed() && window.globalBGM.paused) {
     window.globalBGM.play().catch(function (err) {
      console.log('[BGM] Visibility resume failed:', err);
     });
    }
   }
  });

  // Handle Livewire SPA navigation - pastikan BGM tidak diulang
  document.addEventListener('livewire:navigated', function () {
   if (window.globalBGM) {
    applyMuteState(window.globalBGM);
    // Jika pause karena navigasi, lanjutkan saja (jangan restart)
    var isMuted = localStorage.getItem('bgm_muted') === 'true';
    if (!isMuted && !isArenaBgmSuppressed() && window.globalBGM.paused) {
     window.globalBGM.play().catch(function (err) {
      console.log('[BGM] Post-navigate resume:', err);
     });
    }
   }
  });

  if (document.readyState === 'loading') {
   document.addEventListener('DOMContentLoaded', initBGM);
  } else {
   initBGM();
  }

  // =====================================================
  // GLOBAL BGM CONTROL FUNCTIONS (dipakai semua halaman)
  // =====================================================

  /**
   * Terapkan state mute ke globalBGM secara langsung dari localStorage.
   * Bisa dipanggil kapanpun untuk sync audio ke setting terbaru.
   */
  window.applyBGMMute = function () {
   if (!window.globalBGM) return;
   if (isArenaBgmSuppressed()) {
    window.globalBGM.volume = 0;
    window.globalBGM.muted = true;
    if (!window.globalBGM.paused) {
     window.globalBGM.pause();
    }
    return;
   }
   var isMuted = localStorage.getItem('bgm_muted') === 'true';
   if (isMuted) {
    window.globalBGM.volume = 0;
    window.globalBGM.muted = true;
    if (!window.globalBGM.paused) {
     window.globalBGM.pause();
    }
   } else {
    window.globalBGM.volume = 0.5;
    window.globalBGM.muted = false;
    if (window.globalBGM.paused) {
     window.globalBGM.play().catch(function (err) {
      console.log('[BGM] applyBGMMute play failed:', err);
     });
    }
   }
  };

  /**
   * Toggle BGM on/off — simpan ke localStorage & langsung terapkan ke audio.
   * Menggantikan implementasi per-halaman yang tidak langsung apply.
   */
  window.toggleBGMSetting = function () {
   var bgmMuted = localStorage.getItem('bgm_muted') === 'true';
   localStorage.setItem('bgm_muted', bgmMuted ? 'false' : 'true');
   // Langsung terapkan ke globalBGM tanpa menunggu event apapun
   window.applyBGMMute();
   // Sync tombol UI jika tersedia
   if (typeof window.syncAudioModalButtons === 'function') {
    window.syncAudioModalButtons();
   }
  };

  /**
   * Toggle SFX on/off — simpan ke localStorage.
   * Menggantikan implementasi per-halaman.
   */
  window.toggleSFXSetting = function () {
   var sfxMuted = localStorage.getItem('sfx_muted') === 'true';
   localStorage.setItem('sfx_muted', sfxMuted ? 'false' : 'true');
   // Sync tombol UI jika tersedia
   if (typeof window.syncAudioModalButtons === 'function') {
    window.syncAudioModalButtons();
   }
  };

  /**
   * Fallback updateSoundIcon — akan di-override oleh halaman yang perlu.
   */
  if (!window.updateSoundIcon) {
   window.updateSoundIcon = function () {};
  }

  /**
   * Fullscreen Helper - mendukung HTML5 Document, Mobile Frame, dan Phaser Scale Manager
   */
  window.requestGameFullscreen = function () {
   var isFS = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement;
   if (isFS) return;

   var elem = document.documentElement || document.body || document.getElementById('mobile-frame');
   var req = elem.requestFullscreen || elem.webkitRequestFullscreen || elem.mozRequestFullScreen || elem.msRequestFullscreen;

   if (req) {
    try {
     var p = req.call(elem);
     if (p && typeof p.catch === 'function') {
      p.catch(function (err) {
       tryPhaserFullscreen();
      });
     }
    } catch (e) {
     tryPhaserFullscreen();
    }
   } else {
    tryPhaserFullscreen();
   }
  };

  function tryPhaserFullscreen() {
   if (window.activeMultiplayerArenaGame && window.activeMultiplayerArenaGame.scale) {
    try {
     window.activeMultiplayerArenaGame.scale.startFullscreen();
    } catch (e) {}
   }
  }

  window.toggleFullscreenManual = function () {
   var isFS = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement;
   if (isFS) {
    var exit = document.exitFullscreen || document.webkitExitFullscreen || document.mozCancelFullScreen || document.msExitFullscreen;
    if (exit) {
     exit.call(document).catch(function () {});
    }
    if (window.activeMultiplayerArenaGame && window.activeMultiplayerArenaGame.scale) {
     try { window.activeMultiplayerArenaGame.scale.stopFullscreen(); } catch (e) {}
    }
   } else {
    window.requestGameFullscreen();
   }
  };

  function updateFullscreenIcon() {
   var btnIcon = document.getElementById('fullscreen-icon');
   if (!btnIcon) return;
   var isFS = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement;
   if (isFS) {
    btnIcon.className = 'bi bi-fullscreen-exit';
   } else {
    btnIcon.className = 'bi bi-fullscreen';
   }
  }

  document.addEventListener('fullscreenchange', updateFullscreenIcon);
  document.addEventListener('webkitfullscreenchange', updateFullscreenIcon);
  document.addEventListener('mozfullscreenchange', updateFullscreenIcon);

  var _autoFsTriggered = false;
  function triggerAutoFullscreenOnGesture() {
   if (window.autoFullscreenEnabled === false || _autoFsTriggered) return;
   var isFS = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement;
   if (!isFS) {
    window.requestGameFullscreen();
    setTimeout(function () {
     var checkFS = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement;
     if (checkFS) {
      _autoFsTriggered = true;
     }
    }, 200);
   } else {
    _autoFsTriggered = true;
   }
  }

  ['click', 'touchstart', 'pointerdown', 'keydown'].forEach(function (evtType) {
   window.addEventListener(evtType, triggerAutoFullscreenOnGesture, { capture: true, passive: true });
   document.addEventListener(evtType, triggerAutoFullscreenOnGesture, { capture: true, passive: true });
  });

 })();
})();