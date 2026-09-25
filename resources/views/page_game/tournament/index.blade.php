@extends('layouts.game')

@section('title', 'Pacu Jalur: Turnamen')

@section('content')
<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #060d18;
        color: #e2e8f0;
        font-family: 'Press Start 2P', monospace;
        overflow: hidden;
    }

    #game-ui {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        background: url('{{ asset_v("game_pacu/assets/image/bg/bgmenu.jpg") }}') no-repeat center center;
        background-size: cover;
        z-index: 10;
        box-sizing: border-box;
        overflow: hidden;
    }

    /* Top Navigation (Identik dengan main_menu/index.blade.php) */
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
        z-index: 15;
        transition: all 0.15s ease;
        box-sizing: border-box;
    }

    .back-btn img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        image-rendering: pixelated;
    }

    .back-btn:hover {
        transform: scale(1.05);
    }

    .back-btn:active {
        transform: scale(0.9);
    }

    .hall-fame-btn {
        position: absolute;
        top: 16px;
        right: 14px;
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
        box-sizing: border-box;
    }

    .hall-fame-btn img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        image-rendering: pixelated;
    }

    .hall-fame-btn:hover {
        transform: scale(1.05);
    }

    .hall-fame-btn:active {
        transform: scale(0.9);
    }

    .title-banner {
        font-family: 'Press Start 2P', monospace;
        font-size: 12px;
        color: #d8b4fe;
        margin-top: 68px;
        margin-bottom: 15px;
        text-align: center;
        line-height: 1.4;
        letter-spacing: 1px;
        z-index: 11;
        text-shadow:
            2px 2px 0px #000000,
            -2px -2px 0px #000000,
            2px -2px 0px #000000,
            -2px 2px 0px #000000,
            0px 2px 0px #000000,
            0px -2px 0px #000000,
            2px 0px 0px #000000,
            -2px 0px 0px #000000;
        -webkit-text-stroke: 1px #000000;
    }

    /* Main Scrollable Content Container */
    .tournament-scroll-container {
        width: 92%;
        max-width: 520px;
        flex: 1;
        overflow-y: auto;
        padding-bottom: 30px;
        box-sizing: border-box;
        z-index: 12;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    /* Hide Scrollbar for clean look */
    .tournament-scroll-container::-webkit-scrollbar {
        width: 4px;
    }
    .tournament-scroll-container::-webkit-scrollbar-thumb {
        background: rgba(168, 85, 247, 0.5);
        border-radius: 4px;
    }

    /* Pixel Card Panel */
    .menu-panel {
        background: rgba(10, 18, 36, 0.88);
        border: 2px solid rgba(168, 85, 247, 0.4);
        border-radius: 16px;
        padding: 16px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
        display: flex;
        flex-direction: column;
        gap: 12px;
        box-sizing: border-box;
        position: relative;
    }

    .trophy-summary-header {
        font-size: 9px;
        color: #fbbf24;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 1px dashed rgba(255, 255, 255, 0.15);
        padding-bottom: 8px;
    }
    .trophy-summary-header img {
        width: 16px;
        height: 16px;
        image-rendering: pixelated;
    }

    .trophy-row {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 4px;
    }

    .trophy-badge-box {
        background: rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(251, 191, 36, 0.3);
        border-radius: 8px;
        padding: 8px 12px;
        text-align: center;
        min-width: 120px;
        flex-shrink: 0;
    }

    .tn-title-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
    }

    .tn-name {
        font-size: 10px;
        color: #ffffff;
        line-height: 1.5;
    }

    .tn-status-badge {
        font-size: 7px;
        padding: 4px 8px;
        border-radius: 6px;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .badge-active { background: #16a34a; color: #ffffff; border: 1px solid #22c55e; }
    .badge-completed { background: #2563eb; color: #ffffff; border: 1px solid #60a5fa; }
    .badge-draft { background: #d97706; color: #ffffff; border: 1px solid #fbbf24; }

    .tn-info-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
        font-size: 7px;
        color: rgba(255, 255, 255, 0.75);
        border-top: 1px dashed rgba(255,255,255,0.1);
        padding-top: 10px;
    }

    .pixel-btn {
        background: linear-gradient(180deg, #a855f7 0%, #7e22ce 100%);
        border: 2px solid #581c87;
        border-radius: 8px;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.35), 0 4px 0 #581c87;
        color: white;
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        padding: 10px 16px;
        text-align: center;
        cursor: pointer;
        text-transform: uppercase;
        text-decoration: none;
        display: inline-block;
        transition: all 0.12s ease;
    }
    .pixel-btn:hover { background: #c084fc; transform: translateY(-1px); }
    .pixel-btn:active { transform: translateY(3px); box-shadow: 0 1px 0 #581c87; }

    .empty-box {
        background: rgba(10, 18, 36, 0.85);
        border: 2px dashed rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        padding: 40px 20px;
        text-align: center;
        font-size: 8px;
        color: rgba(255, 255, 255, 0.5);
        line-height: 1.8;
    }
</style>

<div id="game-ui">
    <!-- Tombol Back Top Left (Identik dengan main_menu/index.blade.php) -->
    <div class="back-btn" onclick="window.navigateToPage('/room')">
        <img src="{{ asset_v('game_pacu/assets/image/ui/back.png') }}" alt="Back">
    </div>

    <!-- Tombol Hall of Fame Top Right -->
    <div class="hall-fame-btn" onclick="window.navigateToPage('/tournament/hall-of-fame')">
        <img src="{{ asset_v('game_pacu/assets/image/ui/piala.png') }}" alt="Hall of Fame">
    </div>

    <div class="title-banner">TURNAMEN PACU JALUR</div>

    <div class="tournament-scroll-container">
        @if($userWinners->count() > 0)
            <div class="menu-panel">
                <div class="trophy-summary-header">
                    <img src="{{ asset_v('game_pacu/assets/image/ui/piala.png') }}" alt="Piala">
                    KOLEKSI TROFI SAYA ({{ $userWinners->count() }})
                </div>
                <div class="trophy-row">
                    @foreach($userWinners as $w)
                        <div class="trophy-badge-box">
                            <div style="font-size: 8px; color: #fbbf24;">JUARA {{ $w->rank }}</div>
                            <div style="font-size: 6px; color: #ffffff; margin-top: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 110px;">{{ $w->tournament_title }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div style="font-size: 8px; color: rgba(255,255,255,0.6); margin-left: 4px;">
            DAFTAR TURNAMEN RESMI:
        </div>

        @forelse($tournaments as $t)
            <div class="menu-panel">
                <div class="tn-title-row">
                    <div class="tn-name">{{ $t->title }}</div>
                    <div>
                        @if($t->status === 'active')
                            <span class="tn-status-badge badge-active">BERLANGSUNG</span>
                        @elseif($t->status === 'completed')
                            <span class="tn-status-badge badge-completed">SELESAI</span>
                        @else
                            <span class="tn-status-badge badge-draft">PERSIAPAN</span>
                        @endif
                    </div>
                </div>

                <div class="tn-info-row">
                    <div>
                        PESERTA: <span style="color: #ffffff;">{{ $t->participants->count() }}/{{ $t->max_participants }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 4px;">
                        <img src="{{ asset_v('game_pacu/assets/image/ui/koin.png') }}" style="width: 12px; height: 12px; image-rendering: pixelated;" alt="Koin">
                        <span style="color: #fbbf24;">{{ number_format($t->prize_coins) }} COINS</span>
                    </div>
                    <div>
                        <a href="{{ route('tournament.show', $t->id) }}" class="pixel-btn">
                            LIHAT BAGAN
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-box">
                Belum ada turnamen aktif saat ini.<br>
                Silakan tunggu pengumuman dari Admin.
            </div>
        @endforelse
    </div>
</div>
@endsection
