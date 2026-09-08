@extends('layouts.game')

@section('title', 'Pacu Jalur: The Pixel ” Detail Pemain')

@section('content')
<style>
    #detail-pemain-dashboard {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        background: #0c111d url('{{ asset_v('game_pacu/assets/image/bg/bgmenu.jpg') }}') no-repeat center center;
        background-size: cover;
        z-index: 10;
        box-sizing: border-box;
        overflow: hidden;
        padding-bottom: 10px;
    }

    #detail-pemain-dashboard #ps5-particles {
        display: none;
    }

    #detail-pemain-dashboard .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        padding: 16px 20px;
        z-index: 11;
        margin-top: 10px;
        box-sizing: border-box;
    }

    #detail-pemain-dashboard .back-btn-container {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: filter 0.15s ease, opacity 0.15s ease;
    }

    #detail-pemain-dashboard .back-btn {
        width: 36px;
        height: 36px;
        pointer-events: none;
    }

    #detail-pemain-dashboard .back-btn-container:hover {
        filter: brightness(1.3);
    }

    #detail-pemain-dashboard .back-btn-container:active {
        filter: brightness(0.8);
        opacity: 0.8;
    }

    #detail-pemain-dashboard .coin-display {
        display: flex;
        align-items: center;
        gap: 6px;
        z-index: 15;
        box-sizing: border-box;
        cursor: pointer;
        transition: transform 0.2s;
    }

    #detail-pemain-dashboard .coin-display:hover {
        transform: scale(1.05);
    }

    #detail-pemain-dashboard .coin-icon-wrapper {
        position: relative;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #detail-pemain-dashboard .coin-icon-wrapper img {
        width: 100%;
        height: 100%;
        image-rendering: pixelated;
    }

    #detail-pemain-dashboard .coin-amount {
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        font-weight: bold;
        color: #000000;
        line-height: 1;
        text-shadow:
            1px 1px 0px #ffffff,
            -1px -1px 0px #ffffff,
            1px -1px 0px #ffffff,
            -1px 1px 0px #ffffff,
            0px 1px 0px #ffffff,
            0px -1px 0px #ffffff,
            1px 0px 0px #ffffff,
            -1px 0px 0px #ffffff;
    }

    #detail-pemain-dashboard .profile-container {
        flex: 1 1 0;
        min-height: 0;
        display: block;
        width: 100%;
        overflow-y: auto;
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
        touch-action: pan-y;
        overscroll-behavior: contain;
        z-index: 11;
        scrollbar-width: thin;
        scrollbar-color: rgba(59, 130, 246, 0.4) rgba(255, 255, 255, 0.02);
        box-sizing: border-box;
        padding-bottom: 24px;
    }

    #detail-pemain-dashboard .profile-container::-webkit-scrollbar {
        width: 5px;
    }

    #detail-pemain-dashboard .profile-container::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.02);
        border-radius: 4px;
    }

    #detail-pemain-dashboard .profile-container::-webkit-scrollbar-thumb {
        background: rgba(59, 130, 246, 0.4);
        border-radius: 4px;
    }

    #detail-pemain-dashboard .ps5-card {
        background: rgba(15, 23, 42, 0.88);
        border: 1px solid rgba(59, 130, 246, 0.3);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-radius: 24px;
        width: calc(100% - 28px);
        margin: 0 auto 12px;
        padding: 16px;
        box-shadow:
            0 10px 32px rgba(0, 0, 0, 0.7),
            0 0 0 1px rgba(255, 255, 255, 0.08),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
        box-sizing: border-box;
        position: relative;
        overflow: hidden;
    }

    #detail-pemain-dashboard .ps5-card::before {
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
        border-radius: 24px;
    }

    #detail-pemain-dashboard .profile-avatar-wrapper {
        position: relative;
        margin-top: 6px;
        margin-bottom: 10px;
        z-index: 2;
    }

    #detail-pemain-dashboard .profile-avatar-container {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 3.5px solid #3b82f6;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6), 0 0 15px rgba(59, 130, 246, 0.5);
        background: #0f172a;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
    }

    #detail-pemain-dashboard .profile-avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    #detail-pemain-dashboard .status-dot-pulse {
        position: absolute;
        bottom: 3px;
        right: 3px;
        width: 13px;
        height: 13px;
        background-color: #22c55e;
        border-radius: 50%;
        border: 2px solid #0f172a;
        box-shadow: none;
    }

    #detail-pemain-dashboard .profile-name {
        font-size: 13px;
        font-weight: bold;
        color: #ffffff;
        margin-bottom: 6px;
        text-align: center;
        font-family: 'Press Start 2P', monospace;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    }

    #detail-pemain-dashboard .profile-badge {
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        color: #ffffff;
        padding: 5px 12px;
        border-radius: 20px;
        box-shadow: 0 0 10px rgba(59, 130, 246, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.15);
        display: inline-block;
        margin-bottom: 16px;
        text-shadow: 1px 1px 0px rgba(0, 0, 0, 0.3);
        text-transform: uppercase;
    }

    #detail-pemain-dashboard .stats-row {
        display: flex;
        gap: 8px;
        width: 100%;
        margin-bottom: 14px;
    }

    #detail-pemain-dashboard .stat-card {
        flex: 1;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        padding: 8px 6px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.05);
        box-sizing: border-box;
    }

    #detail-pemain-dashboard .stat-card-gold {
        border-color: rgba(245, 158, 11, 0.4);
        background: linear-gradient(180deg, rgba(245, 158, 11, 0.12) 0%, rgba(0, 0, 0, 0.2) 100%);
        box-shadow: inset 0 1px 0 rgba(245, 158, 11, 0.2), 0 0 12px rgba(245, 158, 11, 0.1);
    }

    #detail-pemain-dashboard .stat-card-silver {
        border-color: rgba(148, 163, 184, 0.4);
        background: linear-gradient(180deg, rgba(148, 163, 184, 0.12) 0%, rgba(0, 0, 0, 0.2) 100%);
        box-shadow: inset 0 1px 0 rgba(148, 163, 184, 0.2);
    }

    #detail-pemain-dashboard .stat-card-bronze {
        border-color: rgba(6, 182, 212, 0.4);
        background: linear-gradient(180deg, rgba(6, 182, 212, 0.12) 0%, rgba(0, 0, 0, 0.2) 100%);
        box-shadow: inset 0 1px 0 rgba(6, 182, 212, 0.2), 0 0 12px rgba(6, 182, 212, 0.1);
    }


    #detail-pemain-dashboard .stat-icon-wins { 
        font-size: 15px;
        margin-bottom: 4px;
        color: #fda635;

    }
    #detail-pemain-dashboard .stat-icon-losses {
        font-size: 15px;
        margin-bottom: 4px;
        color: #e64141;
    
    }
    #detail-pemain-dashboard .stat-icon-winrate {
        font-size: 15px;
        margin-bottom: 4px;
        color: #d6402c;
  
    }


    #detail-pemain-dashboard .stat-label {
        font-family: 'Press Start 2P', monospace;
        font-size: 6px;
        color: #94a3b8;
        margin-bottom: 4px;
        text-transform: uppercase;
    }

    #detail-pemain-dashboard .stat-value {
        font-size: 13px;
        font-weight: bold;
        font-family: 'Press Start 2P', monospace;
    }

    #detail-pemain-dashboard .stat-card-gold .stat-value { color: #f59e0b; text-shadow: 0 0 6px rgba(245, 158, 11, 0.4); }
    #detail-pemain-dashboard .stat-card-silver .stat-value { color: #e2e8f0; text-shadow: 0 0 6px rgba(148, 163, 184, 0.4); }
    #detail-pemain-dashboard .stat-card-bronze .stat-value { color: #22d3ee; text-shadow: 0 0 6px rgba(6, 182, 212, 0.4); }

    #detail-pemain-dashboard .preview-panel {
        width: 100%;
        background: rgba(0, 0, 0, 0.4);
        border: 1px solid rgba(59, 130, 246, 0.25);
        border-radius: 16px;
        padding: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-sizing: border-box;
    }

    #detail-pemain-dashboard .preview-panel-title {
        font-family: 'Press Start 2P', monospace;
        font-size: 7px;
        color: #38bdf8;
        margin-bottom: 6px;
        letter-spacing: 0.5px;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        text-transform: uppercase;
    }

    #detail-pemain-dashboard #jalur-preview-container {
        width: 250px;
        height: 200px;
        max-width: 100%;
        border-radius: 10px;
        overflow: hidden;
        background: rgba(0, 0, 0, 0.35);
        border: 1.5px solid rgba(59, 130, 246, 0.3);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #detail-pemain-dashboard #jalur-preview-container canvas {
        max-width: 100% !important;
        height: auto !important;
        object-fit: contain;
    }

    #detail-pemain-dashboard #jalur-name {
        font-family: 'Press Start 2P', monospace;
        font-size: 13px;
        font-weight: bold;
        color: #f59e0b;
        margin-top: 6px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
    }

    #detail-pemain-dashboard .section-header {
        width: calc(100% - 28px);
        margin: 4px auto 6px;
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        color: #ffffff;
        letter-spacing: 0.5px;
        text-align: left;
        text-shadow: 2px 2px 0px #000000;
        text-transform: uppercase;
    }

    #detail-pemain-dashboard .history-slider {
        width: calc(100% - 28px);
        margin: 0 auto 10px;
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 8px;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #3b82f6 rgba(15, 23, 42, 0.6);
        box-sizing: border-box;
        touch-action: pan-x pan-y !important;
    }

    #detail-pemain-dashboard .history-slider::-webkit-scrollbar {
        height: 5px;
    }

    #detail-pemain-dashboard .history-slider::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.3);
        border-radius: 4px;
    }

    #detail-pemain-dashboard .history-slider::-webkit-scrollbar-thumb {
        background: #3b82f6;
        border-radius: 4px;
    }

    #detail-pemain-dashboard .history-card {
        flex-shrink: 0;
        width: 136px;
        height: 86px;
        border-radius: 12px;
        background: rgba(15, 23, 42, 0.9);
        border: 1.5px solid rgba(255, 255, 255, 0.15);
        padding: 8px 10px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        scroll-snap-align: start;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        box-sizing: border-box;
        transition: all 0.2s ease;
    }

    #detail-pemain-dashboard .history-card:hover {
        border-color: #3b82f6;
        background: #1e293b;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.35);
    }

    #detail-pemain-dashboard .history-outcome-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    #detail-pemain-dashboard .outcome-badge {
        font-size: 8px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 6px;
        font-family: 'Press Start 2P', monospace;
        text-transform: uppercase;
    }

    #detail-pemain-dashboard .outcome-win {
        background: rgba(34, 197, 94, 0.2);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.4);
        text-shadow: 0 0 5px rgba(74, 222, 128, 0.4);
    }

    #detail-pemain-dashboard .outcome-loss {
        background: rgba(239, 68, 68, 0.2);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.4);
        text-shadow: 0 0 5px rgba(248, 113, 113, 0.4);
    }

    #detail-pemain-dashboard .history-room-code {
        font-family: 'Ubuntu', sans-serif !important;
        font-size: 6px;
        color: #64748b;
    }

    #detail-pemain-dashboard .history-opponent {
        font-size: 11px;
        font-weight: 600;
        color: #e2e8f0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin: 4px 0;
    }

    #detail-pemain-dashboard .history-time-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 8px;
        color: #64748b;
    }

    #detail-pemain-dashboard .history-date {
        font-size: 8px;
        font-family: 'Press Start 2P', monospace;
    }

    #detail-pemain-dashboard .history-mode {
        font-size: 7px;
        font-family: 'Press Start 2P', monospace;
        color: #64748b;
    }

    #detail-pemain-dashboard .history-empty {
        width: 100%;
        background: rgba(15, 23, 42, 0.85);
        border: 1.5px dashed rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        box-sizing: border-box;
    }

    #detail-pemain-dashboard .history-empty-title {
        font-family: 'Press Start 2P', monospace;
        font-size: 7px;
        color: #38bdf8;
        margin-bottom: 6px;
        text-shadow: 0 0 5px rgba(56, 189, 248, 0.3);
    }

    #detail-pemain-dashboard .history-empty-subtitle {
        font-size: 10px;
        color: rgba(255, 255, 255, 0.6);
    }
