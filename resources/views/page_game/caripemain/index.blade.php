@extends('layouts.game')

@section('title', 'Pacu Jalur: The Pixel — Cari Pemain')

@section('content')
<style>
    #search-dashboard {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        background: #0c111d url('/game_pacu/assets/image/bg/bgmenu.jpg') no-repeat center center;
        background-size: cover;
        z-index: 10;
        box-sizing: border-box;
        overflow: hidden;
        padding-bottom: 10px;
    }

    #search-dashboard #ps5-particles {
        display: none;
    }

    #search-dashboard .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        padding: 16px 20px 8px;
        z-index: 11;
        margin-top: 10px;
        box-sizing: border-box;
    }

    #search-dashboard .back-btn-container {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: filter 0.15s ease, opacity 0.15s ease;
    }

    #search-dashboard .back-btn {
        width: 36px;
        height: 36px;
        pointer-events: none;
    }

    #search-dashboard .back-btn-container:hover {
        filter: brightness(1.3);
    }

    #search-dashboard .back-btn-container:active {
        filter: brightness(0.8);
        opacity: 0.8;
    }

    #search-dashboard .coin-display {
        display: flex;
        align-items: center;
        gap: 6px;
        z-index: 15;
        box-sizing: border-box;
    }

    #search-dashboard .coin-icon-wrapper {
        position: relative;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #search-dashboard .coin-icon-wrapper img {
        width: 100%;
        height: 100%;
        image-rendering: pixelated;
    }

    #search-dashboard .coin-amount {
        font-family: 'Pixelify Sans', monospace;
        font-size: 13px;
        font-weight: bold;
        color: #000000;
        line-height: 1;
        text-shadow:
            1px 1px 0px #ffffff,
            -1px -1px 0px #ffffff,
            1px -1px 0px #ffffff,
            -1px 1px 0px #ffffff;
    }

    #search-dashboard .search-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        width: 100%;
        overflow-y: auto;
        z-index: 11;
        scrollbar-width: thin;
        scrollbar-color: rgba(239, 68, 68, 0.4) rgba(255, 255, 255, 0.02);
        box-sizing: border-box;
        padding: 0 14px;
    }

    #search-dashboard .search-container::-webkit-scrollbar {
        width: 5px;
    }

    #search-dashboard .search-container::-webkit-scrollbar-thumb {
        background: rgba(239, 68, 68, 0.4);
        border-radius: 4px;
    }

    #search-dashboard .search-card {
        background: rgba(15, 23, 42, 0.88);
        border: 1px solid rgba(239, 68, 68, 0.3);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-radius: 20px;
        padding: 18px;
        box-shadow:
            0 10px 32px rgba(0, 0, 0, 0.7),
            0 0 0 1px rgba(255, 255, 255, 0.08),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 16px;
        position: relative;
        overflow: hidden;
    }

    #search-dashboard .search-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: repeating-linear-gradient(
            0deg, transparent, transparent 3px,
            rgba(0,0,0,0.03) 3px, rgba(0,0,0,0.03) 4px
        );
        pointer-events: none;
        z-index: 0;
        border-radius: 20px;
    }

    #search-dashboard .search-title {
        font-family: 'Press Start 2P', monospace;
        font-size: 10px;
        color: #f87171;
        text-shadow: 0 0 8px rgba(239, 68, 68, 0.5);
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    #search-dashboard .search-form {
        display: flex;
        gap: 10px;
        width: 100%;
    }

    #search-dashboard .search-input {
        flex: 1;
        background: rgba(0, 0, 0, 0.4);
        border: 1.5px solid rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 10px 14px;
        font-family: 'Pixelify Sans', monospace;
        font-size: 14px;
        color: #ffffff;
        outline: none;
        transition: all 0.2s ease;
    }

    #search-dashboard .search-input::placeholder {
        color: rgba(255, 255, 255, 0.35);
    }

    #search-dashboard .search-input:focus {
        border-color: #f87171;
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.3);
        background: rgba(0, 0, 0, 0.5);
    }

    #search-dashboard .search-btn {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        border-radius: 12px;
        padding: 10px 18px;
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        color: #ffffff;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        transition: all 0.15s ease;
        text-shadow: 1px 1px 0px rgba(0, 0, 0, 0.4);
    }

    #search-dashboard .search-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
    }

    #search-dashboard .search-btn:active {
        transform: translateY(1px);
    }

    #search-dashboard .section-header {
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        color: #ffffff;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
        text-shadow: 2px 2px 0px #000000;
        text-transform: uppercase;
    }

    #search-dashboard .players-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 20px;
    }

    #search-dashboard .player-row {
        background: rgba(15, 23, 42, 0.88);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 16px;
        padding: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.06);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
    }

    #search-dashboard .player-row:hover {
        border-color: rgba(239, 68, 68, 0.5);
        background: rgba(30, 41, 59, 0.95);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.5), 0 0 16px rgba(239, 68, 68, 0.2);
    }

    #search-dashboard .player-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    #search-dashboard .player-avatar-wrapper {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: 2px solid #ef4444;
        background: #0f172a;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.3);
    }

    #search-dashboard .player-avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    #search-dashboard .player-details {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    #search-dashboard .player-name {
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        color: #ffffff;
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
    }

    #search-dashboard .player-wins {
        font-size: 11px;
        color: #f59e0b;
        font-weight: bold;
    }

    #search-dashboard .detail-btn {
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.35);
        border-radius: 10px;
        padding: 8px 12px;
        font-family: 'Press Start 2P', monospace;
        font-size: 7px;
        color: #f87171;
        cursor: pointer;
        transition: all 0.2s ease;
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
    }

    #search-dashboard .detail-btn:hover {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border-color: #ef4444;
        color: #ffffff;
        box-shadow:
            0 4px 0 #7f1d1d,
            0 6px 16px rgba(239, 68, 68, 0.35);
        transform: translateY(-2px);
        text-shadow: 0 1px 2px rgba(0,0,0,0.4);
    }

    #search-dashboard .detail-btn:active {
        transform: translateY(2px);
        box-shadow: 0 1px 0 #7f1d1d;
    }

    #search-dashboard .players-empty {
        background: rgba(15, 23, 42, 0.85);
        border: 1.5px dashed rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    #search-dashboard .empty-icon {
        font-size: 24px;
        margin-bottom: 8px;
    }

    #search-dashboard .empty-title {
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        color: #94a3b8;
        margin-bottom: 4px;
    }

    #search-dashboard .empty-subtitle {
        font-size: 11px;
        color: #64748b;
    }
