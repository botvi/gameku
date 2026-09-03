@extends('template-admin.layout')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- Header Title & Subtitle -->
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2 border-bottom">
                <div>
                    <h3 class="h4 fw-bold text-slate-900 mb-1">Dashboard Super Admin</h3>
                    <p class="text-muted text-sm mb-0">Ringkasan performa player, pendapatan QRIS, dan transaksi koin game.</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <span class="shadcn-badge shadcn-badge-success">
                        <i class="ti ti-circle-check-filled me-1"></i> System Online
                    </span>
                </div>
            </div>

            <!-- [ Main Content ] start -->
            <div class="row g-3 mb-4">
                <!-- Stat Card 1 -->
                <div class="col-md-6 col-xl-3">
                    <div class="card p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-xs font-semibold text-uppercase tracking-wider text-muted">Total Player</span>
                            <div class="p-2 rounded-3 bg-slate-100 text-slate-700"><i class="ti ti-users f-20"></i></div>
                        </div>
                        <h3 class="fw-bold text-slate-900 mb-1">{{ number_format($totalUsers) }}</h3>
                        <span class="text-xs text-muted">Player terdaftar dalam game</span>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="col-md-6 col-xl-3">
                    <div class="card p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-xs font-semibold text-uppercase tracking-wider text-muted">Pendapatan QRIS</span>
                            <div class="p-2 rounded-3 bg-emerald-50 text-emerald-600" style="background: #ecfdf5; color: #059669;"><i class="ti ti-wallet f-20"></i></div>
                        </div>
                        <h3 class="fw-bold text-slate-900 mb-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        <span class="text-xs text-muted">Total transaksi SUCCESS</span>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="col-md-6 col-xl-3">
                    <div class="card p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-xs font-semibold text-uppercase tracking-wider text-muted">Koin Terjual</span>
                            <div class="p-2 rounded-3 text-amber-600" style="background: #fefce8; color: #d97706;"><i class="ti ti-coin f-20"></i></div>
                        </div>
                        <h3 class="fw-bold text-slate-900 mb-1">{{ number_format($totalCoinsSold) }} <span class="fs-6 font-normal text-muted">KP</span></h3>
                        <span class="text-xs text-muted">Kuansing Poin dibeli player</span>
                    </div>
                </div>

                <!-- Stat Card 4 -->
                <div class="col-md-6 col-xl-3">
                    <div class="card p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-xs font-semibold text-uppercase tracking-wider text-muted">Player Diblokir</span>
                            <div class="p-2 rounded-3 text-rose-600" style="background: #fef2f2; color: #e11d48;"><i class="ti ti-user-off f-20"></i></div>
                        </div>
                        <h3 class="fw-bold text-slate-900 mb-1">{{ number_format($blockedUsers) }}</h3>
                        <span class="text-xs text-muted">Status blocked aktif</span>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Recent Orders / Transactions -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 fw-bold text-slate-900">Transaksi QRIS Terbaru</h6>
                            <a href="{{ route('superadmin.transactions') }}" class="btn-shadcn-outline">Lihat Semua</a>
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
                                                <td class="ps-4 fw-mono text-xs text-slate-900">{{ $tx->order_id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $tx->user->foto_profile ?? asset('admin/assets/images/user/avatar-2.jpg') }}" alt="User" class="rounded-circle me-2 border" style="width: 32px; height: 32px; object-fit: cover;">
                                                        <div>
                                                            <div class="fw-semibold text-slate-900" style="font-size: 13px;">{{ $tx->user->nama_jalur ?? 'No Name' }}</div>
                                                            <div class="text-muted" style="font-size: 11px;">{{ $tx->user->email }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="fw-semibold text-slate-900">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
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
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 fw-bold text-slate-900">Leaderboard Player</h6>
                            <a href="{{ route('superadmin.users') }}" class="btn-shadcn-outline">Detail</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse($topPlayers as $index => $player)
                                    <div class="list-group-item d-flex align-items-center justify-content-between border-0 py-3 px-4" style="border-bottom: 1px solid #f1f5f9 !important;">
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold me-3 text-muted" style="width: 20px; font-size: 12px;">#{{ $index + 1 }}</span>
                                            <img src="{{ $player->foto_profile ?? asset('admin/assets/images/user/avatar-2.jpg') }}" alt="User" class="rounded-circle me-3 border" style="width: 36px; height: 36px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0 text-slate-900 fw-semibold" style="font-size: 13px;">{{ $player->nama_jalur ?? 'No Name' }}</h6>
                                                <span class="text-muted" style="font-size: 11px;">{{ $player->email }}</span>
                                            </div>
                                        </div>
                                        <span class="shadcn-badge shadcn-badge-coin">
                                            <i class="ti ti-coin me-1"></i>{{ number_format($player->kuansing_poin) }} KP
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
