@extends('layouts.game')

@section('title', 'Live Preview Spectate - ' . ($tournamentMatch->tournament->title ?? 'Pacu Jalur'))

@push('styles')
<style>
    body { margin: 0; padding: 0; background: #060d18; color: #fff; font-family: 'Press Start 2P', monospace; overflow: hidden; }

    .back-btn {
        position: absolute;
        top: 16px;
        left: 14px;
        width: 36px;
        height: 36px;
        background: none;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 600;
    }
    .back-btn img { width: 100%; height: 100%; object-fit: contain; image-rendering: pixelated; }

    /* ===== SPECTATOR RADAR OVERLAY ===== */
    #spectator-loading-overlay {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(6, 13, 24, 0.94);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        z-index: 550;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 18px;
        font-family: 'Press Start 2P', monospace;
    }

    .spectator-title {
        font-size: 11px;
        color: #38bdf8;
        text-shadow: 0 0 20px rgba(56, 189, 248, 0.8);
        letter-spacing: 2px;
        text-align: center;
        animation: specTitleGlow 1.5s ease-in-out infinite alternate;
    }
    @keyframes specTitleGlow {
        from { text-shadow: 0 0 10px rgba(56, 189, 248, 0.5); }
        to { text-shadow: 0 0 25px rgba(56, 189, 248, 1); }
    }

    .radar-spinner {
        width: 48px;
        height: 48px;
        border: 4px solid rgba(56, 189, 248, 0.2);
        border-top: 4px solid #38bdf8;
        border-radius: 50%;
        animation: spinRadar 1s linear infinite;
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.4);
    }
    @keyframes spinRadar {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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
        font-size: 8px;
        color: #ffffff;
        margin-bottom: 4px;
        max-width: 100px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .vs-label { font-size: 18px; color: #38bdf8; text-shadow: 2px 2px 0 #0369a1; }

    .spectator-status-text {
        font-size: 7px;
        color: #fef08a;
        text-align: center;
        line-height: 1.8;
    }
    .ready-waiting-dots::after {
        content: '';
        animation: dots 1.5s steps(3, end) infinite;
    }
    @keyframes dots {
        0% { content: ''; } 33% { content: '.'; } 66% { content: '..'; } 100% { content: '...'; }
    }

    /* ===== MATCH WINNER BANNER OVERLAY ===== */
    #match-finish-overlay {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(6, 13, 24, 0.90);
        backdrop-filter: blur(10px);
        z-index: 560;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 16px;
    }

    .winner-banner-title {
        font-size: 12px;
        color: #fbbf24;
        text-shadow: 2px 2px 0 #78350f;
    }
    .winner-banner-name {
        font-size: 14px;
        color: #ffffff;
        text-shadow: 2px 2px 0 #000;
    }

    .pixel-btn-back {
        background: linear-gradient(180deg, #38bdf8 0%, #0284c7 100%);
        border: 2px solid #0369a1;
        color: white;
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        box-shadow: 0 4px 0 #0369a1;
        margin-top: 10px;
    }
</style>
@endpush

@section('content')
<!-- Tombol Back Top Left -->
<div class="back-btn" onclick="window.navigateToPage('{{ route('tournament.show', $tournamentMatch->tournament_id) }}')">
    <img src="{{ asset_v('game_pacu/assets/image/ui/back.png') }}" alt="Back">
</div>

<!-- ===== SPECTATOR LOADING RADAR OVERLAY ===== -->
<div id="spectator-loading-overlay">
    <div class="spectator-title">LIVE PREVIEW TURNAMEN</div>
    <div class="radar-spinner"></div>
    <div class="vs-badge">
        <div class="vs-player">
            <div class="vs-player-name">
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
    <div class="spectator-status-text">
        <span class="ready-waiting-dots">MENUNGGU KEDUA PLAYER SIAP BERTANDING</span>
    </div>
</div>

<!-- ===== MATCH FINISHED OVERLAY ===== -->
<div id="match-finish-overlay">
    <div class="winner-banner-title">PERTANDINGAN SELESAI</div>
    <div class="winner-banner-name" id="winner-name-display">PEMENANG: TBD</div>
    <a href="{{ route('tournament.show', $tournamentMatch->tournament_id) }}" class="pixel-btn-back">
        KEMBALI KE BAGAN TURNAMEN
    </a>
</div>
@endsection

@push('scripts')
<script src="{{ asset_v('game_pacu/assets/js/phaser.min.js') }}"></script>
<script>
{
    const GAME_WIDTH = 360;
    const GAME_HEIGHT = 760;

    class SpectateScene extends Phaser.Scene {
        constructor() { super({ key: 'SpectateScene' }); }

        preload() {
            const v = (typeof window !== 'undefined' && window.GAME_VERSION) ? `?v=${window.GAME_VERSION}` : '';
            this.load.image('bgmenu', `/game_pacu/assets/image/bg/bgmenu.jpg${v}`);
            this.load.image('jalur_boat', `/game_pacu/assets/image/jalur/jalur.png${v}`);
        }

        create() {
            const W = this.scale.width;
            const H = this.scale.height;
            const cx = W / 2;

            this.roomId = "{{ $tournamentMatch->room_id }}";
            this.matchId = {{ $tournamentMatch->id }};

            this.player1Name = "{!! addslashes($tournamentMatch->player1 ? $tournamentMatch->player1->nama_jalur : 'PLAYER 1') !!}";
            this.player2Name = "{!! addslashes($tournamentMatch->player2 ? $tournamentMatch->player2->nama_jalur : 'PLAYER 2') !!}";
            this.player1Id = {{ $tournamentMatch->player1_id ?? 0 }};
            this.player2Id = {{ $tournamentMatch->player2_id ?? 0 }};

            this.p1Distance = 1000;
            this.p2Distance = 1000;
            this.p1Speed = 0;
            this.p2Speed = 0;
            this.raceDistance = 1000;

            const bg = this.add.image(cx, H / 2, 'bgmenu');
            bg.setScale(Math.max(W / bg.width, H / bg.height));
            bg.setAlpha(0.28);

            // Create Boat Sprites
            this.boat1Group = this.add.container(cx, 420);
            this.boat1Img = this.add.image(0, 0, 'jalur_boat').setScale(2.3);
            this.boat1Img.setTint(0x38bdf8);
            this.boat1Group.add(this.boat1Img);
            this.p1Text = this.add.text(0, -50, this.player1Name, {
                fontFamily: '"Press Start 2P", monospace', fontSize: '9px', color: '#38bdf8', stroke: '#000', strokeThickness: 4
            }).setOrigin(0.5);
            this.boat1Group.add(this.p1Text);

            this.boat2Group = this.add.container(cx, 280);
            this.boat2Img = this.add.image(0, 0, 'jalur_boat').setScale(2.3);
            this.boat2Img.setTint(0xef4444);
            this.boat2Group.add(this.boat2Img);
            this.p2Text = this.add.text(0, -50, this.player2Name, {
                fontFamily: '"Press Start 2P", monospace', fontSize: '9px', color: '#ef4444', stroke: '#000', strokeThickness: 4
            }).setOrigin(0.5);
            this.boat2Group.add(this.p2Text);

            // Top Status Banner
            this.statusBanner = this.add.text(cx, 80, 'LIVE PREVIEW TURNAMEN', {
                fontFamily: '"Press Start 2P", monospace', fontSize: '10px', color: '#fbbf24', stroke: '#000', strokeThickness: 4
            }).setOrigin(0.5);

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
                    payload: { userId: 999999, userName: 'SPECTATOR', customizations: {} }
                }));
            };

            this.ws.onmessage = (event) => {
                const message = JSON.parse(event.data);
                const { type, payload } = message;

                if (type === 'start_countdown' || type === 'game_in_progress' || type === 'opponent_sync' || type === 'game_state_sync') {
                    this.hideSpectatorLoading();
                }

                if (type === 'opponent_sync' || type === 'game_state_sync') {
                    const senderId = parseInt(payload.userId);
                    if (senderId === this.player1Id) {
                        this.p1Speed = payload.speed || 0;
                        this.p1Distance = payload.distance || 1000;
                    } else if (senderId === this.player2Id) {
                        this.p2Speed = payload.speed || 0;
                        this.p2Distance = payload.distance || 1000;
                    }
                }

                if (type === 'game_finished') {
                    const winnerId = parseInt(payload.winnerId);
                    let winnerName = (winnerId === this.player1Id) ? this.player1Name : this.player2Name;
                    this.showFinishOverlay(winnerName);
                }
            };
        }

        hideSpectatorLoading() {
            const overlay = document.getElementById('spectator-loading-overlay');
            if (overlay && overlay.style.display !== 'none') {
                overlay.style.transition = 'opacity 0.5s ease';
                overlay.style.opacity = '0';
                setTimeout(() => { overlay.style.display = 'none'; }, 500);
            }
        }

        showFinishOverlay(winnerName) {
            const overlay = document.getElementById('match-finish-overlay');
            const winnerText = document.getElementById('winner-name-display');
            if (winnerText) winnerText.textContent = "PEMENANG: " + winnerName.toUpperCase();
            if (overlay) overlay.style.display = 'flex';
        }

        update() {
            const cx = this.scale.width / 2;
            // Visual position adjustment based on race distance difference
            const diff = (this.p2Distance - this.p1Distance);
            this.boat1Group.x = cx + Math.min(100, Math.max(-100, diff * 1.5));
            this.boat2Group.x = cx - Math.min(100, Math.max(-100, diff * 1.5));
        }
    }

    window.activeSpectateGame = new Phaser.Game({
        type: Phaser.AUTO,
        width: GAME_WIDTH,
        height: GAME_HEIGHT,
        parent: 'game-container',
        pixelArt: true,
        scene: [SpectateScene],
        scale: { mode: Phaser.Scale.RESIZE, autoCenter: Phaser.Scale.CENTER_BOTH }
    });
}
</script>
@endpush