</style>

@php
$user = auth()->user();
@endphp

<div id="search-dashboard">
    <!-- Top Bar -->
    <div>
        <div class="top-bar">
            <div class="back-btn-container" onclick="goBack()">
                <img class="back-btn" src="/game_pacu/assets/image/ui/back.png" alt="Kembali">
            </div>
            <div class="coin-display" onclick="window.navigateToPage('/shop')">
                <span class="sprint-icon me-1"><img src="/game_pacu/assets/image/ui/sprint.png" alt="Sprint" style="width: 20px; height: 20px; object-fit: contain;"></span>
                <span class="coin-amount">{{ number_format($user->kuansing_poin, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Main Search Area -->
    <div class="search-container">
        <!-- Search Box -->
        <div class="search-card">
            <div class="search-title"><i class="bi bi-search me-1"></i> CARI PAMACU</div>
            <form action="/cari-pemain" method="GET" class="search-form">
                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="Masukkan nama jalur..."
                    value="{{ $search }}"
                    autocomplete="off"
                >
                <button type="submit" class="search-btn">CARI</button>
            </form>
        </div>

        <!-- Player List Header -->
        <div class="section-header">
            {{ $search ? 'Hasil Pencarian' : 'Rekomendasi Pemain' }}
        </div>

        <!-- Player Cards -->
        <div class="players-list">
            @if (empty($players) || count($players) == 0)
                <div class="players-empty">
                    <div class="empty-icon"><i class="bi bi-search text-secondary" style="font-size: 28px;"></i></div>
                    <div class="empty-title">TIDAK DITEMUKAN</div>
                    <div class="empty-subtitle">Silakan cari dengan kata kunci lain.</div>
                </div>
            @else
                @foreach ($players as $player)
                    @php
                    $playerWins = $player->wins()->count();
                    $dbAvatar = $player->foto_profile;
                    if (!empty($dbAvatar)) {
                        if (strpos($dbAvatar, 'http://') === 0 || strpos($dbAvatar, 'https://') === 0) {
                            $avatarUrl = $dbAvatar;
                        } elseif (strpos($dbAvatar, '/') !== false || strpos($dbAvatar, '.gif') !== false) {
                            $avatarUrl = (strpos($dbAvatar, '/') === 0) ? $dbAvatar : '/' . $dbAvatar;
                        } else {
                            $avatarUrl = '/game_pacu/assets/image/ui/' . $dbAvatar . '.gif';
                        }
                    } else {
                        $avatarUrl = '/game_pacu/assets/image/ui/profil.gif';
                    }
                    @endphp
                    <div class="player-row">
                        <div class="player-info">
                            <div class="player-avatar-wrapper">
                                <img src="{{ $avatarUrl }}" alt="Avatar" class="player-avatar-img">
                            </div>
                            <div class="player-details">
                                <div class="player-name">{{ $player->nama_jalur ?? $player->email }}</div>
                                <div class="player-wins"><i class="bi bi-trophy-fill text-warning me-1"></i>{{ $playerWins }} Wins</div>
                            </div>
                        </div>
                        <button class="detail-btn" onclick="viewDetail({{ $player->id }})">PROFIL</button>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
{
    window.goBack = function() {
        window.navigateToPage('/main-menu');
    };

    window.viewDetail = function(id) {
        window.navigateToPage('/cari-pemain/detail/' + id);
    };
}
</script>
@endpush
