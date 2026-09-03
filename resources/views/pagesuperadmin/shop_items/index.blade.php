@extends('template-admin.layout')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- Header Title -->
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2 border-bottom">
                <div>
                    <h3 class="h4 fw-bold text-slate-900 mb-1">Manajemen Item Shop</h3>
                    <p class="text-muted text-sm mb-0">Kelola katalog item shop, gambar asset asli, foto thumbnail (1:1), harga koin (KP), dan status keaktifan.</p>
                </div>
            </div>

            <!-- [ Main Content ] start -->
            <div class="row g-4">
                <!-- ===== ADD ITEM FORM ===== -->
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-header py-3">
                            <h6 class="mb-0 fw-bold text-slate-900">
                                <i class="ti ti-shopping-cart-plus me-1 text-primary"></i>Tambah Item Baru
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('superadmin.shop-items.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label fw-medium text-slate-900" style="font-size: 13px;">Nama Item</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name"
                                           value="{{ old('name') }}"
                                           required
                                           placeholder="Contoh: Skin Jalur Naga">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label fw-medium text-slate-900" style="font-size: 13px;">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description"
                                              rows="2"
                                              placeholder="Deskripsi singkat item...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="price_kp" class="form-label fw-medium text-slate-900" style="font-size: 13px;">Harga (KP)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-slate-100 border text-slate-700 fw-bold">🪙</span>
                                        <input type="number"
                                               class="form-control @error('price_kp') is-invalid @enderror"
                                               id="price_kp" name="price_kp"
                                               value="{{ old('price_kp') }}"
                                               required min="1"
                                               placeholder="Contoh: 100">
                                    </div>
                                    @error('price_kp')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted" style="font-size: 11px;">Harga dalam satuan Kuansing Poin (KP).</div>
                                </div>

                                <!-- Input Foto Thumbnail 1:1 -->
                                <div class="mb-3">
                                    <label for="thumbnail" class="form-label fw-medium text-slate-900 d-flex justify-content-between align-items-center" style="font-size: 13px;">
                                        <span>Foto Thumbnail Game (Rasio 1:1)</span>
                                        <span class="badge bg-primary text-white" style="font-size: 10px;">1:1 Ratio</span>
                                    </label>
                                    <input type="file"
                                           class="form-control @error('thumbnail') is-invalid @enderror"
                                           id="thumbnail" name="thumbnail"
                                           accept="image/png,image/jpeg,image/gif,image/webp"
                                           onchange="previewImage(this, 'previewThumbNew')">
                                    @error('thumbnail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted" style="font-size: 11px;">Foto thumbnail yang muncul di game & preview shop. Rasio wajib 1:1 (Persegi).</div>
                                    <!-- Preview Thumbnail -->
                                    <div id="previewThumbNew" class="mt-2 d-none text-center">
                                        <div class="d-inline-block border rounded-3 p-1 bg-white shadow-sm" style="width: 120px; height: 120px;">
                                            <img src="" alt="Preview Thumbnail" class="rounded-2 w-100 h-100" style="object-fit: cover; aspect-ratio: 1/1;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Input File Item Asli -->
                                <div class="mb-3">
                                    <label for="image" class="form-label fw-medium text-slate-900" style="font-size: 13px;">File Item Asli (Untuk Download)</label>
                                    <input type="file"
                                           class="form-control @error('image') is-invalid @enderror"
                                           id="image" name="image"
                                           required
                                           onchange="previewImage(this, 'previewNew')">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted" style="font-size: 11px;">File asli yang di-download pemain ketika dibeli. Maks 10MB.</div>
                                    <!-- Preview File/Gambar Asli -->
                                    <div id="previewNew" class="mt-2 d-none text-center">
                                        <img src="" alt="Preview Item Asli" class="img-fluid rounded-3 border p-1 bg-white" style="max-height: 120px;">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                        <label class="form-check-label fw-medium text-slate-900" style="font-size: 13px;" for="is_active">Aktif (tampil di shop)</label>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn-shadcn-primary py-2">
                                        <i class="ti ti-plus me-1"></i> Simpan Item
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ===== ITEMS LIST ===== -->
                <div class="col-md-8 col-sm-12">
                    <div class="card">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 fw-bold text-slate-900">
                                <i class="ti ti-package me-1 text-success"></i>Daftar Item Shop
                            </h6>
                            <span class="shadcn-badge shadcn-badge-secondary">{{ $items->count() }} item</span>
                        </div>
                        <div class="card-body p-4">
                            @if($items->isEmpty())
                                <div class="text-center py-5 text-muted">
                                    <i class="ti ti-shopping-cart-off" style="font-size: 3rem; opacity:.4;"></i>
                                    <p class="mt-2">Belum ada item shop yang ditambahkan.</p>
                                </div>
                            @else
                                <div class="row g-3">
                                    @foreach($items as $item)
                                        @php
                                            $displayThumb = !empty($item->thumbnail_path) ? asset($item->thumbnail_path) : asset($item->image_path);
                                        @endphp
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card h-100 position-relative overflow-hidden">
                                                <!-- Badge aktif/nonaktif -->
                                                <span class="position-absolute top-0 end-0 m-2" style="z-index: 5;">
                                                    @if($item->is_active)
                                                        <span class="shadcn-badge shadcn-badge-success">Aktif</span>
                                                    @else
                                                        <span class="shadcn-badge shadcn-badge-secondary">Nonaktif</span>
                                                    @endif
                                                </span>

                                                <!-- Gambar Preview Thumbnail (Rasio 1:1) -->
                                                <div class="text-center pt-3 pb-2 bg-slate-50 border-bottom d-flex align-items-center justify-content-center" style="min-height:140px;">
                                                    <div class="d-inline-block border rounded-3 p-1 bg-white shadow-sm overflow-hidden" style="width: 110px; height: 110px;">
                                                        <img src="{{ $displayThumb }}"
                                                             alt="{{ $item->name }}"
                                                             class="w-100 h-100 rounded-2"
                                                             style="object-fit: cover; aspect-ratio: 1/1;">
                                                    </div>
                                                </div>

                                                <div class="card-body p-3">
                                                    <h6 class="fw-bold text-slate-900 mb-1" style="font-size: 14px;">{{ $item->name }}</h6>
                                                    <p class="text-muted mb-2" style="font-size: 12px; min-height:34px;">{{ $item->description ?? '-' }}</p>
                                                    
                                                    <div class="mb-2">
                                                        <small class="text-muted d-block text-truncate" style="font-size: 11px;" title="{{ $item->filename }}">
                                                            <i class="ti ti-file me-1"></i><strong>File Asli:</strong> {{ $item->filename }}
                                                        </small>
                                                    </div>

                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <span class="shadcn-badge shadcn-badge-coin">
                                                            🪙 {{ number_format($item->price_kp) }} KP
                                                        </span>
                                                        <span class="text-muted" style="font-size: 11px;">
                                                            <i class="ti ti-download me-1"></i>{{ $item->purchasedByUsers()->count() }}x dibeli
                                                        </span>
                                                    </div>

                                                    <!-- Action buttons -->
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        <!-- Edit button -->
                                                        <button class="btn-shadcn-outline flex-fill" style="padding: 5px 8px !important; font-size: 11.5px !important;"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editModal{{ $item->id }}">
                                                            <i class="ti ti-edit me-1"></i> Edit
                                                        </button>

                                                        <!-- Toggle Aktif -->
                                                        <form action="{{ route('superadmin.shop-items.toggle', $item->id) }}" method="POST" class="flex-fill">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="w-100 {{ $item->is_active ? 'btn-shadcn-outline' : 'btn-shadcn-success' }}" style="padding: 5px 8px !important; font-size: 11.5px !important;">
                                                                <i class="ti ti-{{ $item->is_active ? 'eye-off' : 'eye' }} me-1"></i>
                                                                {{ $item->is_active ? 'Matikan' : 'Aktifkan' }}
                                                            </button>
                                                        </form>

                                                        <!-- Delete -->
                                                        <form action="{{ route('superadmin.shop-items.delete', $item->id) }}" method="POST"
                                                              onsubmit="return confirm('Hapus item \'{{ $item->name }}\'? File asset dan thumbnail akan dihapus!')">
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
                                        <div class="modal fade text-start" id="editModal{{ $item->id }}" tabindex="-1"
                                             aria-labelledby="editLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title fw-bold text-slate-900" id="editLabel{{ $item->id }}">
                                                            Edit Item: {{ $item->name }}
                                                        </h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <form action="{{ route('superadmin.shop-items.update', $item->id) }}"
                                                              method="POST" enctype="multipart/form-data">
                                                            @csrf

                                                            <div class="mb-3">
                                                                <label class="form-label fw-medium text-slate-900" style="font-size: 13px;">Nama Item</label>
                                                                <input type="text" class="form-control"
                                                                       name="name" value="{{ $item->name }}" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label fw-medium text-slate-900" style="font-size: 13px;">Deskripsi</label>
                                                                <textarea class="form-control" name="description"
                                                                          rows="2">{{ $item->description }}</textarea>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label fw-medium text-slate-900" style="font-size: 13px;">Harga (KP)</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg-slate-100 border text-slate-700 fw-bold">🪙</span>
                                                                    <input type="number" class="form-control"
                                                                           name="price_kp" value="{{ $item->price_kp }}" required min="1">
                                                                </div>
                                                            </div>

                                                            <!-- Edit Thumbnail 1:1 -->
                                                            <div class="mb-3 border p-3 rounded-3 bg-light">
                                                                <label class="form-label fw-medium text-slate-900 d-flex justify-content-between align-items-center" style="font-size: 13px;">
                                                                    <span>Thumbnail Game (1:1)</span>
                                                                    <span class="badge bg-secondary" style="font-size: 10px;">Ratio 1:1</span>
                                                                </label>
                                                                <div class="text-center mb-2">
                                                                    <div class="d-inline-block border rounded-3 p-1 bg-white shadow-sm" style="width: 90px; height: 90px;">
                                                                        <img src="{{ $displayThumb }}"
                                                                             alt="Thumbnail Current"
                                                                             class="w-100 h-100 rounded-2"
                                                                             style="object-fit: cover; aspect-ratio: 1/1;">
                                                                    </div>
                                                                </div>
                                                                <label class="form-label text-muted" style="font-size: 11px;">Ganti Foto Thumbnail (opsional)</label>
                                                                <input type="file"
                                                                       class="form-control"
                                                                       name="thumbnail"
                                                                       accept="image/png,image/jpeg,image/gif,image/webp"
                                                                       onchange="previewImage(this, 'previewThumbEdit{{ $item->id }}')">
                                                                <div id="previewThumbEdit{{ $item->id }}" class="mt-2 d-none text-center">
                                                                    <div class="d-inline-block border rounded-3 p-1 bg-white shadow-sm" style="width: 90px; height: 90px;">
                                                                        <img src="" alt="Preview Thumbnail Baru" class="w-100 h-100 rounded-2" style="object-fit: cover; aspect-ratio: 1/1;">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Edit File Item Asli -->
                                                            <div class="mb-3 border p-3 rounded-3 bg-light">
                                                                <label class="form-label fw-medium text-slate-900" style="font-size: 13px;">File Item Asli (Untuk Download)</label>
                                                                <div class="small text-muted mb-2 text-truncate">
                                                                    <i class="ti ti-file me-1"></i>File saat ini: <strong>{{ $item->filename }}</strong>
                                                                </div>
                                                                <label class="form-label text-muted" style="font-size: 11px;">Ganti File Item Asli (opsional)</label>
                                                                <input type="file"
                                                                       class="form-control"
                                                                       name="image"
                                                                       onchange="previewImage(this, 'previewEdit{{ $item->id }}')">
                                                                <div id="previewEdit{{ $item->id }}" class="mt-2 d-none text-center">
                                                                    <img src="" alt="Preview File Baru" class="img-fluid rounded-3 border p-1 bg-white" style="max-height:90px;">
                                                                </div>
                                                            </div>

                                                            <div class="mb-4">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox"
                                                                           name="is_active" id="is_active_edit{{ $item->id }}"
                                                                           {{ $item->is_active ? 'checked' : '' }}>
                                                                    <label class="form-check-label fw-medium text-slate-900" style="font-size: 13px;"
                                                                           for="is_active_edit{{ $item->id }}">Aktif</label>
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
