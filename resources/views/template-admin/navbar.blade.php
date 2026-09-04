<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <!-- Logo Brand Header -->
        <div class="m-header d-flex align-items-center justify-content-between px-3 py-3">
            <a href="{{ route('dashboard-superadmin') }}" class="b-brand d-flex align-items-center gap-2 text-decoration-none">
                <div class="d-flex align-items-center justify-content-center rounded-3 bg-emerald-500 text-white shadow-sm" style="width: 36px; height: 36px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: 1px solid #34d399;">
                    <i class="ti ti-trophy f-20"></i>
                </div>
                <div class="d-flex flex-column">
                    <span class="fw-bold text-white font-heading" style="font-size: 14px; letter-spacing: -0.01em; line-height: 1.1;">Pacu Jalur</span>
                    <span class="text-emerald-400 font-pixel" style="font-size: 8px; color: #34d399; letter-spacing: 0.5px;">THE PIXEL RACE</span>
                </div>
            </a>
            <span class="shadcn-badge shadcn-badge-success" style="font-size: 9px; padding: 2px 7px;">ADMIN</span>
        </div>

        <div class="navbar-content py-2">
            <ul class="pc-navbar">
                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin')
                    <!-- Dashboard -->
                    <li class="pc-item {{ request()->routeIs('dashboard-superadmin') ? 'active' : '' }}">
                        <a href="{{ route('dashboard-superadmin') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard Overview</span>
                        </a>
                    </li>

                    <!-- Manajemen User -->
                    <li class="pc-item pc-caption">
                        <label>Manajemen Player</label>
                    </li>
                    <li class="pc-item {{ request()->routeIs('superadmin.users') ? 'active' : '' }}">
                        <a href="{{ route('superadmin.users') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">Daftar Player</span>
                        </a>
                    </li>

                    <!-- Manajemen Koin & Topup -->
                    <li class="pc-item pc-caption">
                        <label>Koin & Pembayaran</label>
                    </li>
                    <li class="pc-item {{ request()->routeIs('superadmin.packages') ? 'active' : '' }}">
                        <a href="{{ route('superadmin.packages') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-coin"></i></span>
                            <span class="pc-mtext">Paket Koin & Harga</span>
                        </a>
                    </li>
                    <li class="pc-item {{ request()->routeIs('superadmin.transactions') ? 'active' : '' }}">
                        <a href="{{ route('superadmin.transactions') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-receipt"></i></span>
                            <span class="pc-mtext">Riwayat Transaksi</span>
                        </a>
                    </li>

                    <!-- Manajemen Catalog -->
                    <li class="pc-item pc-caption">
                        <label>Katalog & Promosi</label>
                    </li>
                    <li class="pc-item {{ request()->routeIs('superadmin.shop-items') ? 'active' : '' }}">
                        <a href="{{ route('superadmin.shop-items') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-shopping-cart"></i></span>
                            <span class="pc-mtext">Item Shop</span>
                        </a>
                    </li>
                    <li class="pc-item {{ request()->routeIs('superadmin.sponsors') ? 'active' : '' }}">
                        <a href="{{ route('superadmin.sponsors') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-photo"></i></span>
                            <span class="pc-mtext">Spanduk Sponsor</span>
                        </a>
                    </li>
                    <li class="pc-item {{ request()->routeIs('superadmin.inbox') ? 'active' : '' }}">
                        <a href="{{ route('superadmin.inbox') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-mail"></i></span>
                            <span class="pc-mtext">Inbox & Pengumuman</span>
                        </a>
                    </li>

                    <!-- Pengaturan -->
                    <li class="pc-item pc-caption">
                        <label>Sistem & Konfigurasi</label>
                    </li>
                    <li class="pc-item {{ request()->routeIs('superadmin.settings') ? 'active' : '' }}">
                        <a href="{{ route('superadmin.settings') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-settings"></i></span>
                            <span class="pc-mtext">Pengaturan Game</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="{{ route('superadmin.settings') }}#klikqris" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-key"></i></span>
                            <span class="pc-mtext">Kredensial KlikQRIS</span>
                        </a>
                    </li>

                    <!-- Quick Game Launcher -->
                    <li class="pc-item mt-4 px-2">
                        <a href="{{ route('main-menu') }}" class="btn-shadcn-success w-100 justify-content-center py-2" target="_blank" style="font-size: 12.5px !important; text-decoration: none;">
                            <i class="ti ti-device-gamepad-2 me-2 f-18"></i> Masuk ke Game ↗
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
