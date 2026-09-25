@extends('template-admin.layout')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        <!-- Header Page -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2 border-bottom">
            <div>
                <h3 class="h4 fw-bold text-slate-900 mb-1">
                    <i class="ti ti-trophy text-warning me-2"></i>Kelola Turnamen Pacu Jalur
                </h3>
                <p class="text-muted text-sm mb-0">Buat turnamen sistem gugur, daftarkan pemain, dan kontrol bagan pertandingan.</p>
            </div>
            <button type="button" class="btn btn-primary d-flex align-items-center gap-2 mt-3 mt-md-0" data-bs-toggle="modal" data-bs-target="#createTournamentModal">
                <i class="ti ti-plus f-18"></i> Buat Turnamen Baru
            </button>
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

        <!-- Daftar Turnamen -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header py-3 bg-white border-bottom">
                <h6 class="mb-0 fw-bold text-slate-900">Daftar Turnamen Game</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 text-slate-700">No</th>
                                <th class="text-slate-700">Nama Turnamen</th>
                                <th class="text-slate-700">Kuota</th>
                                <th class="text-slate-700">Status</th>
                                <th class="text-slate-700">Hadiah Coin</th>
                                <th class="text-slate-700">Juara 1</th>
                                <th class="text-slate-700">Dibuat Pada</th>
                                <th class="text-end pe-4 text-slate-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tournaments as $key => $t)
                                <tr>
                                    <td class="ps-4 text-muted fw-semibold" style="font-size: 13px;">{{ $tournaments->firstItem() + $key }}</td>
                                    <td>
                                        <div class="fw-bold text-slate-900" style="font-size: 14px;">{{ $t->title }}</div>
                                        <small class="text-muted">{{ Str::limit($t->description, 40) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light-primary text-primary fw-bold">{{ $t->participants->count() }} / {{ $t->max_participants }} Player</span>
                                    </td>
                                    <td>
                                        @if($t->status === 'draft')
                                            <span class="badge bg-light-warning text-warning fw-bold"><i class="ti ti-pencil me-1"></i>Draft</span>
                                        @elseif($t->status === 'registration')
                                            <span class="badge bg-light-info text-info fw-bold"><i class="ti ti-users me-1"></i>Bagan Ready</span>
                                        @elseif($t->status === 'active')
                                            <span class="badge bg-light-success text-success fw-bold"><i class="ti ti-player-play me-1"></i>BERLANGSUNG</span>
                                        @elseif($t->status === 'completed')
                                            <span class="badge bg-light-primary text-primary fw-bold"><i class="ti ti-trophy me-1"></i>Selesai</span>
                                        @else
                                            <span class="badge bg-light-danger text-danger fw-bold">Batal</span>
                                        @endif
                                    </td>
                                    <td class="text-warning fw-bold">
                                        <i class="ti ti-coin me-1"></i> {{ number_format($t->prize_coins) }}
                                    </td>
                                    <td>
                                        @if($t->winner)
                                            <span class="text-success fw-bold"><i class="ti ti-trophy me-1"></i> {{ $t->winner->nama_jalur }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-slate-600" style="font-size: 12px;">{{ $t->created_at->format('d M Y, H:i') }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('superadmin.tournaments.detail', $t->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="ti ti-eye me-1"></i> Detail & Bagan
                                        </a>
                                        <form action="{{ route('superadmin.tournaments.delete', $t->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus turnamen ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="ti ti-trophy-off f-40 d-block mb-2 text-slate-400"></i>
                                        Belum ada turnamen yang dibuat. Klik tombol "Buat Turnamen Baru".
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($tournaments->hasPages())
                <div class="card-footer bg-white border-top">
                    {{ $tournaments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Buat Turnamen -->
<div class="modal fade" id="createTournamentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-white border-bottom">
                <h5 class="modal-title fw-bold text-slate-900"><i class="ti ti-trophy text-warning me-2"></i>Buat Turnamen Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('superadmin.tournaments.store') }}" method="POST">
                @csrf
                <div class="modal-body bg-light">
                    <div class="mb-3">
                        <label class="form-label text-slate-900 fw-bold">Judul Turnamen</label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Turnamen Pacu Jalur Teluk Kuantan 2026" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-slate-900 fw-bold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Keterangan turnamen..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-slate-900 fw-bold">Kuota Peserta (Custom Ganjil/Genap)</label>
                            <input type="number" name="max_participants" class="form-control" value="8" min="2" max="128" placeholder="Contoh: 3, 5, 8, 10, 16..." required>
                            <small class="text-muted" style="font-size: 11px;">Bisa diisi angka ganjil/genap (2 s/d 128 player)</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-slate-900 fw-bold">Hadiah Total Coin</label>
                            <input type="number" name="prize_coins" class="form-control" value="10000" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i> Simpan & Lanjut ke Peserta</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
