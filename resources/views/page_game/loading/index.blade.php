@extends('layouts.game')

@section('title', 'Pacu Jalur: The Pixel ” Sinkronisasi Data')

@push('styles')
<style>
    #page-transition-overlay {
        display: none !important;
    }

    .sync-container {
        position: absolute;
        top: 0; left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        padding: 48px 24px 32px;
        overflow: hidden;
        box-sizing: border-box;
        background: radial-gradient(circle at center, #111827 0%, #060913 100%);
        font-family: 'Press Start 2P', monospace;
        z-index: 20;
    }

    .sync-top {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        z-index: 2;
    }

    .sync-badge {
        font-size: 7px;
        color: #38bdf8;
        letter-spacing: 2px;
        background: rgba(56, 189, 248, 0.1);
        border: 1px solid rgba(56, 189, 248, 0.3);
        padding: 4px 10px;
        border-radius: 12px;
    }

    .sync-middle {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex: 1;
        gap: 16px;
        z-index: 2;
    }

    .pixel-logo {
        max-width: 220px;
        height: auto;
        image-rendering: pixelated;
        filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.6));
        animation: logoPulse 2s ease-in-out infinite alternate;
    }

    @keyframes logoPulse {
        0% { transform: scale(1); }
        100% { transform: scale(1.03); }
    }

    .player-avatar-preview {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        border: 2px solid #38bdf8;
        object-fit: cover;
        box-shadow: 0 0 12px rgba(56, 189, 248, 0.4);
    }

    .player-welcome-name {
        font-size: 9px;
        color: #facc15;
        letter-spacing: 1px;
        text-align: center;
        text-shadow: 0 2px 4px rgba(0,0,0,0.8);
    }

    .sync-bottom {
        width: 100%;
        max-width: 320px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        z-index: 2;
    }

    .loading-label {
        font-size: 7px;
        color: rgba(255, 255, 255, 0.8);
        letter-spacing: 1.5px;
        display: flex;
        justify-content: space-between;
        width: 100%;
    }

    .percent-val {
        color: #22c55e;
        font-weight: bold;
    }

    .progress-track {
        width: 100%;
        height: 10px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 99px;
        overflow: hidden;
        position: relative;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.5);
    }

    .progress-fill {
        width: 0%;
        height: 100%;
        background: linear-gradient(90deg, #10b981 0%, #22c55e 100%);
        border-radius: 99px;
        transition: width 0.15s ease-out;
        box-shadow: 0 0 8px rgba(34, 197, 94, 0.6);
    }

    .status-text {
        font-size: 7px;
        color: #38bdf8;
        letter-spacing: 1px;
        min-height: 14px;
        text-align: center;
    }

    .sub-detail {
        font-size: 6px;
        color: rgba(255, 255, 255, 0.4);
        font-family: 'Press Start 2P', monospace;
        letter-spacing: 0.5px;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="sync-container">
    <div class="sync-top">
        <div class="sync-badge">SYSTEM SYNC & CACHE REFRESH</div>
    </div>

    <div class="sync-middle">
        <img src="{{ asset_v('env/nyx_nobg.png') }}" alt="Logo Nyx Studio" class="pixel-logo">
        <img id="player-avatar" src="{{ asset_v(auth()->user()->foto_profile ?? 'profiles/default.gif') }}" class="player-avatar-preview" alt="Avatar">
        <div id="player-name" class="player-welcome-name">HALO, {{ strtoupper(auth()->user()->nama_jalur ?? auth()->user()->email) }}!</div>
    </div>

    <div class="sync-bottom">
        <div class="loading-label">
            <span>MEMUAT DATA...</span>
            <span class="percent-val" id="percent-val">0%</span>
        </div>
        <div class="progress-track">
            <div class="progress-fill" id="progress-fill"></div>
        </div>
        <div class="status-text" id="status-text">INITIALIZING SYSTEM...</div>
        <div class="sub-detail" id="sub-detail">Mengecek pembaruan aplikasi & aset lokal</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    // AUTO HARD RELOAD ” Sekali per sesi saat player masuk loading
    // Membersihkan Service Worker & semua cache lama secara paksa,
    // lalu reload halaman dengan asset fresh dari server.
    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    var HARD_RELOAD_KEY = 'hard_reloaded_v{{ config("app.version", "1.0.0") }}';
    if (!sessionStorage.getItem(HARD_RELOAD_KEY)) {
        sessionStorage.setItem(HARD_RELOAD_KEY, '1');
        (async function () {
            // 1. Unregister semua Service Worker
            if ('serviceWorker' in navigator) {
                try {
                    var regs = await navigator.serviceWorker.getRegistrations();
                    for (var r of regs) { await r.unregister(); }
                } catch (e) {}
            }
            // 2. Hapus semua cache browser
            if ('caches' in window) {
                try {
                    var keys = await caches.keys();
                    for (var k of keys) { await caches.delete(k); }
                } catch (e) {}
            }
            // 3. Hard reload ” paksa browser ambil semua asset fresh dari server
            window.location.reload(true);
        })();
        return; // Hentikan eksekusi sisa skrip ini, reload akan menangani sisanya
    }
    // â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

    var fill = document.getElementById('progress-fill');
    var percentEl = document.getElementById('percent-val');
    var statusEl = document.getElementById('status-text');
    var detailEl = document.getElementById('sub-detail');
    var playerAvatarEl = document.getElementById('player-avatar');
    var playerNameEl = document.getElementById('player-name');

    function updateProgress(percent, statusText, subText) {
        var p = Math.min(Math.max(percent, 0), 100);
        if (fill) fill.style.width = p + '%';
        if (percentEl) percentEl.textContent = Math.floor(p) + '%';
        if (statusEl && statusText) statusEl.textContent = statusText;
        if (detailEl && subText) detailEl.textContent = subText;
    }

    async function clearStaleCaches(newVersion) {
        var currentVersion = localStorage.getItem('app_version');
        var forceRefresh = (currentVersion !== newVersion);

        if (forceRefresh) {
            console.log('[Sync] App version update detected (' + currentVersion + ' -> ' + newVersion + '). Invalidating caches...');
        }

        if ('serviceWorker' in navigator) {
            try {
                var registrations = await navigator.serviceWorker.getRegistrations();
                for (var reg of registrations) {
                    await reg.update();
                    if (reg.waiting) {
                        reg.waiting.postMessage({ type: 'SKIP_WAITING' });
                    }
                }
            } catch (e) {
                console.warn('[Sync] ServiceWorker update warning:', e);
            }
        }

        if (forceRefresh && 'caches' in window) {
            try {
                var cacheKeys = await caches.keys();
                for (var key of cacheKeys) {
                    if (!key.includes(newVersion)) {
                        console.log('[Sync] Menghapus cache lama:', key);
                        await caches.delete(key);
                    }
                }
            } catch (e) {
                console.warn('[Sync] Cache purge error:', e);
            }
        }
    }

    async function syncPlayerData() {
        updateProgress(10, "INITIALIZING...", "Menghubungkan ke server...");

        try {
            // 1. Fetch latest player data from server
            updateProgress(25, "MEMPERBARUI CACHE...", "Mengecek versi aplikasi & ServiceWorker...");
            var res = await fetch("{{ route('api.player.sync-data') }}?t=" + Date.now(), {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (!res.ok) {
                throw new Error("HTTP error " + res.status);
            }

            var data = await res.json();

            // 2. Clear old browser caches if app version updated
            await clearStaleCaches(data.app_version);

            updateProgress(50, "SINKRONISASI LOCALSTORAGE...", "Menyimpan kustomisasi player & item...");

            // 3. Write all user & customization data directly to localStorage
            if (data.user) {
                localStorage.setItem('coins', String(data.user.coins ?? 0));
                localStorage.setItem('nama_jalur', data.user.nama_jalur ?? '');
                localStorage.setItem('foto_profile', data.user.foto_profile ?? '');
                if (playerNameEl && data.user.nama_jalur) {
                    playerNameEl.textContent = 'HALO, ' + data.user.nama_jalur.toUpperCase() + '!';
                }
                if (playerAvatarEl && data.user.foto_profile) {
                    var avatarUrl = data.user.foto_profile.startsWith('http') ? data.user.foto_profile : '/' + data.user.foto_profile.replace(/^\//, '');
                    playerAvatarEl.src = avatarUrl;
                }
            }

            if (data.customization) {
                var colors = data.customization.customColors || {};
                if (colors.boat) localStorage.setItem('custom_boat', colors.boat);
                if (colors.hair) localStorage.setItem('custom_hair', colors.hair);
                if (colors.shirt) localStorage.setItem('custom_shirt', colors.shirt);
                if (colors.pants) localStorage.setItem('custom_pants', colors.pants);
                if (colors.paddle) localStorage.setItem('custom_paddle', colors.paddle);
                if (colors.splash) localStorage.setItem('custom_splash', colors.splash);

                if (data.customization.corak_data_url) {
                    localStorage.setItem('corak_data_url', data.customization.corak_data_url);
                } else {
                    localStorage.removeItem('corak_data_url');
                }

                if (data.customization.lambai_data_url) {
                    localStorage.setItem('lambai_data_url', data.customization.lambai_data_url);
                } else {
                    localStorage.removeItem('lambai_data_url');
                }

                localStorage.setItem('boat_unlocked', data.customization.boat_unlocked ? 'true' : 'false');
                localStorage.setItem('lambai_unlocked', data.customization.lambai_unlocked ? 'true' : 'false');

                var localVsAi = parseInt(localStorage.getItem('vsai_unlocked') || '1', 10);
                var serverVsAi = parseInt(data.customization.vsai_unlocked || 1, 10);
                var finalVsAi = Math.max(localVsAi, serverVsAi);
                localStorage.setItem('vsai_unlocked', String(finalVsAi));
            }

            // Store current app version
            localStorage.setItem('app_version', data.app_version);

            updateProgress(75, "MENDOWNLOAD ASSET GAME...", "Preloading gambar & tekstur...");

            // 4. Preload critical game assets in parallel
            if (data.critical_assets && data.critical_assets.length > 0) {
                var loaded = 0;
                var total = data.critical_assets.length;

                await Promise.all(data.critical_assets.map(function (url) {
                    return new Promise(function (resolve) {
                        var img = new Image();
                        img.onload = function () {
                            loaded++;
                            var p = 75 + Math.floor((loaded / total) * 20);
                            updateProgress(p, "MEMUAT ASSET (" + loaded + "/" + total + ")...", url.split('/').pop());
                            resolve();
                        };
                        img.onerror = function () {
                            loaded++;
                            resolve();
                        };
                        img.src = url;
                    });
                }));
            }

            updateProgress(100, "SINKRONISASI SELESAI!", "Menuju ke Main Menu...");

            // Mark session as synced to prevent unnecessary loop back to loading
            sessionStorage.setItem('synced_this_session', '1');

            setTimeout(function () {
                window.navigateToPage("{{ route('main-menu') }}");
            }, 400);

        } catch (err) {
            console.error('[Sync Error]', err);
            updateProgress(100, "TERJADI KESALAHAN", "Melanjutkan ke Main Menu...");
            sessionStorage.setItem('synced_this_session', '1');
            setTimeout(function () {
                window.navigateToPage("{{ route('main-menu') }}");
            }, 600);
        }
    }

    var _syncStarted = false;
    function startSyncOnce() {
        if (_syncStarted) return;
        _syncStarted = true;
        syncPlayerData();
    }

    // Livewire SPA navigation ” fire saat pindah halaman via Livewire
    document.addEventListener('livewire:navigated', startSyncOnce);

    // Hard reload / full page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startSyncOnce);
    } else {
        // DOM sudah siap (navigasi Livewire sudah render konten)
        startSyncOnce();
    }
})();
</script>
@endpush
