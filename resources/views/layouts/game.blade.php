<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pacu Jalur: The Pixel')</title>
    <link rel="manifest" href="{{ asset_v('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset_v('game_pacu/assets/image/ui/pwa-icon-192.png') }}">
    <link rel="stylesheet" href="{{ asset_v('game_pacu/assets/css/game-layout.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Pixelify+Sans:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .sprint-icon {
            color: #f8a73c;
            text-shadow: 0 0 4px rgba(248, 167, 60, 0.5);
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.4));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            vertical-align: middle;
        }
        .sprint-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(248, 167, 60, 0.12);
            border: 1px solid rgba(248, 167, 60, 0.35);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border-radius: 20px;
            padding: 3px 10px;
            color: #ffffff;
            font-weight: bold;
        }
        @keyframes spinIcon {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .spin-icon {
            display: inline-block;
            animation: spinIcon 1.2s linear infinite;
        }
    </style>
    {{-- Load Phaser.js lokal secara global agar selalu siap saat SPA navigation --}}
    <script src="{{ asset_v('game_pacu/assets/js/phaser.min.js') }}"></script>
    {{-- Preload asset arena yang sering dipakai --}}
    <link rel="preload" href="{{ asset_v('game_pacu/assets/image/bg/bgmenu.jpg') }}" as="image">
    <link rel="preload" href="{{ asset_v('game_pacu/assets/image/ui/back.png') }}" as="image">
    @livewireStyles
    @stack('styles')
</head>
<body>
    <div id="desktop-wrapper">
        <div id="mobile-frame">
            <div id="status-bar">
                <span id="clock">00:00</span>
                <span class="status-dots">
                    <button id="fullscreen-toggle-btn" onclick="toggleFullscreenManual()" style="background: none; border: none; color: #ffffff; cursor: pointer; padding: 0 4px; font-size: 11px; line-height: 1; vertical-align: middle;" title="Layar Penuh">
                        <i class="bi bi-fullscreen" id="fullscreen-icon"></i>
                    </button>
                    <i class="bi bi-wifi"></i> <i class="bi bi-battery-full ms-1"></i>
                </span>
            </div>

            <!-- Page Transition Overlay (smooth SPA transitions) -->
            <div id="page-transition-overlay">
                <div class="transition-content">
                    <div class="transition-title">MEMUAT...</div>
                    <div class="transition-bar-container">
                        <div class="transition-bar-fill"></div>
                    </div>
                </div>
            </div>

            <div id="game-container">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        window.GAME_VERSION = "{{ filemtime(public_path('game_pacu/assets/js/game-layout.js')) }}";
        window.autoFullscreenEnabled = {{ \App\Models\GameSetting::isFullscreenEnabled() ? 'true' : 'false' }};
        window.assetV = function(path) {
            if (!path || typeof path !== 'string') return path;
            if (path.indexOf('?v=') !== -1 || path.indexOf('&v=') !== -1 || path.startsWith('data:') || path.startsWith('blob:') || path.startsWith('http://') || path.startsWith('https://')) {
                return path;
            }
            const v = window.GAME_VERSION || '{{ config("app.version", "2.0.2") }}';
            return path + (path.includes('?') ? '&v=' : '?v=') + v;
        };
    </script>
    @livewireScripts
    <script src="{{ asset_v('game_pacu/assets/js/game-layout.js') }}"></script>
    @stack('scripts')
</body>
</html>
