@extends('layouts.game')

@section('title', 'Hall of Fame - Juara Turnamen')

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

    .back-btn:hover { transform: scale(1.05); }
    .back-btn:active { transform: scale(0.9); }

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

    .hall-fame-btn:hover { transform: scale(1.05); }
    .hall-fame-btn:active { transform: scale(0.9); }

    .title-banner {
        font-family: 'Press Start 2P', monospace;
        font-size: 11px;
        color: #fef08a;
        margin-top: 68px;
        margin-bottom: 2px;
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

    .title-sub {
        font-family: 'Press Start 2P', monospace;
        font-size: 7px;
        color: #ffffff;
        margin-bottom: 12px;
        text-align: center;
        z-index: 11;
        text-shadow:
            1px 1px 0px #000000,
            -1px -1px 0px #000000,
            1px -1px 0px #000000,
            -1px 1px 0px #000000;
    }

    /* Main Scrollable Content Container */
    .hall-scroll-container {
        width: 92%;
        max-width: 520px;
        flex: 1;
        overflow-y: auto;
        padding-bottom: 30px;
        box-sizing: border-box;
        z-index: 12;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .hall-scroll-container::-webkit-scrollbar { width: 4px; }
    .hall-scroll-container::-webkit-scrollbar-thumb { background: rgba(251, 191, 36, 0.5); border-radius: 4px; }

    /* Pixel Card Panel */
    .menu-panel {
        background: rgba(10, 18, 36, 0.88);
        border: 2px solid rgba(251, 191, 36, 0.4);
        border-radius: 16px;
        padding: 14px 16px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        box-sizing: border-box;
    }

    .rank-tag {
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        padding: 6px 10px;
        border-radius: 6px;
        text-transform: uppercase;
        white-space: nowrap;
        font-weight: bold;
    }
    .rank-tag-1 { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #ffffff; border: 1px solid #fef08a; }
    .rank-tag-2 { background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%); color: #ffffff; border: 1px solid #cbd5e1; }
    .rank-tag-3 { background: linear-gradient(135deg, #b45309 0%, #78350f 100%); color: #ffffff; border: 1px solid #fde68a; }

    .winner-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
        flex: 1;
    }
    .winner-name {
        font-size: 9px;
        color: #ffffff;
    }
    .tournament-title-text {
        font-size: 6px;
        color: rgba(255, 255, 255, 0.6);
    }

    .winner-stats {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 4px;
        font-size: 7px;
    }

    .coin-val {
        display: flex;
        align-items: center;
        gap: 4px;
        color: #fbbf24;
    }
    .coin-val img { width: 12px; height: 12px; image-rendering: pixelated; }

    .date-val {
        font-size: 6px;
        color: rgba(255, 255, 255, 0.4);
    }

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
    <div class="back-btn" onclick="window.navigateToPage('/tournament')">
        <img src="{{ asset_v('game_pacu/assets/image/ui/back.png') }}" alt="Back">
    </div>

    <!-- Tombol Hall of Fame Top Right -->
    <div class="hall-fame-btn" onclick="window.navigateToPage('/tournament/hall-of-fame')">
        <img src="{{ asset_v('game_pacu/assets/image/ui/piala.png') }}" alt="Hall of Fame">
    </div>

    <div class="title-banner">HALL OF FAME JUARA</div>
    <div class="title-sub">DAFTAR PEMENANG TURNAMEN SEPANJANG MASA</div>

    <div class="hall-scroll-container">
        @forelse($winners as $w)
            <div class="menu-panel">
                <div style="display: flex; align-items: center; gap: 10px; flex: 1;">
                    <div class="rank-tag rank-tag-{{ $w->rank }}">
                        JUARA {{ $w->rank }}
                    </div>
                    <div class="winner-info">
                        <div class="winner-name">{{ $w->user ? $w->user->nama_jalur : 'Pemain' }}</div>
                        <div class="tournament-title-text">{{ $w->tournament_title }}</div>
                    </div>
                </div>

                <div class="winner-stats">
                    <div class="coin-val">
                        <img src="{{ asset_v('game_pacu/assets/image/ui/koin.png') }}" alt="Koin">
                        <span>{{ number_format($w->prize_coins) }}</span>
                    </div>
                    <div class="date-val">
                        {{ $w->created_at->format('d M Y') }}
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-box">
                Belum ada pemenang turnamen yang terdaftar saat ini.
            </div>
        @endforelse

        @if($winners->hasPages())
            <div style="margin-top: 10px; display: flex; justify-content: center;">
                {{ $winners->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