</style>

@php
$user = auth()->user();
$winsCount = $targetUser->wins()->count();
$lossesCount = $targetUser->losses()->count();
$statusText = 'ANAK BARU';
if ($winsCount >= 100) {
    $statusText = 'PAMACU INTI';
} elseif ($winsCount >= 50) {
    $statusText = 'PAMAIN SEWA';
}

// Fetch match history (riwayat permainan) for targetUser
$targetUserId = $targetUser->id;
$history = \App\Models\Room::where('status', 'finished')
    ->where(function ($query) use ($targetUserId) {
        $query->where('host_id', $targetUserId)
            ->orWhere('guest_id', $targetUserId);
    })
    ->with(['host', 'guest', 'winner'])
    ->orderBy('updated_at', 'desc')
    ->take(10)
    ->get();

// Custom Colors and skins for targetUser
$modelJalur = $targetUser->modelJalur;
$customColors = $modelJalur->model_jalur['customColors'] ?? [
    'boat' => '#8D6E63',
    'hair' => '#111827',
    'pants' => '#38a169',
    'shirt' => '#10B981',
    'paddle' => '#8D6E63',
    'splash' => '#a5f3fc',
];
$corakDataUrl = ($modelJalur && ($modelJalur->model_jalur['boat_unlocked'] ?? false)) ? ($modelJalur->model_jalur['corak_data_url'] ?? null) : null;
$lambaiDataUrl = ($modelJalur && ($modelJalur->model_jalur['lambai_unlocked'] ?? false)) ? ($modelJalur->model_jalur['lambai_data_url'] ?? null) : null;
@endphp

