@extends('template-admin.layout')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- Header Title -->
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2 border-bottom">
                <div>
                    <h3 class="h4 fw-bold text-slate-900 mb-1">Manajemen Player</h3>
                    <p class="text-muted text-sm mb-0">Kelola akun pengguna, status blokir, saldo koin, dan rekor pertandingan.</p>
                </div>
            </div>

            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header py-3">
                            <h6 class="mb-0 fw-bold text-slate-900">Daftar Player Game</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle" id="simpletable">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">#</th>
                                            <th>PLAYER PROFILE</th>
                                            <th>EMAIL</th>
                                            <th>SALDO KOIN</th>
                                            <th>REKOR MATCH</th>
                                            <th>STATUS</th>
                                            <th class="pe-4 text-center">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $index => $u)
                                            <tr>
                                                <td class="ps-4 text-muted fw-semibold" style="font-size: 12px;">{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $u->foto_profile ?? asset('admin/assets/images/user/avatar-2.jpg') }}" alt="User" class="rounded-circle me-3 border" style="width: 40px; height: 40px; object-fit: cover;">
                                                        <div>
                                                            <span class="fw-bold text-slate-900 d-block" style="font-size: 14px;">{{ $u->nama_jalur ?? 'Belum Membuat Jalur' }}</span>
                                                            <span class="shadcn-badge shadcn-badge-secondary mt-1" style="font-size: 10px; padding: 2px 6px;">Role: {{ ucfirst($u->role) }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-slate-700" style="font-size: 13px;">{{ $u->email }}</td>
                                                <td>
                                                    <span class="shadcn-badge shadcn-badge-coin">
                                                        <i class="ti ti-coin me-1"></i>{{ number_format($u->kuansing_poin) }} KP
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <span class="shadcn-badge shadcn-badge-success">
                                                            🏆 {{ $u->wins_count }} Win
                                                        </span>
                                                        <span class="shadcn-badge shadcn-badge-danger">
                                                            💀 {{ $u->losses_count }} Loss
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($u->is_blocked)
                                                        <span class="shadcn-badge shadcn-badge-danger">BLOCKED</span>
                                                    @else
                                                        <span class="shadcn-badge shadcn-badge-success">ACTIVE</span>
                                                    @endif
                                                </td>
                                                <td class="pe-4 text-center">
                                                    <form action="{{ route('superadmin.users.toggle-block', $u->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin mengubah status blokir untuk player ini?')">
                                                        @csrf
                                                        @if($u->is_blocked)
                                                            <button type="submit" class="btn-shadcn-success">
                                                                <i class="ti ti-lock-open me-1"></i> Unblock
                                                            </button>
                                                        @else
                                                            <button type="submit" class="btn-shadcn-danger">
                                                                <i class="ti ti-lock me-1"></i> Block
                                                            </button>
                                                        @endif
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">Belum ada player terdaftar.</td>
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
