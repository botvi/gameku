@extends('template-admin.layout')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- Header Title -->
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2 border-bottom">
                <div>
                    <h3 class="h4 fw-bold text-slate-900 mb-1">Manajemen Inbox & Notifikasi</h3>
                    <p class="text-muted text-sm mb-0">Kirim dan kelola pesan pengumuman atau hadiah koin ke semua player atau player spesifik.</p>
                </div>
            </div>

            <!-- Flash Alert -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="ti ti-circle-check me-2 f-18"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- [ Main Content ] start -->
            <div class="row g-4">
                <!-- ===== FORM TAMBAH INBOX ===== -->
                <div class="col-md-4 col-sm-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header py-3 bg-white border-bottom">
                            <h6 class="mb-0 fw-bold text-slate-900">
                                <i class="ti ti-mail-plus me-1 text-primary"></i>Buat Pesan Inbox Baru
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('superadmin.inbox.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="title" class="form-label fw-medium text-slate-900" style="font-size: 13px;">Judul Pesan / Pengumuman</label>
                                    <input type="text"
                                           class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title"
                                           value="{{ old('title') }}"
                                           required
                                           placeholder="Contoh: Hadiah Event Mingguan">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="content" class="form-label fw-medium text-slate-900" style="font-size: 13px;">Isi Pesan Inbox</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror"
                                              id="content" name="content" rows="4" required
                                              placeholder="Tuliskan isi pesan pengumuman atau instruksi di sini...">{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="type" class="form-label fw-medium text-slate-900" style="font-size: 13px;">Tipe Pesan Inbox</label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="info" {{ old('type') == 'info' ? 'selected' : '' }}>ℹ️ Informasi Biasa</option>
                                        <option value="reward" {{ old('type') == 'reward' ? 'selected' : '' }}>🎁 Hadiah / Reward Koin</option>
                                        <option value="announcement" {{ old('type') == 'announcement' ? 'selected' : '' }}>📢 Pengumuman Penting</option>
                                        <option value="warning" {{ old('type') == 'warning' ? 'selected' : '' }}>⚠️ Peringatan / Himbauan</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="target_type" class="form-label fw-medium text-slate-900" style="font-size: 13px;">Target Penerima</label>
                                    <select class="form-select @error('target_type') is-invalid @enderror"
                                            id="target_type" name="target_type" onchange="toggleTargetUser(this.value, 'user_select_container')">
                                        <option value="all" {{ old('target_type') == 'all' ? 'selected' : '' }}>Semua Player (Broadcast)</option>
                                        <option value="user" {{ old('target_type') == 'user' ? 'selected' : '' }}>Player Spesifik</option>
                                    </select>
                                </div>

                                <div class="mb-3 {{ old('target_type') == 'user' ? '' : 'd-none' }}" id="user_select_container">
                                    <label for="target_user_id" class="form-label fw-medium text-slate-900" style="font-size: 13px;">Pilih Player Penerima</label>
                                    <select class="form-select @error('target_user_id') is-invalid @enderror"
                                            id="target_user_id" name="target_user_id">
                                        <option value="">-- Pilih Player --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('target_user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->nama_jalur ?? $user->email }} (ID: #{{ $user->id }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('target_user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="reward_coins" class="form-label fw-medium text-slate-900" style="font-size: 13px;">Bonus Koin KP (Opsional)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="ti ti-coin text-warning"></i></span>
                                        <input type="number"
                                               class="form-control @error('reward_coins') is-invalid @enderror"
                                               id="reward_coins" name="reward_coins"
                                               value="{{ old('reward_coins', 0) }}"
                                               min="0" step="50" placeholder="0">
                                    </div>
                                    <div class="form-text text-muted" style="font-size: 11px;">Isi > 0 jika pesan mengandung hadiah koin yang bisa diklaim player.</div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                        <label class="form-check-label fw-medium text-slate-900" style="font-size: 13px;" for="is_active">Aktifkan Pesan Ini</label>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn-shadcn-primary py-2">
                                        <i class="ti ti-send me-1"></i> Kirim Pesan Inbox
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ===== DAFTAR INBOX ===== -->
                <div class="col-md-8 col-sm-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header py-3 bg-white border-bottom d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 fw-bold text-slate-900">
                                <i class="ti ti-inbox me-1 text-success"></i>Daftar Pesan Inbox Admin
                            </h6>
                            <span class="shadcn-badge shadcn-badge-secondary">{{ $inboxes->count() }} Pesan</span>
                        </div>
                        <div class="card-body p-4">
                            @if($inboxes->isEmpty())
                                <div class="text-center py-5 text-muted">
                                    <i class="ti ti-mail-off" style="font-size: 3rem; opacity:.4;"></i>
                                    <p class="mt-2">Belum ada pesan inbox yang dikirim.</p>
                                    <small class="text-muted">Gunakan form di sebelah kiri untuk mengirim pesan baru ke player.</small>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle border">
                                        <thead class="table-light">
                                            <tr style="font-size: 12px;">
                                                <th>Pesan & Detail</th>
                                                <th>Target</th>
                                                <th>Hadiah Koin</th>
                                                <th>Status</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($inboxes as $inbox)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2 mb-1">
                                                            @if($inbox->type === 'reward')
                                                                <span class="badge bg-warning text-dark" style="font-size: 10px;">🎁 HADIAH</span>
                                                            @elseif($inbox->type === 'announcement')
                                                                <span class="badge bg-primary" style="font-size: 10px;">📢 PENGUMUMAN</span>
                                                            @elseif($inbox->type === 'warning')
                                                                <span class="badge bg-danger" style="font-size: 10px;">⚠️ PERINGATAN</span>
                                                            @else
                                                                <span class="badge bg-secondary" style="font-size: 10px;">ℹ️ INFO</span>
                                                            @endif
                                                            <div class="fw-bold text-slate-900" style="font-size: 13.5px;">{{ $inbox->title }}</div>
                                                        </div>
                                                        <div class="text-muted text-truncate" style="max-width: 260px; font-size: 12px;">
                                                            {{ $inbox->content }}
                                                        </div>
                                                        <small class="text-muted d-block mt-1" style="font-size: 10.5px;">
                                                            <i class="ti ti-clock me-1"></i>{{ $inbox->created_at->format('d M Y H:i') }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        @if($inbox->target_type === 'all')
                                                            <span class="shadcn-badge shadcn-badge-success" style="font-size: 10px;">
                                                                <i class="ti ti-users me-1"></i>Semua Player
                                                            </span>
                                                        @else
                                                            <span class="shadcn-badge shadcn-badge-info" style="font-size: 10px;">
                                                                <i class="ti ti-user me-1"></i>{{ $inbox->targetUser->nama_jalur ?? $inbox->targetUser->email ?? 'User #'.$inbox->target_user_id }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($inbox->reward_coins > 0)
                                                            <span class="badge bg-warning text-dark fw-bold" style="font-size: 11px;">
                                                                +{{ number_format($inbox->reward_coins, 0, ',', '.') }} KP
                                                            </span>
                                                        @else
                                                            <span class="text-muted" style="font-size: 11px;">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($inbox->is_active)
                                                            <span class="shadcn-badge shadcn-badge-success">Aktif</span>
                                                        @else
                                                            <span class="shadcn-badge shadcn-badge-secondary">Nonaktif</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="d-inline-flex gap-1">
                                                            <!-- Toggle Active -->
                                                            <form action="{{ route('superadmin.inbox.toggle', $inbox->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                        class="btn btn-sm {{ $inbox->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }}"
                                                                        title="{{ $inbox->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                                    <i class="ti ti-{{ $inbox->is_active ? 'eye-off' : 'eye' }}"></i>
                                                                </button>
                                                            </form>

                                                            <!-- Edit Modal Trigger -->
                                                            <button class="btn btn-sm btn-outline-primary"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editModal{{ $inbox->id }}"
                                                                    title="Edit">
                                                                <i class="ti ti-edit"></i>
                                                            </button>

                                                            <!-- Delete -->
                                                            <form action="{{ route('superadmin.inbox.delete', $inbox->id) }}" method="POST"
                                                                  onsubmit="return confirm('Hapus pesan inbox \'{{ $inbox->title }}\'?')">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                                    <i class="ti ti-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal fade text-start" id="editModal{{ $inbox->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h6 class="modal-title fw-bold text-slate-900">Edit Pesan Inbox: {{ $inbox->title }}</h6>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body p-4">
                                                                <form action="{{ route('superadmin.inbox.update', $inbox->id) }}" method="POST">
                                                                    @csrf

                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-medium text-slate-900" style="font-size: 13px;">Judul Pesan</label>
                                                                        <input type="text" class="form-control" name="title" value="{{ $inbox->title }}" required>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-medium text-slate-900" style="font-size: 13px;">Isi Pesan Inbox</label>
                                                                        <textarea class="form-control" name="content" rows="4" required>{{ $inbox->content }}</textarea>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-medium text-slate-900" style="font-size: 13px;">Tipe Pesan Inbox</label>
                                                                        <select class="form-select" name="type" required>
                                                                            <option value="info" {{ $inbox->type == 'info' ? 'selected' : '' }}>ℹ️ Informasi Biasa</option>
                                                                            <option value="reward" {{ $inbox->type == 'reward' ? 'selected' : '' }}>🎁 Hadiah / Reward Koin</option>
                                                                            <option value="announcement" {{ $inbox->type == 'announcement' ? 'selected' : '' }}>📢 Pengumuman Penting</option>
                                                                            <option value="warning" {{ $inbox->type == 'warning' ? 'selected' : '' }}>⚠️ Peringatan / Himbauan</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-medium text-slate-900" style="font-size: 13px;">Target Penerima</label>
                                                                        <select class="form-select" name="target_type" onchange="toggleTargetUser(this.value, 'edit_user_select_{{ $inbox->id }}')">
                                                                            <option value="all" {{ $inbox->target_type == 'all' ? 'selected' : '' }}>Semua Player (Broadcast)</option>
                                                                            <option value="user" {{ $inbox->target_type == 'user' ? 'selected' : '' }}>Player Spesifik</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="mb-3 {{ $inbox->target_type == 'user' ? '' : 'd-none' }}" id="edit_user_select_{{ $inbox->id }}">
                                                                        <label class="form-label fw-medium text-slate-900" style="font-size: 13px;">Pilih Player Penerima</label>
                                                                        <select class="form-select" name="target_user_id">
                                                                            <option value="">-- Pilih Player --</option>
                                                                            @foreach($users as $u)
                                                                                <option value="{{ $u->id }}" {{ $inbox->target_user_id == $u->id ? 'selected' : '' }}>
                                                                                    {{ $u->nama_jalur ?? $u->email }} (ID: #{{ $u->id }})
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-medium text-slate-900" style="font-size: 13px;">Bonus Koin KP</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-text bg-light"><i class="ti ti-coin text-warning"></i></span>
                                                                            <input type="number" class="form-control" name="reward_coins" value="{{ $inbox->reward_coins }}" min="0" step="50">
                                                                        </div>
                                                                    </div>

                                                                    <div class="mb-4">
                                                                        <div class="form-check form-switch">
                                                                            <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active_{{ $inbox->id }}" {{ $inbox->is_active ? 'checked' : '' }}>
                                                                            <label class="form-check-label fw-medium text-slate-900" style="font-size: 13px;" for="edit_is_active_{{ $inbox->id }}">Aktifkan Pesan Ini</label>
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
                                        </tbody>
                                    </table>
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
        function toggleTargetUser(val, containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;
            if (val === 'user') {
                container.classList.remove('d-none');
            } else {
                container.classList.add('d-none');
            }
        }
    </script>
@endsection
