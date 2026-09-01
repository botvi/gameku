@extends('layouts.game')

@section('title', 'Nuxel Games — Loading')

@push('styles')
<style>
    #page-transition-overlay {
        display: none !important;
    }

    /* ---- Main Splash Container ---- */
    .splash-container {
        position: absolute;
        top: 0; left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        padding: 50px 24px 32px;
        overflow: hidden;
        box-sizing: border-box;
        background: #0a0f1a;
        font-family: 'Press Start 2P', monospace;
        z-index: 10;
    }

    /* ---- Content layers ---- */
    .splash-top {
        position: relative;
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
    }

    .studio-intro {
        font-size: 7px;
        color: #38bdf8;
        letter-spacing: 3px;
        opacity: 0.9;
    }

    /* ---- Logo area ---- */
    .splash-middle {
        position: relative;
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        justify-content: center;
        gap: 0;
    }

    .logo-wrapper {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .pixel-logo {
        max-width: 260px;
        height: auto;
        image-rendering: pixelated;
        filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.5));
    }

    /* ---- Loading section ---- */
    .splash-bottom {
        position: relative;
        z-index: 10;
        width: 100%;
        max-width: 300px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0;
    }

    .loading-label {
        font-size: 7px;
        color: rgba(255,255,255,0.7);
        letter-spacing: 2px;
        margin-bottom: 10px;
    }

    .percent-val {
        color: #22c55e;
    }

    /* ---- Progress Bar ---- */
    .progress-track {
        width: 100%;
        height: 8px;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 99px;
        overflow: hidden;
        margin-bottom: 12px;
    }

    .progress-fill {
        width: 0%;
        height: 100%;
        background: #22c55e;
        border-radius: 99px;
        transition: width 0.05s linear;
    }

    .status-text {
        font-size: 7px;
        color: #38bdf8;
        letter-spacing: 1.5px;
        margin-bottom: 20px;
        min-height: 12px;
    }

    /* ---- Footer ---- */
    .splash-footer {
        font-size: 6px;
        color: rgba(255,255,255,0.3);
        letter-spacing: 1px;
        text-align: center;
        font-family: 'Pixelify Sans', monospace;
    }
</style>
@endpush

@section('content')
<div class="splash-container">
    <!-- Top: Studio name -->
    <div class="splash-top">
        <div class="studio-intro">NUXEL STUDIO PRESENTS</div>
    </div>

    <!-- Middle: Logo -->
    <div class="splash-middle">
        <div class="logo-wrapper">
            <img src="{{ asset('env/logo_text1.png') }}" alt="Pacu Jalur Logo" class="pixel-logo">
        </div>
    </div>

    <!-- Bottom: Loading bar -->
    <div class="splash-bottom">
        <div class="loading-label">MEMUAT GAME... <span class="percent-val" id="percent-val">0%</span></div>
        <div class="progress-track">
            <div class="progress-fill" id="progress-fill"></div>
        </div>
        <div class="status-text" id="status-text">INITIALIZING SYSTEM...</div>
        <div class="splash-footer">© 2026 Nuxel Studio. All Rights Reserved.</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ---- Progress Bar Animation ----
    (function () {
        var fill = document.getElementById('progress-fill');
        var percentEl = document.getElementById('percent-val');
        var statusEl = document.getElementById('status-text');

        if (!fill || !percentEl || !statusEl) return;

        var statusMessages = [
            "CONNECTING...",
            "LOADING ASSETS...",
            "PREPARING ARENA...",
            "READY!"
        ];

        var start = null;
        var duration = 1400; // Dipersingkat dari 4.6s menjadi 1.4s agar super cepat!
        var redirected = false;

        function animate(timestamp) {
            if (!document.getElementById('progress-fill')) return;

            if (!start) start = timestamp;
            var elapsed = timestamp - start;
            var progress = Math.min(elapsed / duration, 1);

            var percentage = Math.floor(progress * 100);
            percentEl.textContent = percentage + '%';
            fill.style.width = percentage + '%';

            var msgIndex = Math.min(Math.floor(progress * (statusMessages.length - 1)), statusMessages.length - 1);
            statusEl.textContent = statusMessages[msgIndex];

            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        }

        requestAnimationFrame(animate);

        // Navigate cepat setelah 1.6 detik (sebelumnya 5 detik)
        setTimeout(function () {
            if (redirected) return;
            redirected = true;
            if (typeof Livewire !== 'undefined' && typeof Livewire.navigate === 'function') {
                Livewire.navigate("{{ route('login') }}");
            } else if (typeof window.navigateToPage === 'function') {
                window.navigateToPage("{{ route('login') }}");
            } else {
                window.location.href = "{{ route('login') }}";
            }
        }, 1600);
    })();
</script>
@endpush
