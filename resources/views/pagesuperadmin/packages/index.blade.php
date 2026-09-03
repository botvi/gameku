@extends('template-admin.layout')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- Header Title -->
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2 border-bottom">
                <div>
                    <h3 class="h4 fw-bold text-slate-900 mb-1">Manajemen Paket Koin</h3>
                    <p class="text-muted text-sm mb-0">Atur opsi paket pembelian Kuansing Poin (KP) dan harga Rupiah untuk QRIS topup.</p>
                </div>
            </div>

            <!-- [ Main Content ] start -->
            <div class="row g-4">
                <!-- Add Package Form -->
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-header py-3">
                            <h6 class="mb-0 fw-bold text-slate-900">
                                <i class="ti ti-plus me-1 text-primary"></i>Tambah Paket Baru
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('superadmin.packages.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="coin_amount" class="form-label fw-medium text-slate-900" style="font-size: 13px;">Jumlah Koin (KP)</label>
                                    <input type="number" class="form-control" id="coin_amount" name="coin_amount" required min="1" placeholder="Contoh: 100">
                                    <div class="form-text text-muted" style="font-size: 11px;">Jumlah poin/koin Kuansing yang akan didapat player.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="price" class="form-label fw-medium text-slate-900" style="font-size: 13px;">Harga (Rupiah)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-slate-100 text-slate-700 border" style="font-size: 13px;">Rp</span>
                                        <input type="number" class="form-control" id="price" name="price" required min="0" placeholder="Contoh: 10000">
                                    </div>
                                    <div class="form-text text-muted" style="font-size: 11px;">Harga beli paket koin dalam mata uang rupiah (IDR).</div>
                                </div>

                                <div class="mb-4">
                                    <label for="description" class="form-label fw-medium text-slate-900" style="font-size: 13px;">Keterangan / Deskripsi</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Contoh: Paket Hemat 100 Koin"></textarea>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn-shadcn-primary py-2">
                                        <i class="ti ti-plus me-1"></i> Simpan Paket
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Packages List -->
                <div class="col-md-8 col-sm-12">
                    <div class="card">
                        <div class="card-header py-3">
                            <h6 class="mb-0 fw-bold text-slate-900">Daftar Paket Aktif</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th class="ps-4">#</th>
                                            <th>JUMLAH KOIN</th>
                                            <th>HARGA RUPIAH</th>
                                            <th>DESKRIPSI</th>
                                            <th class="pe-4 text-center">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($packages as $index => $pkg)
                                            <tr>
                                                <td class="ps-4 text-muted fw-semibold" style="font-size: 12px;">{{ $index + 1 }}</td>
                                                <td>
                                                    <span class="shadcn-badge shadcn-badge-coin">
                                                        <i class="ti ti-coin me-1"></i>{{ number_format($pkg->coin_amount) }} KP
                                                    </span>
                                                </td>
                                                <td class="fw-semibold text-slate-900">Rp {{ number_format($pkg->price, 0, ',', '.') }}</td>
                                                <td class="text-slate-700" style="font-size: 13px;">{{ $pkg->description ?? '-' }}</td>
                                                <td class="pe-4 text-center">
                                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                                        <!-- Edit Trigger Button -->
                                                        <button class="btn-shadcn-outline" style="padding: 5px 12px !important; font-size: 12px !important;" data-bs-toggle="modal" data-bs-target="#editModal{{ $pkg->id }}">
                                                            <i class="ti ti-edit me-1"></i> Edit
                                                        </button>

                                                        <!-- Delete Button -->
                                                        <form action="{{ route('superadmin.packages.delete', $pkg->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket koin ini?')">
                                                            @csrf
                                                            <button type="submit" class="btn-shadcn-danger" style="padding: 5px 12px !important; font-size: 12px !important;">
                                                                <i class="ti ti-trash me-1"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </div>

                                                    <!-- Edit Modal -->
                                                    <div class="modal fade text-start" id="editModal{{ $pkg->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $pkg->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h6 class="modal-title fw-bold text-slate-900" id="editModalLabel{{ $pkg->id }}">Edit Paket Koin</h6>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body p-4">
                                                                    <form action="{{ route('superadmin.packages.update', $pkg->id) }}" method="POST">
                                                                        @csrf
                                                                        <div class="mb-3">
                                                                            <label for="coin_amount{{ $pkg->id }}" class="form-label fw-medium text-slate-900" style="font-size: 13px;">Jumlah Koin (KP)</label>
                                                                            <input type="number" class="form-control" id="coin_amount{{ $pkg->id }}" name="coin_amount" value="{{ $pkg->coin_amount }}" required min="1">
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="price{{ $pkg->id }}" class="form-label fw-medium text-slate-900" style="font-size: 13px;">Harga (Rupiah)</label>
                                                                            <div class="input-group">
                                                                                <span class="input-group-text bg-slate-100 text-slate-700 border" style="font-size: 13px;">Rp</span>
                                                                                <input type="number" class="form-control" id="price{{ $pkg->id }}" name="price" value="{{ intval($pkg->price) }}" required min="0">
                                                                            </div>
                                                                        </div>
                                                                        <div class="mb-4">
                                                                            <label for="description{{ $pkg->id }}" class="form-label fw-medium text-slate-900" style="font-size: 13px;">Keterangan / Deskripsi</label>
                                                                            <textarea class="form-control" id="description{{ $pkg->id }}" name="description" rows="3">{{ $pkg->description }}</textarea>
                                                                        </div>
                                                                        <div class="d-grid">
                                                                            <button type="submit" class="btn-shadcn-primary py-2">Simpan Perubahan</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">Belum ada paket koin yang dibuat.</td>
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
