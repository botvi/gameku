<header class="pc-header">
    <div class="header-wrapper">
        <div class="me-auto pc-mob-drp d-flex align-items-center gap-2">
            <ul class="list-unstyled mb-0 d-flex align-items-center">
                <!-- ======= Sidebar Toggle Button (Desktop & Mobile) ===== -->
                <li class="pc-h-item pc-sidebar-collapse">
                    <a href="#" class="pc-head-link ms-0 text-slate-700 p-2 rounded-3 hover-bg" id="sidebar-hide" title="Toggle Sidebar">
                        <i class="ti ti-menu-2 f-20"></i>
                    </a>
                </li>
                <li class="pc-h-item pc-sidebar-popup">
                    <a href="#" class="pc-head-link ms-0 text-slate-700 p-2 rounded-3 hover-bg" id="mobile-collapse" title="Toggle Mobile Menu">
                        <i class="ti ti-menu-2 f-20"></i>
                    </a>
                </li>
            </ul>

            <!-- Game Branding Pill in Topbar -->
            <div class="d-none d-md-flex align-items-center gap-2 px-3 py-1 bg-slate-100 rounded-pill border">
                <span class="badge bg-emerald-500 rounded-circle p-1" style="background: #10b981;"></span>
                <span class="fw-semibold text-slate-800" style="font-size: 12px; letter-spacing: -0.01em;">Pacu Jalur: The Pixel Race</span>
                <span class="text-muted" style="font-size: 11px;">v1.0</span>
            </div>
        </div>

        <div class="ms-auto">
            <ul class="list-unstyled mb-0 d-flex align-items-center gap-3">
                <!-- Direct Link to Game -->
                <li class="d-none d-sm-inline-block">
                    <a href="{{ route('main-menu') }}" target="_blank" class="btn-shadcn-outline" style="padding: 6px 12px !important; font-size: 12px !important; text-decoration: none;">
                        <i class="ti ti-device-gamepad-2 me-1 text-emerald-600" style="color: #10b981;"></i> Main Game
                    </a>
                </li>

                <!-- User Profile Dropdown -->
                <li class="dropdown pc-h-item header-user-profile">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0 d-flex align-items-center gap-2 p-1 rounded-pill border bg-white shadow-xs"
                       data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                        <img src="{{ Auth::user()->foto_profile ?? asset('admin/assets/images/user/avatar-2.jpg') }}" alt="user-image"
                             class="rounded-circle border" style="width: 32px; height: 32px; object-fit: cover;">
                        <span class="fw-semibold text-slate-900 d-none d-md-inline-block pe-1" style="font-size: 13px;">
                            {{ Auth::user()->nama_jalur ?? Auth::user()->email ?? 'Admin' }}
                        </span>
                        <i class="ti ti-chevron-down text-slate-400 f-14 me-1"></i>
                    </a>
                    
                    <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown shadow-xl border mt-2" style="border-radius: 14px; min-width: 230px;">
                        <div class="dropdown-header px-3 py-3 border-bottom bg-slate-50">
                            <div class="d-flex align-items-center">
                                <img src="{{ Auth::user()->foto_profile ?? asset('admin/assets/images/user/avatar-2.jpg') }}"
                                     alt="user-image" class="rounded-circle border me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                <div class="overflow-hidden">
                                    <h6 class="mb-0 fw-bold text-slate-900 text-truncate" style="font-size: 13px;">
                                        {{ Auth::user()->nama_jalur ?? Auth::user()->email ?? 'Admin' }}
                                    </h6>
                                    <span class="shadcn-badge shadcn-badge-success mt-1" style="font-size: 9px; padding: 2px 6px;">
                                        <i class="ti ti-shield-check me-1"></i>{{ ucfirst(Auth::user()->role) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 d-flex flex-column gap-1">
                            <a href="{{ route('main-menu') }}" class="btn-shadcn-outline justify-content-start w-100" target="_blank" style="text-decoration: none;">
                                <i class="ti ti-device-gamepad-2 me-2 text-emerald-600" style="color: #10b981;"></i> Buka Game Pacu Jalur
                            </a>
                            <a href="/logout" class="btn-shadcn-danger justify-content-start w-100 mt-1" style="text-decoration: none;">
                                <i class="ti ti-power me-2"></i> Logout System
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>