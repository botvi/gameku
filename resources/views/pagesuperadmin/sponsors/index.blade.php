@extends('template-admin.layout')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- Header Title -->
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2 border-bottom">
                <div>
                    <h3 class="h4 fw-bold text-slate-900 mb-1">Manajemen Spanduk Sponsor</h3>
                    <p class="text-muted text-sm mb-0">Kelola gambar spanduk promosi sponsor yang akan tampil di pinggir arena permainan pacu jalur.</p>
                </div>
            </div>

            <!-- [ Main Content ] start -->
            <div class="row g-4">
                <!-- ===== ADD SPONSOR FORM ===== -->
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-header py-3">
                            <h6 class="mb-0 fw-bold text-slate-900">
                                <i class="ti ti-photo-plus me-1 text-primary"></i>Tambah Sponsor Baru
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('superadmin.sponsors.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label fw-medium text-slate-900" style="font-size: 13px;">Nama Sponsor / Perusahaan</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name"
                                           value="{{ old('name') }}"
                                           required
                                           placeholder="Contoh: Bank Riau Kepri / Teh Botol">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label fw-medium text-slate-900" style="font-size: 13px;">Gambar Spanduk Promosi</label>
                                    <input type="file"
                                           class="form-control @error('image') is-invalid @enderror"
                                           id="image" name="image"
                                           accept="image/png,image/jpeg,image/gif,image/webp"
                                           required
                                           onchange="previewImage(this, 'previewNew')">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted" style="font-size: 11px;">Rekomendasi rasio memanjang / lanskap (misal: 140x44 px). Maks 5MB.</div>
                                    
                                    <!-- Preview gambar -->
                                    <div id="previewNew" class="mt-2 d-none text-center">
                                        <div class="d-inline-block border rounded-3 p-2 bg-white shadow-sm w-100">
                                            <img src="" alt="Preview Spanduk" class="img-fluid rounded" style="max-height: 80px; object-fit: contain;">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                        <label class="form-check-label fw-medium text-slate-900" style="font-size: 13px;" for="is_active">Aktif (Tampil di Arena)</label>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn-shadcn-primary py-2">
                                        <i class="ti ti-plus me-1"></i> Simpan Spanduk
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ===== SPONSORS LIST ===== -->
                <div class="col-md-8 col-sm-12">
                    <div class="card">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 fw-bold text-slate-900">
                                <i class="ti ti-photo me-1 text-success"></i>Daftar Spanduk Sponsor Arena
                            </h6>
                            <span class="shadcn-badge shadcn-badge-secondary">{{ $sponsors->count() }} spanduk</span>
                        </div>
                        <div class="card-body p-4">
                            @if($sponsors->isEmpty())
                                <div class="text-center py-5 text-muted">
                                    <i class="ti ti-photo-off" style="font-size: 3rem; opacity:.4;"></i>
                                    <p class="mt-2">Belum ada spanduk sponsor yang ditambahkan.</p>
                                    <small class="text-muted">Arena akan menampilkan spanduk default game jika tidak ada spanduk custom.</small>
                                </div>
                            @else
                                <div class="row g-3">
                                    @foreach($sponsors as $sponsor)
                                        <div class="col-md-6 col-lg-6">
                                            <div class="card h-100 position-relative overflow-hidden border">
                                                <!-- Badge aktif/nonaktif -->
                                                <span class="position-absolute top-0 end-0 m-2" style="z-index: 5;">
                                                    @if($sponsor->is_active)
                                                        <span class="shadcn-badge shadcn-badge-success">Aktif</span>
                                                    @else
                                                        <span class="shadcn-badge shadcn-badge-secondary">Nonaktif</span>
                                                    @endif
                                                </span>

                                                <!-- Preview Gambar Spanduk -->
                                                <div class="text-center p-3 bg-slate-50 border-bottom d-flex align-items-center justify-content-center" style="min-height:120px;">
                                                    <div class="bg-white p-2 rounded-3 border shadow-sm w-100 text-center">
                                                        <img src="{{ asset($sponsor->image_path) }}"
                                                             alt="{{ $sponsor->name }}"
                                                             class="img-fluid rounded"
                                                             style="max-height:80px; object-fit:contain;">
                                                    </div>
                                                </div>

                                                <div class="card-body p-3">
                                                    <h6 class="fw-bold text-slate-900 mb-2" style="font-size: 14px;">{{ $sponsor->name }}</h6>

                                                    <!-- Action buttons -->
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        <!-- Edit button -->
                                                        <button class="btn-shadcn-outline flex-fill" style="padding: 5px 8px !important; font-size: 11.5px !important;"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editModal{{ $sponsor->id }}">
                                                            <i class="ti ti-edit me-1"></i> Edit
                                                        </button>

                                                        <!-- Toggle Aktif -->
                                                        <form action="{{ route('superadmin.sponsors.toggle', $sponsor->id) }}" method="POST" class="flex-fill">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="w-100 {{ $sponsor->is_active ? 'btn-shadcn-outline' : 'btn-shadcn-success' }}" style="padding: 5px 8px !important; font-size: 11.5px !important;">
                                                                <i class="ti ti-{{ $sponsor->is_active ? 'eye-off' : 'eye' }} me-1"></i>
                                                                {{ $sponsor->is_active ? 'Matikan' : 'Aktifkan' }}
                                                            </button>
                                                        </form>

                                                        <!-- Delete -->
                                                        <form action="{{ route('superadmin.sponsors.delete', $sponsor->id) }}" method="POST"
                                                              onsubmit="return confirm('Hapus spanduk sponsor \'{{ $sponsor->name }}\'? Gambar akan dihapus dari sistem!')">
                                                            @csrf
                                                            <button type="submit" class="btn-shadcn-danger" style="padding: 5px 8px !important; font-size: 11.5px !important;">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Edit Modal -->
                                        <div class="modal fade text-start" id="editModal{{ $sponsor->id }}" tabindex="-1"
                                             aria-labelledby="editLabel{{ $sponsor->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title fw-bold text-slate-900" id="editLabel{{ $sponsor->id }}">
                                                            Edit Sponsor: {{ $sponsor->name }}
                                                        </h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <form action="{{ route('superadmin.sponsors.update', $sponsor->id) }}"
                                                              method="POST" enctype="multipart/form-data">
                                                            @csrf

                                                            <div class="mb-3">
                                                                <label class="form-label fw-medium text-slate-900" style="font-size: 13px;">Nama Sponsor / Perusahaan</label>
                                                                <input type="text" class="form-control"
                                                                       name="name" value="{{ $sponsor->name }}" required>
                                                            </div>

                                                            <div class="mb-3 border p-3 rounded-3 bg-light">
                                                                <label class="form-label fw-medium text-slate-900" style="font-size: 13px;">Gambar Spanduk Saat Ini</label>
                                                                <div class="text-center mb-2">
                                                                    <div class="bg-white p-2 rounded-3 border d-inline-block">
                                                                        <img src="{{ asset($sponsor->image_path) }}"
                                                                             alt="{{ $sponsor->name }}"
                                                                             class="img-fluid rounded"
                                                                             style="max-height:70px; object-fit:contain;">
                                                                    </div>
                                                                </div>
                                                                <label class="form-label text-muted" style="font-size: 11px;">Ganti Gambar Spanduk (opsional)</label>
                                                                <input type="file"
                                                                       class="form-control"
                                                                       name="image"
                                                                       accept="image/png,image/jpeg,image/gif,image/webp"
                                                                       onchange="previewImage(this, 'previewEdit{{ $sponsor->id }}')">
                                                                <div id="previewEdit{{ $sponsor->id }}" class="mt-2 d-none text-center">
                                                                    <div class="bg-white p-2 rounded-3 border d-inline-block">
                                                                        <img src="" alt="Preview Baru" class="img-fluid rounded" style="max-height:70px; object-fit:contain;">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="mb-4">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox"
                                                                           name="is_active" id="is_active_edit{{ $sponsor->id }}"
                                                                           {{ $sponsor->is_active ? 'checked' : '' }}>
                                                                    <label class="form-check-label fw-medium text-slate-900" style="font-size: 13px;"
                                                                           for="is_active_edit{{ $sponsor->id }}">Aktif (Tampil di Arena)</label>
                                                                </div>
                                                            </div>

                                                            <div class="d-grid">
                                                                <button type="submit" class="btn-shadcn-primary py-2">
                                                                    Simpan Perubahan
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>

    <script>
        function previewImage(input, previewContainerId) {
            const container = document.getElementById(previewContainerId);
            if (!container) return;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    container.classList.remove('d-none');
                    const img = container.querySelector('img');
                    if (img) {
                        img.src = e.target.result;
                    }
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                container.classList.add('d-none');
            }
        }
    </script>
@endsection
