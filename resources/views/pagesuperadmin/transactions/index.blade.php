@extends('template-admin.layout')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- Header Title -->
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2 border-bottom">
                <div>
                    <h3 class="h4 fw-bold text-slate-900 mb-1">Riwayat Transaksi Topup</h3>
                    <p class="text-muted text-sm mb-0">Log aktivitas dan audit trail pembayaran QRIS KlikQRIS oleh seluruh player.</p>
                </div>
            </div>

            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header py-3">
                            <h6 class="mb-0 fw-bold text-slate-900">Log Aktivitas Pembayaran QRIS</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle" id="simpletable">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">#</th>
                                            <th>ORDER ID</th>
                                            <th>PLAYER</th>
                                            <th>NOMINAL</th>
                                            <th>JUMLAH KOIN</th>
                                            <th>STATUS</th>
                                            <th>SIGNATURE</th>
                                            <th>TANGGAL PENGAJUAN</th>
                                            <th class="pe-4">WAKTU PEMBAYARAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transactions as $index => $tx)
                                            <tr>
                                                <td class="ps-4 text-muted fw-semibold" style="font-size: 12px;">{{ $index + 1 }}</td>
                                                <td class="fw-mono text-xs text-slate-900">{{ $tx->order_id }}</td>
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
                                                    <span class="shadcn-badge shadcn-badge-coin">
                                                        <i class="ti ti-coin me-1"></i>{{ number_format($tx->coin_amount) }} KP
                                                    </span>
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
                                                <td>
                                                    @if($tx->signature)
                                                        <code class="text-xs text-muted font-mono" title="{{ $tx->signature }}">{{ substr($tx->signature, 0, 15) }}...</code>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-muted" style="font-size: 12px;">{{ $tx->created_at->format('d M Y H:i:s') }}</td>
                                                <td class="pe-4 text-muted" style="font-size: 12px;">
                                                    @if($tx->paid_at)
                                                        <span class="text-emerald-600 font-medium" style="color: #059669;"><i class="ti ti-calendar-event me-1"></i>{{ $tx->paid_at->format('d M Y H:i:s') }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-4 text-muted">Belum ada transaksi pembayaran.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
@endsection