<div id="detail-pemain-dashboard">
    <!-- Top bar: Back and Coin display -->
    <div>
        <div class="top-bar">
            <div class="back-btn-container" onclick="goBack()">
                <img class="back-btn" src="{{ asset_v('game_pacu/assets/image/ui/back.png') }}" alt="Kembali">
            </div>
            <div class="coin-display" onclick="window.navigateToPage('/shop')">
                <span class="sprint-icon me-1"><img src="{{ asset_v('game_pacu/assets/image/ui/sprint.png') }}" alt="Sprint" style="width: 20px; height: 20px; object-fit: contain;"></span>
                <span class="coin-amount">{{ number_format($user->kuansing_poin, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Scrollable Area -->
    <div class="profile-container scrollable">

        <!-- Main Card -->
        <div class="ps5-card">

            <!-- Avatar overlapping cover -->
            <div class="profile-avatar-wrapper">
                <div class="profile-avatar-container">
                    @php
                    $dbFoto = $targetUser->foto_profile;
                    if (!empty($dbFoto)) {
                        if (strpos($dbFoto, 'http://') === 0 || strpos($dbFoto, 'https://') === 0) {
                            $profileImgSrc = $dbFoto;
                        } elseif (strpos($dbFoto, '/') !== false || strpos($dbFoto, '.gif') !== false) {
                            $profileImgSrc = (strpos($dbFoto, '/') === 0) ? $dbFoto : '/' . $dbFoto;
                        } else {
                            $profileImgSrc = 'game_pacu/assets/image/ui/' . $dbFoto . '.gif';
                        }
                    } else {
                        $profileImgSrc = 'game_pacu/assets/image/ui/profil.gif';
                    }
                    @endphp
                    <img src="{{ asset_v($profileImgSrc) }}" alt="profil" class="profile-avatar-img">
                </div>
                <div class="status-dot-pulse"></div>
            </div>

            <!-- Identity -->
            <div class="profile-name">{{ $targetUser->nama_jalur ?? $targetUser->email }}</div>
            <div class="profile-badge"><img src="{{ asset_v('game_pacu/assets/image/ui/sprint.png') }}" alt="Sprint" style="width: 16px; height: 16px; object-fit: contain; vertical-align: middle;" class="me-1"> {{ $statusText }}</div>

            <!-- Trophy Stats Grid -->
            <div class="stats-row">
                <div class="stat-card stat-card-gold">
                    <span class="stat-icon-wins"><i class="bi bi-trophy-fill text-warning"></i></span>
                    <span class="stat-label">Wins</span>
                    <span class="stat-value">{{ $winsCount }}</span>
                </div>
                <div class="stat-card stat-card-silver">
                    <span class="stat-icon-losses"><i class="bi bi-x-circle-fill text-danger"></i></span>
                    <span class="stat-label">Losses</span>
                    <span class="stat-value">{{ $lossesCount }}</span>
                </div>
                <div class="stat-card stat-card-bronze">
                    <span class="stat-icon-winrate"><i class="bi bi-fire text-danger"></i></span>
                    <span class="stat-label">Win Rate</span>
                    <span class="stat-value">
                        @php
                        $total = $winsCount + $lossesCount;
                        echo $total > 0 ? round(($winsCount / $total) * 100) . '%' : '0%';
                        @endphp
                    </span>
                </div>
            </div>

            <!-- Boat Preview Panel -->
            <div class="preview-panel">
                <div class="preview-panel-title">JALUR AKTIF</div>
                <div id="jalur-preview-container"></div>
                <div id="jalur-name">{{ $targetUser->nama_jalur ?? 'Jalur Pacu' }}</div>
            </div>
        </div>

        <!-- Game History Slider Section -->
        <div class="section-header"><i class="bi bi-clock-history me-1 text-info"></i> Riwayat Permainan</div>

        <div class="history-slider scrollable">
            @if ($history->isEmpty())
                <div class="history-empty">
                    <div class="history-empty-title">BELUM ADA RIWAYAT</div>
                    <div class="history-empty-subtitle">Pemain ini belum bertanding.</div>
                </div>
            @else
                @foreach ($history as $match)
                    @php
                    $isWinner = ($match->winner_id === $targetUser->id);
                    $opponent = ($match->host_id === $targetUser->id) ? $match->guest : $match->host;
                    $opponentName = $opponent ? ($opponent->nama_jalur ?? $opponent->email) : 'Lawan';
                    $formattedDate = $match->updated_at ? $match->updated_at->format('d/m H:i') : '';
                    @endphp
                    <div class="history-card">
                        <div class="history-outcome-row">
                            <span class="outcome-badge {{ $isWinner ? 'outcome-win' : 'outcome-loss' }}">
                                {{ $isWinner ? 'WIN' : 'LOSS' }}
                            </span>
                            <span class="history-room-code">#{{ $match->room_code }}</span>
                        </div>
                        <div class="history-opponent" title="vs {{ $opponentName }}">
                            vs {{ $opponentName }}
                        </div>
                        <div class="history-time-row">
                            <span class="history-date">{{ $formattedDate }}</span>
                            <span class="history-mode">{{ $match->name === 'Quick Match' ? 'QUICK' : 'ROOM' }}</span>
                        </div>
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
    const customColors = @json($customColors);
    const corakDataUrl = @json($corakDataUrl);
    const lambaiDataUrl = @json($lambaiDataUrl);

    window.goBack = function() {
        window.navigateToPage('/cari-pemain');
    };

    function initDetailPreview() {
        if (window.Phaser && document.getElementById('jalur-preview-container')) {
            initJalurPreviewCustom('jalur-preview-container');
        }
    }

    initDetailPreview();
    document.addEventListener('game:page-ready', function () {
        if (!document.getElementById('detail-pemain-dashboard')) return;
        initDetailPreview();
    });

    function initJalurPreviewCustom(containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        container.innerHTML = '';
        if (window.activePreviewGame) {
            try {
                window.activePreviewGame.destroy(true);
            } catch (e) {
                console.warn('Error destroying activePreviewGame:', e);
            }
            window.activePreviewGame = null;
        }

        window.activePreviewGame = new Phaser.Game({
            type: Phaser.AUTO,
            width: 250,
            height: 140,
            transparent: true,
            parent: containerId,
            pixelArt: true,
            scale: {
                mode: Phaser.Scale.FIT,
                autoCenter: Phaser.Scale.CENTER_BOTH
            },
            scene: {
                preload: function () {
                    const v = (typeof window !== 'undefined' && window.GAME_VERSION) ? `?v=${window.GAME_VERSION}` : '';
                    this.load.image('jalur_boat', `/game_pacu/assets/image/jalur/jalur.png${v}`);
                    for (let i = 1; i <= 5; i++) {
                        this.load.image(`char${i}`, `/game_pacu/assets/image/char/${i}.png${v}`);
                        this.load.image(`timbo${i}`, `/game_pacu/assets/image/timbo_ruang/${i}.png${v}`);
                        this.load.image(`tari${i}`, `/game_pacu/assets/image/tukang_tari/${i}.png${v}`);
                        this.load.image(`onjai${i}`, `/game_pacu/assets/image/tukang_onjai/${i}.png${v}`);
                    }
                },
                create: function () {
                    const scene = this;
                    const scaleMult = 0.8;
                    const BOAT_SCALE = 2.3 * scaleMult;
                    const ROWER_SCALE = 0.23 * scaleMult;
                    const TIMBO_SCALE = 0.25 * scaleMult;
                    const TARI_SCALE = 0.25 * scaleMult;
                    const ONJAI_SCALE = 0.25 * scaleMult;

                    const BOAT_OFFSET_X = 0;
                    const BOAT_OFFSET_Y = 15 * scaleMult;

                    const ROWER_OFFSET_X = -30 * scaleMult;
                    const ROWER_OFFSET_Y = -22 * scaleMult;

                    const TIMBO_OFFSET_X = -25 * scaleMult;
                    const TIMBO_OFFSET_Y = -47 * scaleMult;

                    const TARI_OFFSET_X = -37 * scaleMult;
                    const TARI_OFFSET_Y = -47 * scaleMult;

                    const ONJAI_OFFSET_X = -25 * scaleMult;
                    const ONJAI_OFFSET_Y = -47 * scaleMult;

                    const ROWER_SPACING = 35 * scaleMult;

                    const boatGroup = scene.add.container(125, 75);

                    // Recolor character image function
                    function recolorCharacterImage(sourceKey) {
                        const sourceTexture = scene.textures.get(sourceKey);
                        const sourceImage = sourceTexture.getSourceImage();
                        const canvas = document.createElement('canvas');
                        canvas.width = sourceImage.width;
                        canvas.height = sourceImage.height;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(sourceImage, 0, 0);

                        const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                        const data = imgData.data;

                        const targetHair = Phaser.Display.Color.HexStringToColor(customColors.hair);
                        const targetShirt = Phaser.Display.Color.HexStringToColor(customColors.shirt);
                        const targetPants = Phaser.Display.Color.HexStringToColor(customColors.pants);
                        const targetPaddle = Phaser.Display.Color.HexStringToColor(customColors.paddle);

                        for (let i = 0; i < data.length; i += 4) {
                            const r = data[i], g = data[i + 1], b = data[i + 2], a = data[i + 3];
                            if (a < 10) continue;
                            if (r < 40 && g < 40 && b < 40) continue;

                            if (r - g > 100 && r - b > 100) {
                                const factor = Math.min(1.2, r / 199);
                                data[i] = Math.min(255, targetHair.r * factor);
                                data[i + 1] = Math.min(255, targetHair.g * factor);
                                data[i + 2] = Math.min(255, targetHair.b * factor);
                            } else if (g - r > 50 && g - b > 40) {
                                const factor = Math.min(1.2, g / 122);
                                data[i] = Math.min(255, targetPants.r * factor);
                                data[i + 1] = Math.min(255, targetPants.g * factor);
                                data[i + 2] = Math.min(255, targetPants.b * factor);
                            } else if (b - r > 80 && b - g > 40) {
                                const factor = Math.min(1.2, b / 203);
                                data[i] = Math.min(255, targetPaddle.r * factor);
                                data[i + 1] = Math.min(255, targetPaddle.g * factor);
                                data[i + 2] = Math.min(255, targetPaddle.b * factor);
                            } else if (Math.abs(r - g) < 20 && Math.abs(g - b) < 20 && Math.abs(r - b) < 20) {
                                const factor = Math.min(1.2, ((r + g + b) / 3) / 78);
                                data[i] = Math.min(255, targetShirt.r * factor);
                                data[i + 1] = Math.min(255, targetShirt.g * factor);
                                data[i + 2] = Math.min(255, targetShirt.b * factor);
                            }
                        }
                        ctx.putImageData(imgData, 0, 0);
                        return canvas;
                    }

                    // Apply recolors to animations safely
                    const rowerSprites = [];
                    for (let f = 1; f <= 5; f++) {
                        ['char', 'timbo', 'tari', 'onjai'].forEach(prefix => {
                            const texKey = `recolored_${prefix}${f}`;
                            if (scene.textures.exists(texKey)) {
                                scene.textures.remove(texKey);
                            }
                            const canvas = recolorCharacterImage(`${prefix}${f}`);
                            scene.textures.addCanvas(texKey, canvas);
                        });
                    }

                    ['rowing', 'timbo', 'tari', 'onjai'].forEach(animType => {
                        const key = `${animType}_anim`;
                        const prefix = animType === 'rowing' ? 'char' : animType;
                        if (scene.anims.exists(key)) {
                            scene.anims.remove(key);
                        }
                        scene.anims.create({
                            key: key,
                            frames: [
                                { key: `recolored_${prefix}1` }, { key: `recolored_${prefix}2` },
                                { key: `recolored_${prefix}3` }, { key: `recolored_${prefix}4` },
                                { key: `recolored_${prefix}5` }
                            ],
                            frameRate: animType === 'tari' ? 1 : 8,
                            repeat: -1
                        });
                    });

                    const boatImg = scene.add.image(BOAT_OFFSET_X, BOAT_OFFSET_Y, 'jalur_boat');
                    boatImg.setScale(BOAT_SCALE);
                    boatImg.texture.setFilter(Phaser.Textures.FilterMode.NEAREST);
                    const boatColorInt = Phaser.Display.Color.HexStringToColor(customColors.boat).color;
                    boatImg.setTint(boatColorInt);
                    boatGroup.add(boatImg);

                    const offsetsX = [-2.5, -1.5, -0.5, 0.5, 1.5, 2.5, 3.5].map(m => m * ROWER_SPACING);

                    offsetsX.forEach((offsetX, idx) => {
                        const isTari = (idx === 0);
                        const isTimbo = (idx === 3);
                        const isOnjai = (idx === 6);

                        let finalScale = ROWER_SCALE;
                        let finalOffX = ROWER_OFFSET_X;
                        let finalOffY = ROWER_OFFSET_Y;
                        let animKey = 'rowing_anim';
                        let defaultTex = 'char1';

                        if (isTari) {
                            finalScale = TARI_SCALE;
                            finalOffX = TARI_OFFSET_X;
                            finalOffY = TARI_OFFSET_Y;
                            animKey = 'tari_anim';
                            defaultTex = 'tari1';
                        } else if (isTimbo) {
                            finalScale = TIMBO_SCALE;
                            finalOffX = TIMBO_OFFSET_X;
                            finalOffY = TIMBO_OFFSET_Y;
                            animKey = 'timbo_anim';
                            defaultTex = 'timbo1';
                        } else if (isOnjai) {
                            finalScale = ONJAI_SCALE;
                            finalOffX = ONJAI_OFFSET_X;
                            finalOffY = ONJAI_OFFSET_Y;
                            animKey = 'onjai_anim';
                            defaultTex = 'onjai1';
                        }

                        const rowerX = BOAT_OFFSET_X + finalOffX + offsetX;
                        const rowerY = BOAT_OFFSET_Y + finalOffY;
                        const rowerSprite = scene.add.sprite(rowerX, rowerY, defaultTex);
                        rowerSprite.setScale(finalScale);
                        rowerSprite.texture.setFilter(Phaser.Textures.FilterMode.NEAREST);
                        boatGroup.add(rowerSprite);
                        rowerSprites.push(rowerSprite);
                        rowerSprite.play(animKey);
                    });

                    // Apply Corak if unlocked
                    if (corakDataUrl) {
                        const img = new Image();
                        img.onload = () => {
                            if (!scene.sys || !scene.sys.isActive()) return;
                            const boatSource = scene.textures.get('jalur_boat').getSourceImage();
                            const CORAK_SCALE = BOAT_SCALE;
                            const displayW = Math.round(boatSource.width * CORAK_SCALE);
                            const displayH = Math.round(boatSource.height * CORAK_SCALE);

                            const maskCanvas = document.createElement('canvas');
                            maskCanvas.width = displayW; maskCanvas.height = displayH;
                            const ctx = maskCanvas.getContext('2d');

                            const scaleFactor = (displayW / img.width);
                            const drawW = displayW;
                            const drawH = Math.round(img.height * scaleFactor);
                            const drawX = 0;
                            const drawY = Math.round((displayH - drawH) / 2);

                            const isPixelArt = (img.width <= 256 && img.height <= 256);
                            ctx.imageSmoothingEnabled = !isPixelArt;
                            if (ctx.imageSmoothingEnabled) ctx.imageSmoothingQuality = 'high';
                            ctx.drawImage(img, drawX, drawY, drawW, drawH);

                            const imageData = ctx.getImageData(0, 0, displayW, displayH);
                            const data = imageData.data;
                            const WHITE_THRESHOLD = 240;
                            for (let i = 0; i < data.length; i += 4) {
                                const r = data[i], g = data[i + 1], b = data[i + 2];
                                if (r > WHITE_THRESHOLD && g > WHITE_THRESHOLD && b > WHITE_THRESHOLD) {
                                    const brightness = Math.min(r, g, b);
                                    const fade = (brightness - WHITE_THRESHOLD) / (255 - WHITE_THRESHOLD);
                                    data[i + 3] = Math.round(255 * (1 - fade));
                                }
                            }
                            ctx.putImageData(imageData, 0, 0);

                            ctx.imageSmoothingEnabled = false;
                            ctx.globalCompositeOperation = 'destination-in';
                            ctx.drawImage(boatSource, 0, 0, displayW, displayH);
                            ctx.globalCompositeOperation = 'source-over';
                            ctx.imageSmoothingEnabled = true;

                            if (scene.textures.exists('corak_texture')) scene.textures.remove('corak_texture');
                            scene.textures.addCanvas('corak_texture', maskCanvas);
                            const corakSprite = scene.make.image({ x: BOAT_OFFSET_X, y: BOAT_OFFSET_Y, key: 'corak_texture', add: false });
                            corakSprite.setScale(1.0);
                            corakSprite.setAlpha(0.82);
                            corakSprite.setBlendMode(Phaser.BlendModes.MULTIPLY);
                            boatGroup.add(corakSprite);
                            boatGroup.moveTo(corakSprite, boatGroup.getIndex(boatImg) + 1);
                        };
                        img.src = corakDataUrl;
                    }

                    // Apply Lambai if unlocked
                    if (lambaiDataUrl) {
                        const img = new Image();
                        img.onload = () => {
                            if (!scene.sys || !scene.sys.isActive()) return;
                            const LAMBAI_SCALE = 1.3 * scaleMult;
                            const LAMBAI_OFFSET_X = 125 * scaleMult;
                            const LAMBAI_OFFSET_Y = -18 * scaleMult;
                            const targetSize = 48;
                            let w = img.width, h = img.height;
                            if (w > h) { h = Math.round((h / w) * targetSize); w = targetSize; }
                            else { w = Math.round((w / h) * targetSize); h = targetSize; }

                            const canvas = document.createElement('canvas');
                            canvas.width = w; canvas.height = h;
                            const ctx = canvas.getContext('2d');
                            ctx.imageSmoothingEnabled = false;
                            ctx.drawImage(img, 0, 0, w, h);

                            const imageData = ctx.getImageData(0, 0, w, h);
                            const data = imageData.data;
                            const WHITE_THRESHOLD = 240;
                            for (let i = 0; i < data.length; i += 4) {
                                const r = data[i], g = data[i + 1], b = data[i + 2];
                                if (r > WHITE_THRESHOLD && g > WHITE_THRESHOLD && b > WHITE_THRESHOLD) {
                                    const brightness = Math.min(r, g, b);
                                    const fade = (brightness - WHITE_THRESHOLD) / (255 - WHITE_THRESHOLD);
                                    data[i + 3] = Math.round(255 * (1 - fade));
                                }
                            }
                            ctx.putImageData(imageData, 0, 0);

                            if (scene.textures.exists('lambai_texture')) scene.textures.remove('lambai_texture');
                            scene.textures.addCanvas('lambai_texture', canvas);
                            const lambaiSprite = scene.make.image({ x: LAMBAI_OFFSET_X, y: LAMBAI_OFFSET_Y, key: 'lambai_texture', add: false });
                            lambaiSprite.setScale(LAMBAI_SCALE);
                            lambaiSprite.texture.setFilter(Phaser.Textures.FilterMode.NEAREST);
                            boatGroup.add(lambaiSprite);
                            boatGroup.moveTo(lambaiSprite, boatGroup.getIndex(boatImg));

                            scene.tweens.add({
                                targets: lambaiSprite,
                                angle: { from: 0, to: 0 },
                                duration: 850,
                                yoyo: true,
                                repeat: -1,
                                ease: 'Sine.easeInOut'
                            });
                        };
                        img.src = lambaiDataUrl;
                    }

                    // Idle bobbing animation
                    scene.tweens.add({
                        targets: boatGroup,
                        y: boatGroup.y + 4 * scaleMult,
                        duration: 1200,
                        yoyo: true,
                        repeat: -1,
                        ease: 'Sine.easeInOut'
                    });
                }
            }
        });
    }

    // Cleanup Phaser preview instance on navigation
    document.addEventListener('livewire:navigating', () => {
        if (window.activePreviewGame) {
            window.activePreviewGame.destroy(true);
            window.activePreviewGame = null;
            console.log('Target user preview Phaser destroyed.');
        }
    }, { once: true });
}
</script>
@endpush
