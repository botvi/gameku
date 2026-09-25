@extends('layouts.game')

@section('title', 'Arena Turnamen - ' . ($tournamentMatch->tournament->title ?? 'Pacu Jalur'))

@push('styles')
<style>
    #game-container canvas { z-index: 1; }
    
    .sound-btn {
        position: absolute;
        top: 16px;
        left: 50%;
        transform: translateX(-50%);
        width: 36px;
        height: 36px;
        background: none;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 15;
        transition: all 0.15s ease;
    }
    .sound-btn img { width: 100%; height: 100%; object-fit: contain; image-rendering: pixelated; }

    /* ===== READY OVERLAY ===== */
    #ready-overlay {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(6, 13, 24, 0.92);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        z-index: 500;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 18px;
        font-family: 'Press Start 2P', monospace;
    }

    .ready-title {
        font-size: 11px;
        color: #22c55e;
        text-shadow: 0 0 20px rgba(34, 197, 94, 0.8);
        letter-spacing: 2px;
        text-align: center;
    }

    .vs-badge {
        display: flex;
        align-items: center;
        gap: 14px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 14px 22px;
    }

    .vs-player { text-align: center; min-width: 80px; }
    .vs-player-name {
        font-size: 7px;
        color: #ffffff;
        margin-bottom: 4px;
        max-width: 90px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .vs-player-name.is-me { color: #4ade80; }
    .vs-label { font-size: 18px; color: #f59e0b; text-shadow: 2px 2px 0 #78350f; }

    #ready-status-text {
        font-size: 7px;
        color: rgba(255, 255, 255, 0.7);
        text-align: center;
        line-height: 1.8;
    }

    #arena-ready-btn {
        background: linear-gradient(180deg, #4ade80 0%, #16a34a 100%);
        border: 4px solid #14532d;
        border-radius: 14px;
        box-shadow: 0 8px 0 #14532d, 0 12px 30px rgba(34, 197, 94, 0.5);
        color: #fff;
        font-family: 'Press Start 2P', monospace;
        font-size: 14px;
        padding: 16px 36px;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: all 0.1s;
    }
    #arena-ready-btn.pressed {
        background: linear-gradient(180deg, #86efac 0%, #4ade80 100%);
        cursor: default;
        opacity: 0.7;
        pointer-events: none;
    }

    .ready-waiting-dots::after {
        content: '';
        animation: dots 1.5s steps(3, end) infinite;
    }
    @keyframes dots {
        0% { content: ''; } 33% { content: '.'; } 66% { content: '..'; } 100% { content: '...'; }
    }

    #arena-loading-screen {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(160deg, #0a1628 0%, #0d2b1a 60%, #071a10 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        font-family: 'Press Start 2P', monospace;
        gap: 24px;
        transition: opacity 0.5s ease;
    }
    #arena-loading-screen.hidden { opacity: 0; pointer-events: none; }
    .arena-loading-title { font-size: 12px; color: #4ade80; text-align: center; }
    .arena-loading-bar-wrap {
        width: 240px; background: rgba(255,255,255,0.07);
        border: 2px solid rgba(74,222,128,0.3); border-radius: 999px; height: 16px; overflow: hidden;
    }
    #arena-loading-bar { height: 100%; width: 0%; background: linear-gradient(90deg, #22c55e, #4ade80); border-radius: 999px; }
    #arena-loading-pct { font-size: 10px; color: #4ade80; }
    #arena-loading-text { font-size: 7px; color: rgba(255,255,255,0.5); }
</style>
@endpush

@section('content')
<div id="arena-loading-screen">
    <div class="arena-loading-title">ARENA TURNAMEN</div>
    <div class="arena-loading-bar-wrap">
        <div id="arena-loading-bar"></div>
    </div>
    <div id="arena-loading-pct">0%</div>
    <div id="arena-loading-text">Memuat arena...</div>
</div>

<button id="sound-btn" class="sound-btn" onclick="openAudioSettings()">
    <img id="sound-icon" src="/game_pacu/assets/image/ui/sound_on.png" alt="Sound">
</button>

<!-- ===== READY OVERLAY ===== -->
<div id="ready-overlay" style="display: flex;">
    <div class="ready-title">LAGA TURNAMEN</div>

    <div class="vs-badge">
        <div class="vs-player">
            <div class="vs-player-name is-me">
                {{ strtoupper($tournamentMatch->player1 ? $tournamentMatch->player1->nama_jalur : 'PLAYER 1') }}
            </div>
        </div>
        <div class="vs-label">VS</div>
        <div class="vs-player">
            <div class="vs-player-name">
                {{ strtoupper($tournamentMatch->player2 ? $tournamentMatch->player2->nama_jalur : 'PLAYER 2') }}
            </div>
        </div>
    </div>

    <div id="ready-status-text">Tekan SIAP untuk memulai babak ini!</div>

    <div id="ready-btn-container">
        <button id="arena-ready-btn" onclick="pressArenaReady()">SIAP!</button>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset_v('game_pacu/assets/js/phaser.min.js') }}"></script>
