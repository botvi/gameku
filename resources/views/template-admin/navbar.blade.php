<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header d-flex align-items-center justify-content-between px-3 py-3 border-bottom border-secondary">
            <a href="{{ route('dashboard-superadmin') }}" class="b-brand text-primary d-flex align-items-center gap-2">
                <img src="{{ asset('env') }}/logo_text.png" alt="Logo" style="height: 34px; object-fit: contain;">
                <span class="badge bg-primary text-xs" style="font-size: 10px;">ADMIN</span>
            </a>
        </div>
        <div class="navbar-content py-2">
            <ul class="pc-navbar">
                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin')
                    <li class="pc-item {{ request()->routeIs('dashboard-superadmin') ? 'active' : '' }}">
                        <a href="{{ route('dashboard-superadmin') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label class="text-uppercase text-xs font-semibold text-slate-400">Manajemen User</label>
                    </li>
                    <li class="pc-item {{ request()->routeIs('superadmin.users') ? 'active' : '' }}">
                        <a href="{{ route('superadmin.users') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">Daftar Player</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label class="text-uppercase text-xs font-semibold text-slate-400">Manajemen Koin & Topup</label>
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

                    <li class="pc-item pc-caption">
                        <label class="text-uppercase text-xs font-semibold text-slate-400">Manajemen Shop</label>
                    </li>
                    <li class="pc-item {{ request()->routeIs('superadmin.shop-items') ? 'active' : '' }}">
                        <a href="{{ route('superadmin.shop-items') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-shopping-cart"></i></span>
                            <span class="pc-mtext">Item Shop</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label class="text-uppercase text-xs font-semibold text-slate-400">Pengaturan</label>
                    </li>
                    <li class="pc-item {{ request()->routeIs('superadmin.settings') ? 'active' : '' }}">
                        <a href="{{ route('superadmin.settings') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-key"></i></span>
                            <span class="pc-mtext">KlikQRIS Credentials</span>
                        </a>
                    </li>
                    <li class="pc-item mt-3">
                        <a href="{{ route('main-menu') }}" class="pc-link text-info" target="_blank">
                            <span class="pc-micon"><i class="ti ti-device-gamepad-2"></i></span>
                            <span class="pc-mtext">Masuk ke Game ↗</span>
                        </a>
                    </li>
                @elseif (Auth::user()->role == 'asisten')
                    <li class="pc-item">
                        <a href="/" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    <li class="pc-item pc-caption">
                        <label>Data Panenpro</label>
                        <i class="ti ti-dashboard"></i>
                    </li>
                    <li class="pc-item">
                        <a href="" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-user"></i></span>
                            <span class="pc-mtext">Data Elemen</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
