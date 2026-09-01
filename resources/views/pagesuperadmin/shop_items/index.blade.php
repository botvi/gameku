@extends('template-admin.layout')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title mb-2">
                                <h4 class="m-0 text-dark fw-bold">Manajemen Item Shop</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard-superadmin') }}">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page">Item Shop</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- ===== ADD ITEM FORM ===== -->
                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 text-dark fw-bold">
                                <i class="ti ti-shopping-cart-plus me-2 text-primary"></i>Tambah Item Baru
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('superadmin.shop-items.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold text-dark">Nama Item</label>
                                    <input type="text"
                                           class="form-control rounded-3 @error('name') is-invalid @enderror"
                                           id="name" name="name"
                                           value="{{ old('name') }}"
                                           required
                                           placeholder="Contoh: Skin Naga">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label fw-semibold text-dark">Deskripsi</label>
                                    <textarea class="form-control rounded-3 @error('description') is-invalid @enderror"
                                              id="description" name="description"
                                              rows="2"
                                              placeholder="Deskripsi singkat item...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="price_kp" class="form-label fw-semibold text-dark">Harga (KP)</label>
                                    <div class="input-group">
                                        <span class="input-group-text rounded-start-3 bg-warning text-dark fw-bold">🪙</span>
                                        <input type="number"
                                               class="form-control rounded-end-3 @error('price_kp') is-invalid @enderror"
                                               id="price_kp" name="price_kp"
                                               value="{{ old('price_kp') }}"
                                               required min="1"
                                               placeholder="Contoh: 100">
                                    </div>
                                    @error('price_kp')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted text-xs">Harga dalam satuan Kuansing Poin (KP).</div>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label fw-semibold text-dark">Gambar Item</label>
                                    <input type="file"
                                           class="form-control rounded-3 @error('image') is-invalid @enderror"
                                           id="image" name="image"
                                           accept="image/png,image/jpeg,image/gif,image/webp"
                                           required
                                           onchange="previewImage(this, 'previewNew')">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted text-xs">Format: PNG, JPG, GIF, WebP. Maks 5MB.</div>
                                    <!-- Preview gambar -->
                                    <div id="previewNew" class="mt-2 d-none text-center">
                                        <img src="" alt="Preview" class="img-fluid rounded-3 border" style="max-height: 160px;">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                        <label class="form-check-label fw-semibold text-dark" for="is_active">Aktif (tampil di shop)</label>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary rounded-pill py-2 fw-semibold">
                                        <i class="ti ti-plus me-1"></i> Simpan Item
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ===== ITEMS LIST ===== -->
                <div class="col-md-8 col-sm-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 text-dark fw-bold">
                                <i class="ti ti-package me-2 text-success"></i>Daftar Item Shop
                            </h5>
                            <span class="badge bg-primary rounded-pill px-3">{{ $items->count() }} item</span>
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
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card border rounded-4 h-100 shadow-sm position-relative overflow-hidden">
                                                <!-- Badge aktif/nonaktif -->
                                                <span class="position-absolute top-0 end-0 m-2 badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                                                    {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>

                                                <!-- Gambar item -->
                                                <div class="text-center pt-3 pb-2 bg-light" style="min-height:130px;">
                                                    <img src="{{ asset($item->image_path) }}"
                                                         alt="{{ $item->name }}"
                                                         class="img-fluid rounded-3"
                                                         style="max-height:120px; object-fit:contain;">
                                                </div>

                                                <div class="card-body px-3 py-2">
                                                    <h6 class="fw-bold text-dark mb-1">{{ $item->name }}</h6>
                                                    <p class="text-muted small mb-2" style="min-height:36px;">{{ $item->description ?? '-' }}</p>
                                                    <div class="d-flex align-items-center gap-1 mb-3">
                                                        <span class="badge bg-warning text-dark rounded-pill fw-bold px-2">
                                                            🪙 {{ number_format($item->price_kp) }} KP
                                                        </span>
                                                        <span class="text-muted small">
                                                            <i class="ti ti-download"></i> {{ $item->purchasedByUsers()->count() }}x dibeli
                                                        </span>
                                                    </div>

                                                    <!-- Action buttons -->
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        <!-- Edit button -->
                                                        <button class="btn btn-sm btn-outline-primary rounded-pill px-2 flex-fill"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editModal{{ $item->id }}">
                                                            <i class="ti ti-edit"></i> Edit
                                                        </button>

                                                        <!-- Toggle Aktif -->
                                                        <form action="{{ route('superadmin.shop-items.toggle', $item->id) }}" method="POST" class="flex-fill">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="btn btn-sm w-100 rounded-pill px-2 {{ $item->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                                                <i class="ti ti-{{ $item->is_active ? 'eye-off' : 'eye' }}"></i>
                                                                {{ $item->is_active ? 'Nonaktif' : 'Aktifkan' }}
                                                            </button>
                                                        </form>

                                                        <!-- Delete -->
                                                        <form action="{{ route('superadmin.shop-items.delete', $item->id) }}" method="POST"
                                                              onsubmit="return confirm('Hapus item \'{{ $item->name }}\'? Gambar juga akan dihapus!')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2">
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
                                                <div class="modal-content rounded-4 border-0">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="modal-title fw-bold text-dark" id="editLabel{{ $item->id }}">
                                                            Edit Item: {{ $item->name }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <form action="{{ route('superadmin.shop-items.update', $item->id) }}"
                                                              method="POST" enctype="multipart/form-data">
                                                            @csrf

                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold text-dark">Nama Item</label>
                                                                <input type="text" class="form-control rounded-3"
                                                                       name="name" value="{{ $item->name }}" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold text-dark">Deskripsi</label>
                                                                <textarea class="form-control rounded-3" name="description"
                                                                          rows="2">{{ $item->description }}</textarea>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold text-dark">Harga (KP)</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text rounded-start-3 bg-warning text-dark fw-bold">🪙</span>
                                                                    <input type="number" class="form-control rounded-end-3"
                                                                           name="price_kp" value="{{ $item->price_kp }}" required min="1">
                                                                </div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold text-dark">Gambar Saat Ini</label>
                                                                <div class="text-center mb-2">
                                                                    <img src="{{ asset($item->image_path) }}"
                                                                         alt="{{ $item->name }}"
                                                                         class="img-fluid rounded-3 border"
                                                                         style="max-height:100px;">
                                                                </div>
                                                                <label class="form-label text-muted small">Ganti Gambar (opsional)</label>
                                                                <input type="file"
                                                                       class="form-control rounded-3"
                                                                       name="image"
                                                                       accept="image/png,image/jpeg,image/gif,image/webp"
                                                                       onchange="previewImage(this, 'previewEdit{{ $item->id }}')">
                                                                <div id="previewEdit{{ $item->id }}" class="mt-2 d-none text-center">
                                                                    <img src="" alt="Preview Baru" class="img-fluid rounded-3 border" style="max-height:100px;">
                                                                </div>
                                                            </div>

                                                            <div class="mb-4">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox"
                                                                           name="is_active" id="is_active_edit{{ $item->id }}"
                                                                           {{ $item->is_active ? 'checked' : '' }}>
                                                                    <label class="form-check-label fw-semibold text-dark"
                                                                           for="is_active_edit{{ $item->id }}">Aktif</label>
                                                                </div>
                                                            </div>

                                                            <div class="d-grid">
                                                                <button type="submit" class="btn btn-primary rounded-pill py-2 fw-semibold">
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
                    container.querySelector('img').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                container.classList.add('d-none');
            }
        }
    </script>
@endsection
