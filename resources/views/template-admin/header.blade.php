<header class="pc-header">
    <div class="header-wrapper">
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled mb-0 d-flex align-items-center">
                <!-- ======= Sidebar Toggle Button ===== -->
                <li class="pc-h-item pc-sidebar-collapse">
                    <a href="#" class="pc-head-link ms-0 text-slate-700" id="sidebar-hide">
                        <i class="ti ti-menu-2 f-20"></i>
                    </a>
                </li>
                <li class="pc-h-item pc-sidebar-popup">
                    <a href="#" class="pc-head-link ms-0 text-slate-700" id="mobile-collapse">
                        <i class="ti ti-menu-2 f-20"></i>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="ms-auto">
            <ul class="list-unstyled mb-0 d-flex align-items-center gap-2">
                <li class="dropdown pc-h-item header-user-profile">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0 d-flex align-items-center gap-2" data-bs-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside"
                        aria-expanded="false">
                        <img src="{{ Auth::user()->foto_profile ?? asset('admin/assets/images/user/avatar-2.jpg') }}" alt="user-image"
                            class="user-avtar border" style="width: 34px; height: 34px; object-fit: cover;">
                        <span class="fw-semibold text-slate-900" style="font-size: 13.5px;">{{ Auth::user()->nama_jalur ?? Auth::user()->email ?? 'Admin' }}</span>
                        <i class="ti ti-chevron-down text-slate-400 f-14"></i>
                    </a>
                    <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown shadow-lg border" style="border-radius: 12px; min-width: 220px;">
                        <div class="dropdown-header px-3 py-3 border-bottom bg-slate-50">
                            <div class="d-flex align-items-center">
                                <img src="{{ Auth::user()->foto_profile ?? asset('admin/assets/images/user/avatar-2.jpg') }}"
                                    alt="user-image" class="rounded-circle border me-2" style="width: 38px; height: 38px; object-fit: cover;">
                                <div class="overflow-hidden">
                                    <h6 class="mb-0 fw-bold text-slate-900 text-truncate" style="font-size: 13px;">{{ Auth::user()->nama_jalur ?? Auth::user()->email ?? 'Admin' }}</h6>
                                    <span class="shadcn-badge shadcn-badge-secondary" style="font-size: 10px; padding: 2px 6px;">{{ ucfirst(Auth::user()->role) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 d-flex flex-column gap-1">
                            <a href="{{ route('main-menu') }}" class="btn-shadcn-outline justify-content-start w-100" target="_blank">
                                <i class="ti ti-device-gamepad-2 me-2"></i> Ke Game
                            </a>
                            <a href="/logout" class="btn-shadcn-danger justify-content-start w-100 mt-1">
                                <i class="ti ti-power me-2"></i> Logout
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>