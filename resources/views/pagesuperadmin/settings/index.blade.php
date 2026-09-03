@extends('template-admin.layout')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- Header Title -->
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2 border-bottom">
                <div>
                    <h3 class="h4 fw-bold text-slate-900 mb-1">Pengaturan API KlikQRIS</h3>
                    <p class="text-muted text-sm mb-0">Konfigurasi kunci API dan ID Merchant untuk pemprosesan otomatis transaksi QRIS.</p>
                </div>
            </div>

            <!-- [ Main Content ] start -->
            <div class="row g-4">
                <div class="col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-header py-3">
                            <h6 class="mb-0 fw-bold text-slate-900">Konfigurasi Merchant</h6>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('superadmin.settings.save') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="klikqris_merchant_id" class="form-label fw-medium text-slate-900" style="font-size: 13px;">ID Merchant KlikQRIS</label>
                                    <input type="text" class="form-control" id="klikqris_merchant_id" name="klikqris_merchant_id" value="{{ $merchantId }}" required placeholder="Contoh: 178032012018">
                                    <div class="form-text text-muted" style="font-size: 11px;">ID Merchant unik dari dasbor KlikQRIS.</div>
                                </div>

                                <div class="mb-4">
                                    <label for="klikqris_api_key" class="form-label fw-medium text-slate-900" style="font-size: 13px;">API Key KlikQRIS</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="klikqris_api_key" name="klikqris_api_key" value="{{ $apiKey }}" required placeholder="Masukkan API Key Anda">
                                        <button class="btn-shadcn-outline" type="button" id="btnToggleApiKey" style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important;">
                                            <i class="ti ti-eye" id="toggleIcon"></i>
                                        </button>
                                    </div>
                                    <div class="form-text text-muted" style="font-size: 11px;">Kunci otorisasi rahasia untuk validasi webhook & callback payment.</div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn-shadcn-primary py-2">
                                        <i class="ti ti-device-floppy me-1"></i> Simpan Konfigurasi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12">
                    <div class="card bg-slate-50">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-slate-900 mb-3"><i class="ti ti-info-circle me-1 text-primary"></i> Panduan Kredensial</h6>
                            <p class="text-muted text-sm mb-3" style="line-height: 1.6;">
                                Kredensial ini digunakan backend game untuk berkomunikasi langsung dengan gateway <strong>KlikQRIS</strong> saat player membeli koin (KP).
                            </p>
                            <h6 class="fw-bold text-slate-900 text-xs text-uppercase mb-2" style="letter-spacing: 0.05em;">Langkah Sinkronisasi:</h6>
                            <ol class="text-muted text-sm ps-3 mb-4" style="line-height: 1.8;">
                                <li>Login ke dashboard KlikQRIS Anda.</li>
                                <li>Salin <strong>Merchant ID</strong> dan <strong>API Key</strong> dari menu integrasi pengembang.</li>
                                <li>Tempelkan data tersebut di form sebelah kiri lalu klik simpan.</li>
                                <li>Pastikan webhook global di dasbor KlikQRIS diarahkan ke: <br>
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
