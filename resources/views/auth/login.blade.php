@extends('layouts.game')

@section('title', 'Pacu Jalur: The Pixel ” Masuk Akun')

@push('styles')
<link rel="manifest" href="/manifest.json">
<link rel="apple-touch-icon" href="/game_pacu/assets/image/ui/pwa-icon-192.png">
<style>
    #game-ui {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: url('{{ asset('game_pacu/assets/image/bg/bgmenu.jpg') }}') no-repeat center center;
        background-size: cover;
        z-index: 10;
        overflow: hidden;
    }
    /* PS5 Backdrop Glow */
    .ps5-backdrop {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: #0f172a;
        z-index: 1;
        pointer-events: none;
    }
    /* Floating particles canvas */
    #ps5-particles {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        z-index: 2;
        pointer-events: none;
        opacity: 0.5;
    }
    /* Main content wrapper */
    .login-content {
        position: relative;
        z-index: 11;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        max-width: 320px;
        padding: 0 20px;
        box-sizing: border-box;
    }
    /* Game Title */
    .game-title {
        font-family: 'Press Start 2P', monospace;
        font-size: 13px;
        background: linear-gradient(180deg, #ffffff 0%, #86efac 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-align: center;
        line-height: 1.5;
        letter-spacing: 2px;
        margin-bottom: 6px;
    }
    .game-subtitle {
        font-family: 'Press Start 2P', monospace;
        font-size: 13px;
        color: rgba(255,255,255,0.6);
        text-align: center;
        margin-bottom: 36px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.4);
    }
    /* Login Card */
    .login-card {
        width: 100%;
        background: rgba(15, 23, 42, 0.92);
        border: 1.5px solid rgba(255,255,255,0.15);
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.4);
        padding: 28px 24px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0;
    }
    .card-header {
        font-family: 'Press Start 2P', monospace;
        font-size: 9px;
        color: #22c55e;
        letter-spacing: 1px;
        margin-bottom: 20px;
        text-align: center;
    }
    /* Google Sign In Button */
    .google-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 14px 20px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        transition: transform 0.15s ease;
        position: relative;
        overflow: hidden;
        text-decoration: none;
        box-sizing: border-box;
    }
    .google-btn:hover {
        transform: translateY(-2px);
    }
    .google-btn:active {
        transform: translateY(1px);
    }
    .google-logo {
        width: 22px;
        height: 22px;
        object-fit: contain;
        flex-shrink: 0;
    }
    .google-btn-text {
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        color: #374151;
        white-space: nowrap;
    }
    /* Divider */
    .card-divider {
        width: 100%;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
        margin: 20px 0 16px;
    }
    /* Footer info */
    .card-footer {
        font-family: 'Press Start 2P', monospace;
        font-size: 11px;
        color: rgba(255,255,255,0.4);
        text-align: center;
        line-height: 1.5;
    }
    /* Loading overlay */
    .connecting-overlay {
        display: none;
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.92);
        z-index: 100;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 16px;
    }
    .connecting-overlay.show {
        display: flex;
    }
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid rgba(34, 197, 94, 0.2);
        border-top-color: #22c55e;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .connecting-text {
        font-family: 'Press Start 2P', monospace;
        font-size: 9px;
        color: #22c55e;
    }

    /* PWA Install Alert Banner */
    .pwa-install-alert {
        position: absolute;
        top: -250px;
        left: 5%;
        width: 90%;
        background: rgba(15, 23, 42, 0.95);
        border: 2px solid #22c55e;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
        padding: 18px 16px;
        box-sizing: border-box;
        z-index: 1050;
        transition: top 0.3s ease;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .pwa-install-alert.show { top: 20px; }
    .pwa-alert-header {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .pwa-alert-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        border: 2px solid #22c55e;
        object-fit: cover;
    }
    .pwa-alert-title-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
        flex: 1;
    }
    .pwa-alert-title {
        font-family: 'Press Start 2P', monospace;
        font-size: 9px;
        color: #22c55e;
        margin: 0;
    }
    .pwa-alert-desc {
        font-family: 'Press Start 2P', monospace;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.8);
        margin: 0;
        line-height: 1.4;
    }
    .pwa-alert-buttons {
        display: flex;
        gap: 12px;
        width: 100%;
    }
    .pwa-btn {
        flex: 1;
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        padding: 12px 0;
        border: 2px solid #000000;
        border-radius: 8px;
        cursor: pointer;
        text-align: center;
        transition: all 0.1s;
    }
    .pwa-btn-install {
        background-color: #22c55e;
        color: white;
    }
    .pwa-btn-cancel {
        background-color: #475569;
        color: #cbd5e1;
    }
    .pwa-ios-instructions {
        font-family: 'Press Start 2P', monospace;
        font-size: 11px;
        color: rgba(255, 255, 255, 0.9);
        background: rgba(34, 197, 94, 0.1);
        border: 1px dashed rgba(34, 197, 94, 0.4);
        border-radius: 8px;
        padding: 10px;
        margin-top: 2px;
        display: flex;
        align-items: center;
        gap: 8px;
        line-height: 1.4;
    }
    .pwa-ios-icon {
        font-size: 18px;
        display: inline-block;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<!-- PS5 Styled Login UI -->
<div id="game-ui">
    <div class="login-content">
        <!-- Game Title -->


        <!-- Login Card -->
        <div class="login-card">
            <div class="card-header">MASUK GAME</div>

            <!-- Google Sign In Button -->
            <a id="google-login-btn" href="#" class="google-btn" onclick="handleGoogleLogin(event)">
                <img src="{{ asset('game_pacu/assets/image/ui/google.png') }}" alt="Google" class="google-logo">
                <span class="google-btn-text">MASUK DENGAN GOOGLE</span>
            </a>

            <div class="card-divider"></div>
            <div class="card-footer">Hubungkan akun Google<br>untuk mulai bermain</div>
        </div>
    </div>

    <!-- Connecting overlay -->
    <div class="connecting-overlay" id="connecting-overlay">
        <div class="spinner"></div>
        <div class="connecting-text">MENGHUBUNGKAN...</div>
    </div>

    <!-- PWA Install Alert Dialog -->
    <div id="pwa-install-alert" class="pwa-install-alert">
        <div class="pwa-alert-header">
            <img src="/game_pacu/assets/image/ui/pwa-icon-192.png" alt="Icon Game" class="pwa-alert-icon">
            <div class="pwa-alert-title-group">
                <h4 class="pwa-alert-title"><i class="bi bi-download me-1"></i> PASANG GAME</h4>
                <p class="pwa-alert-desc">Pasang game di Home Screen kamu untuk bermain lebih lancar, cepat, dan layar penuh!</p>
            </div>
        </div>
        <!-- iOS specific message (hidden by default) -->
        <div id="pwa-ios-guide" class="pwa-ios-instructions" style="display: none;">
            <i class="bi bi-box-arrow-up pwa-ios-icon me-1"></i>
            <span>Ketuk tombol <strong>Bagikan (Share)</strong> di Safari lalu pilih <strong>'Tambahkan ke Layar Utama (Add to Home Screen)'</strong>.</span>
        </div>
        <div class="pwa-alert-buttons">
            <button id="pwa-btn-cancel" class="pwa-btn pwa-btn-cancel">BATAL</button>
            <button id="pwa-btn-install" class="pwa-btn pwa-btn-install">PASANG</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ---- Google Login Handler ----
    function handleGoogleLogin(e) {
        e.preventDefault();

        // Show connecting overlay
        var overlay = document.getElementById('connecting-overlay');
        if (overlay) overlay.classList.add('show');

        // Deteksi apakah dijalankan di Mobile, PWA, atau Flutter WebView
        var ua = navigator.userAgent || navigator.vendor || window.opera;
        var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(ua);
        var isWebView = /wv|WebView|Flutter|MobileApp/i.test(ua) || (window.Flutter !== undefined) || (window.flutter_inappwebview !== undefined);
        var isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;

        // Pada Mobile / WebView (Flutter) / PWA Standalone, langsung redirect di window yang sama
        // Agar tidak membuka popup / browser eksternal yang menyebabkan keluar dari aplikasi Flutter!
        if (isMobile || isWebView || isStandalone) {
            window.location.href = '{{ route('google.login') }}';
            return;
        }

        var width = 500, height = 650;
        var left = (window.screen.width / 2) - (width / 2);
        var top = (window.screen.height / 2) - (height / 2);
        var popup = null;

        try {
            popup = window.open('about:blank', 'GoogleLoginPopup', 'width=' + width + ',height=' + height + ',left=' + left + ',top=' + top + ',status=no,resizable=yes,scrollbars=yes');
        } catch (err) {}

        if (!popup || popup.closed || typeof popup.closed === 'undefined') {
            // Popup diblokir oleh browser / PWA -> fallback langsung redirect ke halaman Google login!
            window.location.href = '{{ route('google.login') }}';
            return;
        }

        // Redirect popup after slight delay
        setTimeout(function () {
            if (popup) popup.location.href = '{{ route('google.login') }}';
        }, 300);

        // Periodically check if popup closed
        var checkTimer = setInterval(function () {
            if (popup && popup.closed) {
                clearInterval(checkTimer);
                if (!window._googleLoginRedirecting) {
                    if (overlay) overlay.classList.remove('show');
                }
            }
        }, 500);

        window._googleLoginCheckTimer = checkTimer;
    }

    // ---- Popup message listener ----
    window.addEventListener('message', function (event) {
        if (event.origin !== window.location.origin) return;
        if (event.data && event.data.type === 'google-login-response') {
            if (window._googleLoginCheckTimer) {
                clearInterval(window._googleLoginCheckTimer);
                window._googleLoginCheckTimer = null;
            }

            if (event.data.status === 'success') {
                window._googleLoginRedirecting = true;
                var overlay = document.getElementById('connecting-overlay');
                if (overlay) overlay.classList.add('show');
                // Gunakan Livewire.navigate agar SPA (tidak reload penuh)
                if (typeof Livewire !== 'undefined' && typeof Livewire.navigate === 'function') {
                    Livewire.navigate(event.data.redirect);
                } else {
                    window.location.href = event.data.redirect;
                }
            } else {
                window._googleLoginRedirecting = false;
                window.location.reload();
            }
        }
    });

    // ---- PWA Service Worker & Install Prompt Logic ----
    (function () {
        var deferredPrompt;
        var pwaAlert = document.getElementById('pwa-install-alert');
        var btnInstall = document.getElementById('pwa-btn-install');
        var btnCancel = document.getElementById('pwa-btn-cancel');
        var iosGuide = document.getElementById('pwa-ios-guide');

        if (!pwaAlert || !btnInstall || !btnCancel) return;

        // Register Service Worker (hanya sekali per sesi)
        if ('serviceWorker' in navigator && !window._swRegistered) {
            window._swRegistered = true;
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/sw.js?v={{ config("app.version") }}')
                    .then(function (reg) { console.log('[PWA] SW registered:', reg.scope); })
                    .catch(function (err) { console.error('[PWA] SW failed:', err); });
            });
        }

        function isInstalled() {
            return window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;
        }

        function isIOS() {
            return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        }

        function showPwaNotification() {
            var dismissedTime = localStorage.getItem('pwa-prompt-dismissed');
            var now = Date.now();
            if (dismissedTime && (now - parseInt(dismissedTime)) < (24 * 60 * 60 * 1000)) return;
            if (isInstalled()) return;
            setTimeout(function () { pwaAlert.classList.add('show'); }, 1500);
        }

        window.addEventListener('beforeinstallprompt', function (e) {
            e.preventDefault();
            deferredPrompt = e;
            showPwaNotification();
        });

        btnInstall.addEventListener('click', async function () {
            pwaAlert.classList.remove('show');
            if (deferredPrompt) {
                deferredPrompt.prompt();
                var result = await deferredPrompt.userChoice;
                console.log('[PWA] User choice:', result.outcome);
                deferredPrompt = null;
            } else if (isIOS()) {
                pwaAlert.classList.add('show');
                if (iosGuide) iosGuide.style.display = 'flex';
                btnInstall.style.display = 'none';
                btnCancel.textContent = 'OKE';
            }
        });

        btnCancel.addEventListener('click', function () {
            pwaAlert.classList.remove('show');
            localStorage.setItem('pwa-prompt-dismissed', Date.now().toString());
        });

        if (isIOS() && !isInstalled()) {
            showPwaNotification();
        }
    })();
</script>
@endpush
