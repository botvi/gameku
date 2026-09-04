@extends('template-admin.layout')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- Header Title -->
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2 border-bottom">
                <div>
                    <h3 class="h4 fw-bold text-slate-900 mb-1">Pengaturan Sistem & Game</h3>
                    <p class="text-muted text-sm mb-0">Kelola konfigurasi layar game dan kredensial API KlikQRIS dalam kolom terpisah.</p>
                </div>
            </div>

            <!-- [ Main Content ] start -->
            <div class="row g-4">
                <!-- COL 1: Pengaturan Game & Auto Fullscreen -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header py-3 bg-white border-bottom d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 fw-bold text-slate-900">
                                <i class="ti ti-device-gamepad-2 me-2 text-primary"></i> Pengaturan Game (Auto Fullscreen)
                            </h6>
                            <span class="badge {{ $fullscreen == 1 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} border px-2 py-1" style="font-size: 11px;">
                                {{ $fullscreen == 1 ? 'AKTIF (ON)' : 'NONAKTIF (OFF)' }}
                            </span>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('superadmin.settings.save') }}" method="POST">
                                @csrf
                                <div class="row align-items-center mb-3">
                                    <div class="col-md-9 col-sm-12 mb-3 mb-md-0">
                                        <h6 class="fw-bold text-slate-900 mb-1" style="font-size: 14px;">Auto Fullscreen Game</h6>
                                        <p class="text-muted text-sm mb-0" style="line-height: 1.5;">
                                            Jika diaktifkan (ON / 1), game akan secara otomatis meminta tampilan layar penuh (*fullscreen*) saat pemain membuka game dan melakukan interaksi/ketukan layar pertama.
                                        </p>
                                    </div>
                                    <div class="col-md-3 col-sm-12 text-md-end">
                                        <div class="form-check form-switch d-inline-block p-0 m-0">
                                            <input class="form-check-input ms-0" type="checkbox" id="fullscreen" name="fullscreen" value="1" {{ $fullscreen == 1 ? 'checked' : '' }} style="width: 52px; height: 26px; cursor: pointer;">
                                        </div>
                                    </div>
                                </div>
                                {{-- Preserve existing KlikQRIS values when submitting game settings --}}
                                <input type="hidden" name="klikqris_merchant_id" value="{{ $merchantId }}">
                                <input type="hidden" name="klikqris_api_key" value="{{ $apiKey }}">

                                <div class="text-end border-top pt-3">
                                    <button type="submit" class="btn-shadcn-primary py-2 px-4">
                                        <i class="ti ti-device-floppy me-1"></i> Simpan Pengaturan Game
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- COL 2: Kredensial KlikQRIS API (Kolom Terpisah) -->
                <div class="col-md-6 col-sm-12" id="klikqris">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header py-3 bg-white border-bottom">
                            <h6 class="mb-0 fw-bold text-slate-900">
                                <i class="ti ti-key me-2 text-primary"></i> Kolom Kredensial KlikQRIS
                            </h6>
                        </div>
                        <div class="card-body p-4 d-flex flex-column justify-content-between">
                            <form action="{{ route('superadmin.settings.save') }}" method="POST">
                                @csrf
                                {{-- Preserve fullscreen boolean --}}
                                @if($fullscreen == 1)
                                    <input type="hidden" name="fullscreen" value="1">
                                @endif

                                <div class="mb-3">
                                    <label for="klikqris_merchant_id" class="form-label fw-medium text-slate-900" style="font-size: 13px;">ID Merchant KlikQRIS</label>
                                    <input type="text" class="form-control" id="klikqris_merchant_id" name="klikqris_merchant_id" value="{{ $merchantId }}" placeholder="Contoh: 178032012018">
                                    <div class="form-text text-muted" style="font-size: 11px;">ID Merchant unik dari dasbor KlikQRIS.</div>
                                </div>

                                <div class="mb-4">
                                    <label for="klikqris_api_key" class="form-label fw-medium text-slate-900" style="font-size: 13px;">API Key KlikQRIS</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="klikqris_api_key" name="klikqris_api_key" value="{{ $apiKey }}" placeholder="Masukkan API Key Anda">
                                        <button class="btn-shadcn-outline" type="button" id="btnToggleApiKey" style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important;">
                                            <i class="ti ti-eye" id="toggleIcon"></i>
                                        </button>
                                    </div>
                                    <div class="form-text text-muted" style="font-size: 11px;">Kunci otorisasi rahasia untuk validasi webhook & callback payment.</div>
                                </div>

                                <div class="text-end border-top pt-3 mt-auto">
                                    <button type="submit" class="btn-shadcn-primary py-2 px-4">
                                        <i class="ti ti-device-floppy me-1"></i> Simpan Kredensial KlikQRIS
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Panduan Integrasi KlikQRIS -->
                <div class="col-md-6 col-sm-12">
                    <div class="card bg-slate-50 border-0 h-100 shadow-sm">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-slate-900 mb-3"><i class="ti ti-info-circle me-1 text-primary"></i> Panduan Kredensial KlikQRIS</h6>
                            <p class="text-muted text-sm mb-3" style="line-height: 1.6;">
                                Kredensial ini disimpan terpisah untuk berkomunikasi langsung dengan gateway <strong>KlikQRIS</strong> saat player melakukan isi ulang koin (KP).
                            </p>
                            <h6 class="fw-bold text-slate-900 text-xs text-uppercase mb-2" style="letter-spacing: 0.05em;">Langkah Sinkronisasi:</h6>
                            <ol class="text-muted text-sm ps-3 mb-4" style="line-height: 1.8;">
                                <li>Login ke dashboard KlikQRIS Anda.</li>
                                <li>Salin <strong>Merchant ID</strong> dan <strong>API Key</strong> dari menu integrasi pengembang.</li>
                                <li>Pastikan URL Webhook di dasbor KlikQRIS diarahkan ke: <br>
                                    <code class="bg-white px-2 py-1 rounded border text-xs font-mono select-all text-primary d-inline-block mt-1">{{ route('klikqris.webhook') }}</code>
                                </li>
                            </ol>
                            <div class="shadcn-badge shadcn-badge-warning p-3 w-100 d-flex align-items-start gap-2" style="border-radius: 8px;">
                                <i class="ti ti-alert-triangle f-18 flex-shrink-0 mt-1"></i>
                                <div>
                                    <strong class="d-block mb-1">Penting:</strong> Selalu amankan API Key Anda. Jangan membagikan kunci rahasia ini ke pihak mana pun.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>

    @section('script')
        <script>
            document.getElementById('btnToggleApiKey').addEventListener('click', function () {
                var apiKeyInput = document.getElementById('klikqris_api_key');
                var toggleIcon = document.getElementById('toggleIcon');
                if (apiKeyInput.type === "password") {
                    apiKeyInput.type = "text";
                    toggleIcon.classList.remove('ti-eye');
                    toggleIcon.classList.add('ti-eye-off');
                } else {
                    apiKeyInput.type = "password";
                    toggleIcon.classList.remove('ti-eye-off');
                    toggleIcon.classList.add('ti-eye');
                }
            });
        </script>
    @endsection
@endsection
