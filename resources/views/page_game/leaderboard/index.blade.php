@extends('layouts.game')

@section('title', 'Franchise Game — Leaderboard')

@section('content')
<style>
    #leaderboard-dashboard {
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

    #leaderboard-dashboard #ps5-particles {
        display: none;
    }

    #leaderboard-dashboard .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        padding: 16px 20px 8px;
        z-index: 11;
        margin-top: 10px;
        box-sizing: border-box;
    }

    #leaderboard-dashboard .back-btn-container {
        width: 36px; height: 36px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    #leaderboard-dashboard .back-btn { width: 36px; height: 36px; }
    #leaderboard-dashboard .back-btn-container:hover { transform: scale(1.1); }

    #leaderboard-dashboard .coin-display {
        display: flex; align-items: center; gap: 6px;
        z-index: 15;
    }
    #leaderboard-dashboard .coin-icon-wrapper {
        position: relative;
        width: 36px; height: 36px;
        border-radius: 50%; overflow: hidden;
        display: flex; align-items: center; justify-content: center;
    }
    #leaderboard-dashboard .coin-icon-wrapper img { width: 100%; height: 100%; image-rendering: pixelated; }
    #leaderboard-dashboard .coin-amount {
        font-family: 'Pixelify Sans', monospace;
        font-size: 13px; font-weight: bold;
        color: #000000; line-height: 1;
        text-shadow: 1px 1px 0px #ffffff, -1px -1px 0px #ffffff,
                     1px -1px 0px #ffffff, -1px 1px 0px #ffffff;
    }

    #leaderboard-dashboard .lb-title-wrap {
        z-index: 11;
        padding: 0 14px 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }
    #leaderboard-dashboard .lb-main-title {
        font-family: 'Press Start 2P', monospace;
        font-size: 12px;
        color: #fbbf24;
        text-shadow: 0 0 12px rgba(251, 191, 36, 0.6), 2px 2px 0px #92400e;
        letter-spacing: 1px;
    }
    #leaderboard-dashboard .lb-subtitle {
        font-size: 11px;
        color: rgba(255,255,255,0.7);
    }

    #leaderboard-dashboard .filter-tabs {
        display: flex;
        gap: 8px;
        z-index: 11;
        padding: 0 14px 10px;
        justify-content: center;
    }
    #leaderboard-dashboard .filter-tab {
        background: rgba(15, 23, 42, 0.85);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 10px;
        padding: 7px 14px;
        font-family: 'Press Start 2P', monospace;
        font-size: 6px;
        color: rgba(255,255,255,0.7);
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        white-space: nowrap;
    }
    #leaderboard-dashboard .filter-tab:hover {
        border-color: rgba(251, 191, 36, 0.4);
        color: #fbbf24;
    }
    #leaderboard-dashboard .filter-tab.active {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border-color: #f59e0b;
        color: #0c111d;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }

    #leaderboard-dashboard .my-rank-banner {
        z-index: 11;
        margin: 0 14px 10px;
        background: rgba(15, 23, 42, 0.88);
        border: 1px solid rgba(251, 191, 36, 0.4);
        border-radius: 14px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 12px rgba(0,0,0,0.4), inset 0 1px 0 rgba(251,191,36,0.1);
    }
    #leaderboard-dashboard .my-rank-left {
        display: flex; align-items: center; gap: 10px;
    }
    #leaderboard-dashboard .my-rank-badge {
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        color: #fbbf24;
        background: rgba(251,191,36,0.15);
        border-radius: 8px;
        padding: 5px 8px;
    }
    #leaderboard-dashboard .my-rank-name {
        font-family: 'Press Start 2P', monospace;
        font-size: 7px;
        color: #ffffff;
    }
    #leaderboard-dashboard .my-rank-info {
        display: flex; gap: 12px;
        font-size: 10px;
    }
    #leaderboard-dashboard .my-rank-stat { display: flex; flex-direction: column; align-items: flex-end; gap: 1px; }
    #leaderboard-dashboard .my-rank-stat-val { font-weight: bold; color: #fbbf24; }
    #leaderboard-dashboard .my-rank-stat-lbl { color: rgba(255, 255, 255, 0.6); font-size: 9px; }

    #leaderboard-dashboard .lb-scroll {
        flex: 1;
        overflow-y: auto;
        z-index: 11;
        padding: 0 14px;
        scrollbar-width: thin;
        scrollbar-color: rgba(251, 191, 36, 0.3) rgba(255,255,255,0.02);
    }
    #leaderboard-dashboard .lb-scroll::-webkit-scrollbar { width: 4px; }
    #leaderboard-dashboard .lb-scroll::-webkit-scrollbar-thumb {
        background: rgba(251, 191, 36, 0.3);
        border-radius: 4px;
    }

    #leaderboard-dashboard .rank-header {
        display: flex;
        align-items: center;
        padding: 0 12px 6px;
        font-family: 'Press Start 2P', monospace;
        font-size: 6px;
        color: rgba(255, 255, 255, 0.6);
        letter-spacing: 0.5px;
    }
    #leaderboard-dashboard .rh-rank { width: 32px; }
    #leaderboard-dashboard .rh-player { flex: 1; }
    #leaderboard-dashboard .rh-wins { width: 48px; text-align: center; }
    #leaderboard-dashboard .rh-losses { width: 48px; text-align: center; }
    #leaderboard-dashboard .rh-winrate { width: 52px; text-align: right; }

    #leaderboard-dashboard .lb-row {
        display: flex;
        align-items: center;
        background: rgba(15, 23, 42, 0.88);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 14px;
        padding: 10px 12px;
        margin-bottom: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 3px 10px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.04);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        cursor: pointer;
    }
    #leaderboard-dashboard .lb-row:hover {
        border-color: rgba(251, 191, 36, 0.5);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.5), 0 0 14px rgba(251,191,36,0.15);
    }
    #leaderboard-dashboard .lb-row.is-me {
        border-color: rgba(251, 191, 36, 0.6);
        background: rgba(251, 191, 36, 0.12);
    }
    #leaderboard-dashboard .lb-row.top1 { border-color: rgba(255, 215, 0, 0.65); background: rgba(255,215,0,0.12); }
    #leaderboard-dashboard .lb-row.top2 { border-color: rgba(192, 192, 192, 0.6); background: rgba(192,192,192,0.1); }
    #leaderboard-dashboard .lb-row.top3 { border-color: rgba(205, 127, 50, 0.6); background: rgba(205,127,50,0.1); }

    #leaderboard-dashboard .lb-rank {
        width: 32px;
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        color: #ffffff;
        flex-shrink: 0;
    }
    #leaderboard-dashboard .lb-rank.r1 { color: #FFD700; text-shadow: 0 0 8px rgba(255,215,0,0.5); }
    #leaderboard-dashboard .lb-rank.r2 { color: #C0C0C0; text-shadow: 0 0 6px rgba(192,192,192,0.4); }
    #leaderboard-dashboard .lb-rank.r3 { color: #CD7F32; text-shadow: 0 0 6px rgba(205,127,50,0.4); }

    #leaderboard-dashboard .lb-avatar-wrap {
        width: 36px; height: 36px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.12);
        overflow: hidden;
        flex-shrink: 0;
        margin-right: 10px;
        background: #0f172a;
    }
    #leaderboard-dashboard .lb-row.top1 .lb-avatar-wrap { border-color: #FFD700; box-shadow: 0 0 8px rgba(255,215,0,0.4); }
    #leaderboard-dashboard .lb-row.top2 .lb-avatar-wrap { border-color: #C0C0C0; }
    #leaderboard-dashboard .lb-row.top3 .lb-avatar-wrap { border-color: #CD7F32; }
    #leaderboard-dashboard .lb-row.is-me .lb-avatar-wrap { border-color: #fbbf24; }
    #leaderboard-dashboard .lb-avatar-wrap img { width: 100%; height: 100%; object-fit: cover; }

    #leaderboard-dashboard .lb-info { flex: 1; min-width: 0; }
    #leaderboard-dashboard .lb-name {
        font-family: 'Press Start 2P', monospace;
        font-size: 7px;
        color: #ffffff;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        max-width: 110px;
    }
    #leaderboard-dashboard .lb-badge-me {
        font-size: 9px;
        color: #fbbf24;
        display: inline-block;
        margin-left: 4px;
    }
    #leaderboard-dashboard .lb-totalmatch {
        font-size: 9px;
        color: rgba(255,255,255,0.5);
        margin-top: 2px;
    }

    /* Stats */
    .lb-wins {
        width: 48px;
        text-align: center;
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        color: #4ade80;
        flex-shrink: 0;
    }
    .lb-losses {
        width: 48px;
        text-align: center;
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        color: #f87171;
        flex-shrink: 0;
    }
    .lb-winrate {
        width: 52px;
        text-align: right;
        font-size: 10px;
        font-weight: bold;
        flex-shrink: 0;
    }
    .lb-winrate span {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Empty */
    .lb-empty {
        text-align: center;
        padding: 40px 20px;
        color: rgba(0,0,0,0.3);
    }
    .lb-empty-icon { font-size: 30px; margin-bottom: 10px; }
    .lb-empty-title { font-family: 'Press Start 2P', monospace; font-size: 8px; color: #475569; }

    /* Crown icons for top 3 */
    .crown { font-size: 11px; }
</style>

@php
$user = auth()->user();
@endphp

<div id="leaderboard-dashboard">
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

    <!-- Title -->
    <div class="lb-title-wrap">
        <div class="lb-main-title"><i class="bi bi-trophy-fill me-1 text-warning"></i> LEADERBOARD</div>
        <div class="lb-subtitle">Peringkat Pamacu Terbaik</div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="/leaderboard?filter=wins" wire:navigate
           class="filter-tab {{ $filter === 'wins' ? 'active' : '' }}">
            <i class="bi bi-trophy-fill me-1 text-warning"></i> WINS
        </a>
        <a href="/leaderboard?filter=losses" wire:navigate
           class="filter-tab {{ $filter === 'losses' ? 'active' : '' }}">
            <i class="bi bi-x-circle-fill me-1 text-danger"></i> LOSSES
        </a>
        <a href="/leaderboard?filter=winrate" wire:navigate
           class="filter-tab {{ $filter === 'winrate' ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow me-1 text-info"></i> WIN RATE
        </a>
    </div>

    <!-- My Rank Banner -->
    @php
    $myUser = $currentUser;
    $myWins = $myUser->wins_count ?? $myUser->wins()->count();
    $myLosses = $myUser->losses_count ?? $myUser->losses()->count();
    $myTotal = $myWins + $myLosses;
    $myWr = $myTotal > 0 ? round(($myWins / $myTotal) * 100, 1) : 0;

    $dbAvatarMe = $myUser->foto_profile;
    if (!empty($dbAvatarMe)) {
        if (strpos($dbAvatarMe, 'http://') === 0 || strpos($dbAvatarMe, 'https://') === 0) {
            $myAvatar = $dbAvatarMe;
        } elseif (strpos($dbAvatarMe, '/') !== false || strpos($dbAvatarMe, '.gif') !== false) {
            $myAvatar = (strpos($dbAvatarMe, '/') === 0) ? $dbAvatarMe : '/' . $dbAvatarMe;
        } else {
            $myAvatar = '/game_pacu/assets/image/ui/' . $dbAvatarMe . '.gif';
        }
    } else {
        $myAvatar = '/game_pacu/assets/image/ui/profil.gif';
    }
    @endphp
    <div class="my-rank-banner">
        <div class="my-rank-left">
            <div class="my-rank-badge">
                #{{ $myRank ?? '—' }}
            </div>
            <div>
                <div class="my-rank-name">{{ $myUser->nama_jalur ?? $myUser->email }}</div>
                <div style="font-size:9px;color:rgba(0, 0, 0, 0);margin-top:2px;">Kamu</div>
            </div>
        </div>
        <div class="my-rank-info">
            <div class="my-rank-stat">
                <span class="my-rank-stat-val" style="color:#4ade80">{{ $myWins }}</span>
                <span class="my-rank-stat-lbl">Wins</span>
            </div>
            <div class="my-rank-stat">
                <span class="my-rank-stat-val" style="color:#f87171">{{ $myLosses }}</span>
                <span class="my-rank-stat-lbl">Losses</span>
            </div>
            <div class="my-rank-stat">
                <span class="my-rank-stat-val" style="color:#fbbf24">{{ $myWr }}%</span>
                <span class="my-rank-stat-lbl">WR</span>
            </div>
        </div>
    </div>

    <!-- Scrollable List -->
    <div class="lb-scroll">
        <!-- Header Labels -->
        <div class="rank-header">
            <span class="rh-rank">#</span>
            <span class="rh-player">PLAYER</span>
            <span class="rh-wins">W</span>
            <span class="rh-losses">L</span>
            <span class="rh-winrate">WR%</span>
        </div>

        @if ($leaderboard->isEmpty())
            <div class="lb-empty">
                <div class="lb-empty-icon"><i class="bi bi-flag-fill text-muted" style="font-size: 28px;"></i></div>
                <div class="lb-empty-title">BELUM ADA DATA</div>
            </div>
        @else
            @foreach ($leaderboard as $idx => $player)
                @php
                $rank = $idx + 1;
                $isMe = $player->id === $currentUser->id;

                // Row class
                $rowClass = 'lb-row';
                if ($rank === 1) $rowClass .= ' top1';
                elseif ($rank === 2) $rowClass .= ' top2';
                elseif ($rank === 3) $rowClass .= ' top3';
                if ($isMe) $rowClass .= ' is-me';

                // Rank label
                $rankClass = 'lb-rank';
                if ($rank === 1) $rankClass .= ' r1';
                elseif ($rank === 2) $rankClass .= ' r2';
                elseif ($rank === 3) $rankClass .= ' r3';

                // Crown / Rank icon HTML
                $rankLabel = '#' . $rank;
                if ($rank === 1) $rankLabel = '<i class="bi bi-award-fill text-warning"></i>';
                elseif ($rank === 2) $rankLabel = '<i class="bi bi-award-fill text-secondary"></i>';
                elseif ($rank === 3) $rankLabel = '<i class="bi bi-award-fill" style="color:#b45309;"></i>';

                // Avatar
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

                $winrate = $player->winrate ?? 0;
                @endphp
                <div class="{{ $rowClass }}"
                     onclick="viewDetail({{ $player->id }})">
                    <!-- Rank -->
                    <div class="{{ $rankClass }}">{!! $rankLabel !!}</div>

                    <!-- Avatar -->
                    <div class="lb-avatar-wrap">
                        <img src="{{ $avatarUrl }}" alt="Avatar"
                             onerror="this.src='/game_pacu/assets/image/ui/profil.gif'">
                    </div>

                    <!-- Name + total -->
                    <div class="lb-info">
                        <div class="lb-name">
                            {{ $player->nama_jalur ?? $player->email }}
                            @if ($isMe)
                                <span class="lb-badge-me"><i class="bi bi-star-fill text-warning"></i></span>
                            @endif
                        </div>
                        <div class="lb-totalmatch">{{ $player->total_matches }} match</div>
                    </div>

                    <!-- Stats -->
                    <div class="lb-wins">{{ $player->wins_count }}</div>
                    <div class="lb-losses">{{ $player->losses_count }}</div>
                    <div class="lb-winrate"><span>{{ $winrate }}%</span></div>
                </div>
            @endforeach
        @endif
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
