@extends('template-admin.layout')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- Header Title & Subtitle -->
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2 border-bottom gap-2">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h3 class="h4 fw-bold text-slate-900 mb-0 font-heading">Dashboard Super Admin</h3>
                        <span class="shadcn-badge shadcn-badge-success">
                            <i class="ti ti-circle-check-filled"></i> Online
                        </span>
                    </div>
                    <p class="text-muted text-sm mb-0">Selamat datang di Panel Manajemen Game <strong>Pacu Jalur: The Pixel Race</strong>.</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('main-menu') }}" target="_blank" class="btn-shadcn-success" style="text-decoration: none;">
                        <i class="ti ti-device-gamepad-2 me-1"></i> Mainkan Game ↗
                    </a>
                </div>
            </div>

            <!-- ===== 4 STAT CARDS ===== -->
            <div class="row g-3 mb-4">
                <!-- Stat Card 1: Total Player -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card p-3 h-100 position-relative overflow-hidden">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-xs font-semibold text-uppercase tracking-wider text-muted">Total Player</span>
                            <div class="p-2.5 rounded-3 bg-slate-100 text-slate-700">
                                <i class="ti ti-users f-22"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-slate-900 mb-1 font-heading">{{ number_format($totalUsers) }}</h3>
                        <span class="text-xs text-muted">Player terdaftar di database</span>
                    </div>
                </div>

                <!-- Stat Card 2: Pendapatan QRIS -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card p-3 h-100 position-relative overflow-hidden">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-xs font-semibold text-uppercase tracking-wider text-muted">Pendapatan QRIS</span>
                            <div class="p-2.5 rounded-3" style="background: #ecfdf5; color: #059669;">
                                <i class="ti ti-wallet f-22"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-slate-900 mb-1 font-heading">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        <span class="text-xs text-muted">Total transaksi <span class="badge bg-success text-white" style="font-size: 10px;">SUCCESS</span></span>
                    </div>
                </div>

                <!-- Stat Card 3: Koin Terjual -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card p-3 h-100 position-relative overflow-hidden">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-xs font-semibold text-uppercase tracking-wider text-muted">Koin Terjual (KP)</span>
                            <div class="p-2.5 rounded-3" style="background: #fefce8; color: #d97706;">
                                <i class="ti ti-coin f-22"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-slate-900 mb-1 font-heading">{{ number_format($totalCoinsSold) }} <span class="fs-6 text-muted font-normal">KP</span></h3>
                        <span class="text-xs text-muted">Kuansing Poin dibeli player</span>
                    </div>
                </div>

                <!-- Stat Card 4: Player Diblokir -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card p-3 h-100 position-relative overflow-hidden">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-xs font-semibold text-uppercase tracking-wider text-muted">Player Diblokir</span>
                            <div class="p-2.5 rounded-3" style="background: #fef2f2; color: #e11d48;">
                                <i class="ti ti-user-off f-22"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-slate-900 mb-1 font-heading">{{ number_format($blockedUsers) }}</h3>
                        <span class="text-xs text-muted">Status akun terblokir</span>
                    </div>
                </div>
            </div>

            <!-- ===== TABLES & LEADERBOARD ===== -->
            <div class="row g-4">
                <!-- Recent Orders / Transactions -->
                <div class="col-12 col-lg-8">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between py-3">
                            <h6 class="mb-0 fw-bold text-slate-900 d-flex align-items-center gap-2">
                                <i class="ti ti-receipt text-emerald-600" style="color: #059669;"></i> Transaksi QRIS Terbaru
                            </h6>
                            <a href="{{ route('superadmin.transactions') }}" class="btn-shadcn-outline" style="padding: 4px 10px !important; font-size: 11.5px !important; text-decoration: none;">
                                Lihat Semua ↗
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">ORDER ID</th>
                                            <th>PLAYER</th>
                                            <th>NOMINAL</th>
                                            <th>KOIN (KP)</th>
                                            <th>STATUS</th>
                                            <th class="pe-4 text-end">TANGGAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentTransactions as $tx)
                                            <tr>
                                                <td class="ps-4 fw-mono text-xs font-semibold text-slate-900">{{ $tx->order_id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $tx->user->foto_profile ?? asset('admin/assets/images/user/avatar-2.jpg') }}" alt="User" class="rounded-circle me-2 border" style="width: 34px; height: 34px; object-fit: cover;">
                                                        <div>
                                                            <div class="fw-semibold text-slate-900" style="font-size: 13px;">{{ $tx->user->nama_jalur ?? 'No Name' }}</div>
                                                            <div class="text-muted" style="font-size: 11px;">{{ $tx->user->email }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="fw-bold text-slate-900">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                                                <td>
                                                    <span class="shadcn-badge shadcn-badge-coin">+{{ number_format($tx->coin_amount) }} KP</span>
                                                </td>
                                                <td>
                                                    @if($tx->status == 'SUCCESS')
                                                        <span class="shadcn-badge shadcn-badge-success">SUCCESS</span>
                                                    @elseif($tx->status == 'PENDING')
                                                        <span class="shadcn-badge shadcn-badge-warning">PENDING</span>
                                                    @else
                                                        <span class="shadcn-badge shadcn-badge-danger">EXPIRED</span>
                                                    @endif
                                                </td>
                                                <td class="pe-4 text-end text-muted" style="font-size: 12px;">{{ $tx->created_at->format('d M Y H:i') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">Belum ada transaksi pembayaran.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Leaderboard Coins / Top Players -->
                <div class="col-12 col-lg-4">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between py-3">
                            <h6 class="mb-0 fw-bold text-slate-900 d-flex align-items-center gap-2">
                                <i class="ti ti-trophy text-amber-500" style="color: #f59e0b;"></i> Leaderboard Player
                            </h6>
                            <a href="{{ route('superadmin.users') }}" class="btn-shadcn-outline" style="padding: 4px 10px !important; font-size: 11.5px !important; text-decoration: none;">
                                Detail ↗
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse($topPlayers as $index => $player)
                                    <div class="list-group-item d-flex align-items-center justify-content-between border-0 py-3 px-3" style="border-bottom: 1px solid #f1f5f9 !important;">
                                        <div class="d-flex align-items-center overflow-hidden">
                                            <span class="fw-bold me-2 text-center" style="width: 24px; font-size: 12px; color: {{ $index == 0 ? '#eab308' : ($index == 1 ? '#94a3b8' : ($index == 2 ? '#b45309' : '#cbd5e1')) }};">
                                                #{{ $index + 1 }}
                                            </span>
                                            <img src="{{ $player->foto_profile ?? asset('admin/assets/images/user/avatar-2.jpg') }}" alt="User" class="rounded-circle me-2.5 border flex-shrink-0" style="width: 36px; height: 36px; object-fit: cover;">
                                            <div class="overflow-hidden">
                                                <h6 class="mb-0 text-slate-900 fw-semibold text-truncate" style="font-size: 13px;">{{ $player->nama_jalur ?? 'No Name' }}</h6>
                                                <span class="text-muted text-truncate d-block" style="font-size: 11px;">{{ $player->email }}</span>
                                            </div>
                                        </div>
                                        <span class="shadcn-badge shadcn-badge-coin flex-shrink-0">
                                            🪙 {{ number_format($player->kuansing_poin) }} KP
                                        </span>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-muted">Belum ada data player.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
@endsection
