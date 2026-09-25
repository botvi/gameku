@extends('template-admin.layout')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <!-- Header Page -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2 border-bottom">
            <div>
                <a href="{{ route('superadmin.tournaments') }}" class="btn btn-sm btn-outline-secondary mb-2">
                    <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar
                </a>
                <h3 class="h4 fw-bold text-slate-900 mb-1">
                    <i class="ti ti-trophy text-warning me-2"></i>{{ $tournament->title }}
                </h3>
                <p class="text-slate-600 mb-0" style="font-size: 13px;">
                    Kuota: <strong class="text-slate-900">{{ $tournament->max_participants }} Player</strong> | 
                    Hadiah: <strong class="text-warning"><i class="ti ti-coin me-1"></i> {{ number_format($tournament->prize_coins) }} Coins</strong> | 
                    Status: 
                    @if($tournament->status === 'draft')
                        <span class="badge bg-light-warning text-warning fw-bold">Draft</span>
                    @elseif($tournament->status === 'registration')
                        <span class="badge bg-light-info text-info fw-bold">Bagan Ready</span>
                    @elseif($tournament->status === 'active')
                        <span class="badge bg-light-success text-success fw-bold">BERLANGSUNG</span>
                    @elseif($tournament->status === 'completed')
                        <span class="badge bg-light-primary text-primary fw-bold">Selesai</span>
                    @endif
                </p>
            </div>

            <div class="d-flex gap-2 mt-3 mt-md-0">
                @if($tournament->status === 'draft' && $tournament->participants->count() === (int)$tournament->max_participants)
                    <form action="{{ route('superadmin.tournaments.generate-bracket', $tournament->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info fw-bold">
                            <i class="ti ti-sitemap me-1"></i> Generate Bracket System Gugur
                        </button>
                    </form>
                @endif

                @if($tournament->status === 'registration')
                    <form action="{{ route('superadmin.tournaments.start', $tournament->id) }}" method="POST" onsubmit="return confirm('Mulai turnamen sekarang? Laga babak 1 akan langsung diaktifkan dengan timer 3 menit.')">
                        @csrf
                        <button type="submit" class="btn btn-success fw-bold px-4">
                            <i class="ti ti-player-play me-1"></i> MULAI TURNAMEN!
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ti ti-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ti ti-alert-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Left: Select Participants -->
            <div class="col-md-5 mb-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header py-3 bg-white border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="mb-0 fw-bold text-slate-900"><i class="ti ti-users me-2"></i>Peserta Turnamen ({{ $tournament->participants->count() }}/{{ $tournament->max_participants }})</h6>
                    </div>
                    <div class="card-body">
                        @if($tournament->status === 'draft')
                            <form action="{{ route('superadmin.tournaments.add-participants', $tournament->id) }}" method="POST">
                                @csrf
                                <label class="form-label text-slate-900 fw-bold mb-2">Pilih Player Terdaftar (Centang {{ $tournament->max_participants }} Player):</label>
                                
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-white border-end-0"><i class="ti ti-search text-slate-500"></i></span>
                                    <input type="text" id="searchPlayerInput" class="form-control border-start-0" placeholder="Cari nama player / email..." onkeyup="filterPlayerList()">
                                </div>

                                <div class="list-group overflow-auto mb-3" style="max-height: 350px;">
                                    @php
                                        $selectedUserIds = $tournament->participants->pluck('user_id')->toArray();
                                    @endphp
                                    @foreach($users as $u)
                                        <label class="player-select-item list-group-item bg-white text-slate-900 border d-flex align-items-center gap-3">
                                            <input class="form-check-input flex-shrink-0" type="checkbox" name="user_ids[]" value="{{ $u->id }}" {{ in_array($u->id, $selectedUserIds) ? 'checked' : '' }}>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-slate-900">{{ $u->nama_jalur }}</div>
                                                <small class="text-muted">{{ $u->email }}</small>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti ti-device-floppy me-1"></i> Simpan Daftar Peserta
                                </button>
                            </form>
                        @else
                            <div class="list-group">
                                @foreach($tournament->participants as $p)
                                    <div class="list-group-item bg-white text-slate-900 border d-flex align-items-center justify-content-between py-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-secondary">#{{ $p->seed_number }}</span>
                                            <span class="fw-bold text-slate-900">{{ $p->user->nama_jalur }}</span>
                                        </div>
                                        @if($p->status === 'winner')
                                            <span class="badge bg-success"><i class="ti ti-trophy me-1"></i> JUARA 1</span>
                                        @elseif($p->status === 'eliminated')
                                            <span class="badge bg-danger">Gugur</span>
                                        @else
                                            <span class="badge bg-info">Aktif</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right: Bracket View -->
            <div class="col-md-7 mb-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header py-3 bg-white border-bottom">
                        <h6 class="mb-0 fw-bold text-slate-900"><i class="ti ti-sitemap me-2"></i>Bagan Match System Gugur</h6>
                    </div>
                    <div class="card-body bg-light" style="min-height: 400px;">
                        @if($tournament->matches->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="ti ti-sitemap-off f-40 d-block mb-2 text-slate-400"></i>
                                Bagan belum di-generate. Pilih peserta terlebih dahulu lalu klik <strong>Generate Bracket</strong>.
                            </div>
                        @else
                            <div class="d-flex flex-column gap-3">
                                @php
                                    $grouped = $tournament->matches->groupBy('round');
                                @endphp
                                @foreach($grouped as $roundNum => $matches)
                                    <div class="border rounded-3 p-3 bg-white shadow-sm">
                                        <h6 class="text-primary fw-bold mb-3">
                                            @if($roundNum == 1)
                                                BABAK 1 (Quarterfinal / Pre-Elimination)
                                            @elseif($roundNum == 2)
                                                BABAK SEMIFINAL
                                            @elseif($roundNum == 3)
                                                BABAK FINAL
                                            @else
                                                BABAK {{ $roundNum }}
                                            @endif
                                        </h6>
                                        <div class="row g-3">
                                            @foreach($matches as $m)
                                                <div class="col-md-6">
                                                    <div class="p-2 rounded border {{ $m->status === 'in_progress' ? 'border-success bg-light-success' : 'border-slate-200 bg-light' }}">
                                                        <div class="d-flex justify-content-between text-muted mb-1" style="font-size: 11px;">
                                                            <span class="fw-bold">Match #{{ $m->match_number }}</span>
                                                            @if($m->status === 'ready_check')
                                                                <span class="text-warning fw-bold"><i class="ti ti-clock me-1"></i>Ready Check (3 Min)</span>
                                                            @elseif($m->status === 'in_progress')
                                                                <span class="text-success fw-bold"><i class="ti ti-live-photo me-1"></i>LIVE</span>
                                                            @elseif($m->status === 'completed')
                                                                <span class="text-primary fw-bold">Selesai</span>
                                                            @elseif($m->status === 'forfeited')
                                                                <span class="text-danger fw-bold">WO / Forfeit</span>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                                                            <span class="{{ $m->winner_id == $m->player1_id && $m->winner_id ? 'text-success fw-bold' : 'text-slate-900' }}" style="font-size: 13px;">
                                                                {{ $m->player1 ? $m->player1->nama_jalur : 'TBD' }}
                                                            </span>
                                                            @if($m->ready_p1) <span class="badge bg-success" style="font-size: 9px;">SIAP</span> @endif
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center py-1">
                                                            <span class="{{ $m->winner_id == $m->player2_id && $m->winner_id ? 'text-success fw-bold' : 'text-slate-900' }}" style="font-size: 13px;">
                                                                {{ $m->player2 ? $m->player2->nama_jalur : 'TBD' }}
                                                            </span>
                                                            @if($m->ready_p2) <span class="badge bg-success" style="font-size: 9px;">SIAP</span> @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function filterPlayerList() {
    const query = document.getElementById('searchPlayerInput').value.toLowerCase().trim();
    const items = document.querySelectorAll('.player-select-item');
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(query)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>
@endsection
