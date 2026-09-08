<!DOCTYPE html>
<html lang="id">

<head>
    {{-- MONETAG --}}
    <meta name="monetag" content="5d86190daeee506c68bbc447ed901024">
    <script>
        (function(s) {
            s.dataset.zone = '11752959', s.src = 'https://nap5k.com/tag.min.js'
        })([document.documentElement, document.body].filter(Boolean).pop().appendChild(document.createElement('script')))
    </script>

    {{-- MONETAG --}}
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
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Ubuntu:wght@400;500;700&display=swap"
        rel="stylesheet">
    <style>
        /* Global Press Start 2P — jangan pakai #game-container * agar Ubuntu bisa menang */
        html,
        body {
            font-family: 'Press Start 2P', monospace !important;
            font-size: 78%;
        }

        button,
        input,
        select,
        textarea,
        label,
        a,
        p,
        span,
        div,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: inherit;
        }

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
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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
                    <button id="fullscreen-toggle-btn" onclick="toggleFullscreenManual()"
                        style="background: none; border: none; color: #ffffff; cursor: pointer; padding: 0 4px; font-size: 11px; line-height: 1; vertical-align: middle;"
                        title="Layar Penuh">
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
            if (path.indexOf('?v=') !== -1 || path.indexOf('&v=') !== -1 || path.startsWith('data:') || path.startsWith(
                    'blob:') || path.startsWith('http://') || path.startsWith('https://')) {
                return path;
            }
            const v = window.GAME_VERSION || '{{ config('app.version', '2.0.2') }}';
            return path + (path.includes('?') ? '&v=' : '?v=') + v;
        };
    </script>
    @livewireScripts
    <script src="{{ asset_v('game_pacu/assets/js/game-layout.js') }}"></script>
    @stack('scripts')
    {{-- Ubuntu override di akhir body agar mengalahkan style per-halaman --}}
    <style id="font-ubuntu-overrides">
        #game-container #chat-sidebar,
        #game-container #chat-sidebar *:not(.bi):not(i),
        #game-container #chat-toggle-btn,
        #game-container #chat-toggle-btn *:not(.bi):not(i),
        #game-container #chat-input,
        #game-container #chat-send-btn,
        #game-container #chat-messages,
        #game-container #chat-messages *:not(.bi):not(i),
        #game-container .chat-header,
        #game-container .chat-header *:not(.bi):not(i),
        #game-container .chat-msg,
        #game-container .chat-msg *:not(.bi):not(i),
        #game-container .chat-input-area,
        #game-container .chat-input-area *:not(.bi):not(i),
        #game-container .chat-system-msg,
        #game-container .chat-share-btn,
        #game-container .chat-online-badge,
        #game-container .chat-online-badge *:not(.bi):not(i),
        #game-container .chat-msg-bubble,
        #game-container .chat-msg-name,
        #game-container .chat-msg-time,
        #game-container .chat-header-title,
        #game-container #inbox-sidebar,
        #game-container #inbox-sidebar *:not(.bi):not(i),
        #game-container #inbox-toggle-btn,
        #game-container #inbox-toggle-btn *:not(.bi):not(i),
        #game-container #inbox-messages-list,
        #game-container #inbox-messages-list *:not(.bi):not(i),
        #game-container .inbox-header,
        #game-container .inbox-header *:not(.bi):not(i),
        #game-container .inbox-card,
        #game-container .inbox-card *:not(.bi):not(i),
        #game-container .inbox-card-title,
        #game-container .inbox-card-content,
        #game-container .inbox-reward-box,
        #game-container .inbox-reward-box *:not(.bi):not(i),
        #game-container .inbox-claim-btn,
        #game-container .inbox-header-title,
        #game-container .room-code-box,
        #game-container .room-code-box *:not(.bi):not(i),
        #game-container .room-code-value,
        #game-container .room-code-label,
        #game-container .room-code-copy,
        #game-container .history-room-code,
        #game-container #room-code-display,
        #game-container #room-code-input,
        #game-container #join-code-input,
        #game-container #join-by-code-input {
            font-family: 'Ubuntu', sans-serif !important;
            letter-spacing: normal !important;
        }
    </style>
</body>

</html>
