@extends('layouts.game')

@section('title', 'Bagan Turnamen - ' . $tournament->title)

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
        color: #d8b4fe;
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
    .bracket-scroll-container {
        width: 95%;
        max-width: 950px;
        flex: 1;
        overflow-y: auto;
        padding-bottom: 30px;
        box-sizing: border-box;
        z-index: 12;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .bracket-scroll-container::-webkit-scrollbar { width: 4px; }
    .bracket-scroll-container::-webkit-scrollbar-thumb { background: rgba(168, 85, 247, 0.5); border-radius: 4px; }

    /* Winner Card */
    .winner-card {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.9) 0%, rgba(180, 83, 9, 0.9) 100%);
        border: 2px solid #fef08a;
        border-radius: 14px;
        padding: 14px 20px;
        text-align: center;
        box-shadow: 0 8px 24px rgba(245, 158, 11, 0.4);
    }
    .winner-card-title {
        font-size: 11px;
        color: #fef08a;
        text-shadow: 2px 2px 0 #78350f;
        margin-bottom: 4px;
    }
    .winner-card-name {
        font-size: 13px;
        color: #ffffff;
        text-shadow: 2px 2px 0 #78350f;
        margin-bottom: 4px;
    }

    /* Ready Check Card */
    .ready-check-card {
        background: rgba(10, 18, 36, 0.92);
        border: 2px solid #22c55e;
        border-radius: 14px;
        padding: 14px;
        text-align: center;
        box-shadow: 0 0 20px rgba(34, 197, 94, 0.4);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
    }

    .pixel-btn-green {
        background: linear-gradient(180deg, #4ade80 0%, #16a34a 100%);
        border: 2px solid #14532d;
        border-radius: 8px;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.35), 0 4px 0 #14532d;
        color: white;
        font-family: 'Press Start 2P', monospace;
        font-size: 9px;
        padding: 10px 20px;
        cursor: pointer;
        text-transform: uppercase;
        margin-top: 4px;
        transition: all 0.12s ease;
    }
    .pixel-btn-green:hover { background: #86efac; }
    .pixel-btn-green:active { transform: translateY(3px); box-shadow: 0 1px 0 #14532d; }

    /* Single Elimination Bracket Tree */
    .bracket-tree-box {
        background: rgba(10, 18, 36, 0.88);
        border: 2px solid rgba(168, 85, 247, 0.4);
        border-radius: 16px;
        padding: 16px 12px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.6);
        overflow-x: auto;
    }

    .bracket-tree {
        display: flex;
        gap: 16px;
        min-width: 650px;
    }

    .bracket-round {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        gap: 12px;
        min-width: 200px;
    }

    .round-header {
        font-size: 7px;
        color: #fbbf24;
        text-align: center;
        padding: 6px;
        background: rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(251, 191, 36, 0.3);
        border-radius: 6px;
        margin-bottom: 8px;
    }

    .match-card {
        background: rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 8px;
        padding: 8px 10px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .match-card.is-active-check { border-color: #fbbf24; box-shadow: 0 0 10px rgba(251, 191, 36, 0.4); }
    .match-card.is-active-live { border-color: #ef4444; box-shadow: 0 0 10px rgba(239, 68, 68, 0.4); }

    .match-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 6px;
        color: rgba(255,255,255,0.5);
        border-bottom: 1px dashed rgba(255,255,255,0.1);
        padding-bottom: 4px;
    }

    .player-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 5px 8px;
        border-radius: 5px;
        background: rgba(15, 23, 42, 0.8);
        font-size: 7px;
        color: rgba(255,255,255,0.8);
    }
    .player-row.is-winner {
        background: rgba(34, 197, 94, 0.2);
        border: 1px solid #22c55e;
        color: #4ade80;
    }
    .player-row.is-me { color: #38bdf8; }

    .tag-ready {
        font-size: 5px;
        color: #22c55e;
        background: rgba(34, 197, 94, 0.15);
        padding: 2px 4px;
        border-radius: 3px;
    }

    .pixel-btn-spectate {
        display: block;
        text-align: center;
        background: #ef4444;
        border: 1px solid #991b1b;
        color: #ffffff;
        font-size: 6px;
        padding: 6px;
        border-radius: 5px;
        text-decoration: none;
        margin-top: 4px;
        box-shadow: 0 2px 0 #991b1b;
    }
    .pixel-btn-spectate:hover { background: #dc2626; }
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

    <div class="title-banner">BAGAN TURNAMEN GUGUR</div>
    <div class="title-sub">{{ $tournament->title }}</div>

    <div class="bracket-scroll-container">
        @if($tournament->status === 'completed' && $tournament->winner)
            <div class="winner-card">
                <div class="winner-card-title">JUARA TURNAMEN</div>
                <div class="winner-card-name">{{ $tournament->winner->nama_jalur }}</div>
                <div style="font-size: 7px; color: #fef08a; display: flex; align-items: center; justify-content: center; gap: 4px; margin-top: 4px;">
                    PRIZE POOL: 
                    <img src="{{ asset_v('game_pacu/assets/image/ui/koin.png') }}" style="width: 12px; height: 12px; image-rendering: pixelated;" alt="Koin">
                    <span>{{ number_format($tournament->prize_coins) }} COINS</span>
                </div>
            </div>
        @endif

        @if($activeMatch)
            <div class="ready-check-card">
                <div style="font-size: 9px; color: #4ade80;">GILIRAN ANDA BERTANDING!</div>
                <div style="font-size: 7px; color: rgba(255,255,255,0.8);">
                    BATAS WAKTU SIAP: <span id="ready-timer-display" style="color: #fbbf24;">03:00</span>
                </div>
                <button class="pixel-btn-green" id="btn-ready-match" onclick="submitMatchReady({{ $activeMatch->id }})">
                    SIAP BERTANDING
                </button>
            </div>
        @endif

        <div class="bracket-tree-box">
            <div class="bracket-tree">
                @foreach($matchesByRound as $roundNum => $matches)
                    <div class="bracket-round">
                        <div class="round-header">
                            @if($roundNum == 1)
                                PEREMPAT FINAL
                            @elseif($roundNum == 2)
                                SEMI FINAL
                            @elseif($roundNum == 3)
                                BABAK FINAL
                            @else
                                BABAK {{ $roundNum }}
                            @endif
                        </div>

                        @foreach($matches as $m)
                            <div class="match-card {{ $m->status === 'ready_check' ? 'is-active-check' : '' }} {{ $m->status === 'in_progress' ? 'is-active-live' : '' }}">
                                <div class="match-card-header">
                                    <span>MATCH #{{ $m->match_number }}</span>
                                    @if($m->status === 'ready_check')
                                        <span style="color: #fbbf24;">READY CHECK</span>
                                    @elseif($m->status === 'in_progress')
                                        <span style="color: #ef4444;">LIVE</span>
                                    @elseif($m->status === 'completed')
                                        <span style="color: #22c55e;">SELESAI</span>
                                    @elseif($m->status === 'forfeited')
                                        <span style="color: #ef4444;">WO / FORFEIT</span>
                                    @else
                                        <span>MENUNGGU</span>
                                    @endif
                                </div>

                                <!-- Player 1 -->
                                <div class="player-row {{ $m->winner_id == $m->player1_id && $m->winner_id ? 'is-winner' : '' }} {{ $m->player1_id == auth()->id() ? 'is-me' : '' }}">
                                    <span>
                                        {{ $m->player1 ? Str::limit($m->player1->nama_jalur, 12) : 'TBD' }}
                                    </span>
                                    @if($m->ready_p1) <span class="tag-ready">SIAP</span> @endif
                                </div>

                                <!-- Player 2 -->
                                <div class="player-row {{ $m->winner_id == $m->player2_id && $m->winner_id ? 'is-winner' : '' }} {{ $m->player2_id == auth()->id() ? 'is-me' : '' }}">
                                    <span>
                                        {{ $m->player2 ? Str::limit($m->player2->nama_jalur, 12) : 'TBD' }}
                                    </span>
                                    @if($m->ready_p2) <span class="tag-ready">SIAP</span> @endif
                                </div>

                                @if($m->status === 'in_progress')
                                    <a href="{{ route('arena-pacu', ['tournament_match_id' => $m->id, 'mode' => 'spectator']) }}" class="pixel-btn-spectate">
                                        TONTON LIVE PREVIEW
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
{
    @if($activeMatch && $activeMatch->ready_deadline)
        let deadline = new Date("{{ $activeMatch->ready_deadline->toIso8601String() }}").getTime();
        let timerInterval = setInterval(function() {
            let now = new Date().getTime();
            let distance = deadline - now;

            if (distance < 0) {
                clearInterval(timerInterval);
                document.getElementById('ready-timer-display').innerHTML = "WAKTU HABIS!";
                window.location.reload();
            } else {
                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                document.getElementById('ready-timer-display').innerHTML = 
                    String(minutes).padStart(2, '0') + ":" + String(seconds).padStart(2, '0');
            }
        }, 1000);
    @endif

    window.submitMatchReady = function(matchId) {
        const btn = document.getElementById('btn-ready-match');
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'MEMPROSES...';
        }

        fetch('/tournament/match/' + matchId + '/ready', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (data.status === 'start' && data.redirect_url) {
                    window.navigateToPage(data.redirect_url);
                } else {
                    if (btn) btn.innerText = 'MENUNGGU LAWAN...';
                    setTimeout(() => window.location.reload(), 2000);
                }
            } else {
                alert(data.message || 'Gagal memproses status siap.');
                if (btn) btn.disabled = false;
            }
        })
        .catch(err => {
            console.error(err);
            if (btn) btn.disabled = false;
        });
    };
}
</script>
@endpush
@endsection