<script>
{
    const GAME_WIDTH = 360;
    const GAME_HEIGHT = 760;

    class LoadingScene extends Phaser.Scene {
        constructor() { super({ key: 'LoadingScene' }); }
        preload() {
            const bar = document.getElementById('arena-loading-bar');
            const pct = document.getElementById('arena-loading-pct');
            const txt = document.getElementById('arena-loading-text');

            this.load.on('progress', (value) => {
                const p = Math.round(value * 100);
                if (bar) bar.style.width = p + '%';
                if (pct) pct.textContent = p + '%';
            });
            this.load.on('fileprogress', (file) => {
                if (txt) txt.textContent = 'Memuat: ' + file.key + '...';
            });
            this.load.on('complete', () => {
                if (bar) bar.style.width = '100%';
                if (pct) pct.textContent = '100%';
                if (txt) txt.textContent = 'Siap bertanding!';
            });

            const v = (typeof window !== 'undefined' && window.GAME_VERSION) ? `?v=${window.GAME_VERSION}` : '';
            this.load.image('bgmenu', `/game_pacu/assets/image/bg/bgmenu.jpg${v}`);
            this.load.image('back', `/game_pacu/assets/image/ui/back.png${v}`);
            this.load.image('koin', `/game_pacu/assets/image/ui/sprint.png${v}`);
            this.load.image('jalur_boat', `/game_pacu/assets/image/jalur/jalur.png${v}`);

            for (let i = 1; i <= 5; i++) {
                this.load.image(`char${i}`, `/game_pacu/assets/image/char/${i}.png${v}`);
                this.load.image(`timbo${i}`, `/game_pacu/assets/image/timbo_ruang/${i}.png${v}`);
                this.load.image(`tari${i}`, `/game_pacu/assets/image/tukang_tari/${i}.png${v}`);
                this.load.image(`onjai${i}`, `/game_pacu/assets/image/tukang_onjai/${i}.png${v}`);
            }
            for (let i = 1; i <= 6; i++) {
                this.load.image(`pancang${i}`, `/game_pacu/assets/image/pancang/${i}.png${v}`);
            }
            this.load.audio('sound_321', `/game_pacu/assets/sound/321.ogg${v}`);
            this.load.audio('sound_suporter', `/game_pacu/assets/sound/suporter.ogg${v}`);
            this.load.audio('sound_pluit', `/game_pacu/assets/sound/pluit.ogg${v}`);
        }
        create() {
            const screen = document.getElementById('arena-loading-screen');
            if (screen) screen.classList.add('hidden');
            setTimeout(() => {
                if (screen) screen.remove();
                this.scene.start('ArenaScene');
            }, 500);
        }
    }

    class ArenaScene extends Phaser.Scene {
        constructor() { super({ key: 'ArenaScene' }); }

        create() {
            const W = this.scale.width;
            const H = this.scale.height;
            const cx = W / 2;

            this.roomId = "{{ $tournamentMatch->room_id }}";
            this.matchId = {{ $tournamentMatch->id }};
            this.currentUserId = {{ auth()->id() }};
            this.currentUserName = "{!! addslashes(auth()->user()->nama_jalur ?? auth()->user()->email) !!}";
            
            this.raceDistance = 1000;
            this.playerDistance = this.raceDistance;
            this.opponentDistance = this.raceDistance;
            this.playerSpeed = 0;
            this.opponentSpeed = 0;
            this.gameState = 'ready_check';

            const bg = this.add.image(cx, H / 2, 'bgmenu');
            bg.setScale(Math.max(W / bg.width, H / bg.height));
            bg.setAlpha(0.28);

            this.countdownText = this.add.text(cx, 350, 'MENUNGGU KEDUA PLAYER SIAP...', {
                fontFamily: '"Press Start 2P", monospace',
                fontSize: '9px',
                color: '#ffffff',
                stroke: '#000000',
                strokeThickness: 6
            }).setOrigin(0.5).setDepth(1000);

            this.initWebSocket();
        }

        initWebSocket() {
            const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
            const wsHost = (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1' || window.location.hostname.startsWith('192.168.'))
                ? `${window.location.hostname}:8080`
                : `${window.location.hostname}/ws`;
            
            this.ws = new WebSocket(`${protocol}//${wsHost}`);

            this.ws.onopen = () => {
                this.ws.send(JSON.stringify({
                    type: 'join',
                    roomId: this.roomId,
                    payload: {
                        userId: this.currentUserId,
                        userName: this.currentUserName,
                        customizations: {}
                    }
                }));

                // Auto send arena_ready if user readied on bracket page
                @if(($tournamentMatch->player1_id == auth()->id() && $tournamentMatch->ready_p1) || ($tournamentMatch->player2_id == auth()->id() && $tournamentMatch->ready_p2))
                    window.pressArenaReady();
                @endif
            };

            this.ws.onmessage = (event) => {
                const message = JSON.parse(event.data);
                const { type, payload } = message;

                if (type === 'start_countdown') {
                    const overlay = document.getElementById('ready-overlay');
                    if (overlay) overlay.style.display = 'none';
                    this.startCountdownSequence();
                } else if (type === 'opponent_sync') {
                    this.opponentSpeed = payload.speed;
                    this.opponentDistance = payload.distance;
                } else if (type === 'game_finished') {
                    this.finishMatchServer(payload.winnerId);
                }
            };
        }

        startCountdownSequence() {
            let count = 3;
            this.countdownText.setFontSize(24);
            const timer = this.time.addEvent({
                delay: 1000,
                loop: true,
                callback: () => {
                    if (count > 0) {
                        this.countdownText.setText(String(count));
                        count--;
                    } else if (count === 0) {
                        this.countdownText.setText('GO!!');
                        this.gameState = 'racing';
                        count--;
                    } else {
                        this.countdownText.setVisible(false);
                        timer.destroy();
                    }
                }
            });
        }

        finishMatchServer(winnerId) {
            this.gameState = 'finished';
            fetch('/tournament/match/' + this.matchId + '/finish', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ winner_id: winnerId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.redirect_url) {
                    window.navigateToPage(data.redirect_url);
                } else {
                    window.navigateToPage('/tournament/{{ $tournamentMatch->tournament_id }}');
                }
            });
        }

        update(time, delta) {
            if (this.gameState === 'racing') {
                // Accelerate player speed on input tap
                const input = this.input.activePointer;
                if (input.isDown && time - (this.lastTap || 0) > 120) {
                    this.lastTap = time;
                    this.playerSpeed = Math.min(25, this.playerSpeed + 2.5);
                }
                this.playerSpeed = Math.max(0, this.playerSpeed - 0.08);
                this.playerDistance = Math.max(0, this.playerDistance - this.playerSpeed * (delta / 1000));

                if (this.ws && this.ws.readyState === WebSocket.OPEN) {
                    this.ws.send(JSON.stringify({
                        type: 'game_state_sync',
                        roomId: this.roomId,
                        payload: { speed: this.playerSpeed, distance: this.playerDistance }
                    }));
                }

                if (this.playerDistance <= 0) {
                    if (this.ws && this.ws.readyState === WebSocket.OPEN) {
                        this.ws.send(JSON.stringify({
                            type: 'game_over',
                            roomId: this.roomId,
                            payload: { winnerId: this.currentUserId }
                        }));
                    }
                }
            }
        }
    }

    function pressArenaReady() {
        const btn = document.getElementById('arena-ready-btn');
        if (btn) {
            btn.classList.add('pressed');
            btn.textContent = 'SUDAH SIAP!';
        }
        const statusEl = document.getElementById('ready-status-text');
        if (statusEl) statusEl.innerHTML = 'Kamu sudah siap!<br><span class="ready-waiting-dots">Menunggu lawan</span>';

        if (window.activeTournamentArenaGame) {
            const scene = window.activeTournamentArenaGame.scene.getScene('ArenaScene');
            if (scene && scene.ws && scene.ws.readyState === WebSocket.OPEN) {
                scene.ws.send(JSON.stringify({
                    type: 'arena_ready',
                    roomId: scene.roomId,
                    payload: { userId: scene.currentUserId }
                }));
            }
        }
    }
    window.pressArenaReady = pressArenaReady;

    window.activeTournamentArenaGame = new Phaser.Game({
        type: Phaser.AUTO,
        width: GAME_WIDTH,
        height: GAME_HEIGHT,
        parent: 'game-container',
        pixelArt: true,
        scene: [LoadingScene, ArenaScene],
        scale: { mode: Phaser.Scale.RESIZE, autoCenter: Phaser.Scale.CENTER_BOTH }
    });
}
</script>
@endpush
