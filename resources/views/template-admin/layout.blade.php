<!DOCTYPE html>
<html lang="id">
<head>
    <title>Pacu Jalur: The Pixel Race — Super Admin Panel</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Dashboard Super Admin Pacu Jalur: The Pixel Race. Kelola player, koin, item shop, sponsor, dan transaksi QRIS.">
    <meta name="author" content="Pacu Jalur Team">

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('game_pacu/assets/image/ui/sprint.png') }}" type="image/x-icon">
    
    <!-- [Google Fonts] Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&family=Press+Start+2P&display=swap" rel="stylesheet">
    
    <!-- [Icons] Tabler, Feather, FontAwesome -->
    <link rel="stylesheet" href="{{ asset('admin') }}/assets/fonts/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('admin') }}/assets/fonts/feather.css">
    <link rel="stylesheet" href="{{ asset('admin') }}/assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="{{ asset('admin') }}/assets/fonts/material.css">
    
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('admin') }}/assets/css/style.css" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('admin') }}/assets/css/style-preset.css">

    <!-- Modern Premium Design System -->
    <style>
        :root {
            --font-main: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-heading: 'Outfit', sans-serif;
            --font-pixel: 'Press Start 2P', monospace;

            --bg-body: #f8fafc;
            --sidebar-bg: #0b1329;
            --sidebar-border: #1e293b;
            
            --text-slate-900: #0f172a;
            --text-slate-700: #334155;
            --text-slate-500: #64748b;
            --text-slate-400: #94a3b8;
            --border-slate-200: #e2e8f0;
            --border-slate-300: #cbd5e1;

            --primary-emerald: #10b981;
            --primary-emerald-dark: #059669;
            --accent-cyan: #0284c7;
            --accent-amber: #f59e0b;
        }

        body {
            font-family: var(--font-main) !important;
            background-color: var(--bg-body) !important;
            color: var(--text-slate-900) !important;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: var(--font-heading) !important;
            letter-spacing: -0.02em;
        }

        /* Container Layout & Responsiveness */
        .pc-container {
            background-color: var(--bg-body) !important;
            min-height: 100vh;
            transition: all 0.25s ease-in-out;
        }

        .pc-content {
            padding: 24px 28px !important;
            max-width: 1600px;
            margin: 0 auto;
        }

        @media (max-width: 991.98px) {
            .pc-content {
                padding: 16px 14px !important;
            }
        }

        /* Sidebar Customization */
        .pc-sidebar {
            background: var(--sidebar-bg) !important;
            border-right: 1px solid var(--sidebar-border) !important;
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.15) !important;
        }

        .pc-sidebar .m-header {
            background: #080e1f !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            padding: 16px 20px !important;
        }

        .pc-sidebar .pc-caption label {
            color: #64748b !important;
            font-size: 10.5px !important;
            font-weight: 700 !important;
            letter-spacing: 0.08em !important;
            text-transform: uppercase !important;
            margin-top: 14px !important;
            margin-bottom: 6px !important;
        }

        .pc-sidebar .pc-link {
            border-radius: 10px !important;
            margin: 2px 10px !important;
            padding: 10px 14px !important;
            transition: all 0.2s ease !important;
        }

        .pc-sidebar .pc-mtext {
            color: #94a3b8 !important;
            font-size: 13.5px !important;
            font-weight: 500 !important;
        }

        .pc-sidebar .pc-micon {
            color: #64748b !important;
            font-size: 18px !important;
            transition: transform 0.2s ease, color 0.2s ease !important;
        }

        .pc-sidebar .pc-link:hover {
            background: rgba(255, 255, 255, 0.06) !important;
        }

        .pc-sidebar .pc-link:hover .pc-mtext,
        .pc-sidebar .pc-link:hover .pc-micon {
            color: #ffffff !important;
        }

        .pc-sidebar .pc-link:hover .pc-micon {
            transform: translateX(2px);
        }

        .pc-sidebar .pc-item.active > .pc-link {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(2, 132, 199, 0.2) 100%) !important;
            border: 1px solid rgba(16, 185, 129, 0.4) !important;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.15) !important;
        }

        .pc-sidebar .pc-item.active > .pc-link .pc-mtext {
            color: #38bdf8 !important;
            font-weight: 600 !important;
        }

        .pc-sidebar .pc-item.active > .pc-link .pc-micon {
            color: #34d399 !important;
        }

        /* Topbar Header */
        .pc-header {
            background: rgba(255, 255, 255, 0.92) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border-bottom: 1px solid var(--border-slate-200) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02) !important;
            z-index: 1020 !important;
        }

        /* Cards & Panels */
        .card {
            background-color: #ffffff !important;
            border: 1px solid var(--border-slate-200) !important;
            border-radius: 14px !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03), 0 4px 12px rgba(0, 0, 0, 0.02) !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05) !important;
            border-color: #cbd5e1 !important;
        }

        .card-header {
            background-color: #ffffff !important;
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 16px 20px !important;
            border-top-left-radius: 14px !important;
            border-top-right-radius: 14px !important;
        }

        /* Tables & Responsive Display */
        .table-responsive {
            -webkit-overflow-scrolling: touch;
            border-radius: 0 0 14px 14px;
        }

        .table-responsive::-webkit-scrollbar {
            height: 6px;
        }
        .table-responsive::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .table-responsive::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .table {
            margin-bottom: 0 !important;
            width: 100% !important;
        }

        .table th {
            font-size: 11px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.06em !important;
            color: var(--text-slate-500) !important;
            background-color: #f8fafc !important;
            border-bottom: 1px solid var(--border-slate-200) !important;
            padding: 12px 16px !important;
            white-space: nowrap;
        }

        .table td {
            padding: 12px 16px !important;
            border-bottom: 1px solid #f1f5f9 !important;
            vertical-align: middle !important;
            font-size: 13.5px !important;
            color: var(--text-slate-700) !important;
        }

        .table tbody tr:hover {
            background-color: #f8fafc !important;
        }

        /* Badges */
        .shadcn-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 11px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 600;
            line-height: 1;
            white-space: nowrap;
        }

        .shadcn-badge-success {
            background-color: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .shadcn-badge-danger {
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .shadcn-badge-warning {
            background-color: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .shadcn-badge-coin {
            background-color: #fefce8;
            color: #a16207;
            border: 1px solid #fef08a;
        }

        .shadcn-badge-secondary {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        /* Modern Buttons */
        .btn-shadcn-primary {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
            color: #ffffff !important;
            border: 1px solid #0f172a !important;
            border-radius: 10px !important;
            padding: 8px 16px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 4px rgba(15, 23, 42, 0.15) !important;
            transition: all 0.2s ease !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .btn-shadcn-primary:hover {
            background: #1e293b !important;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.25) !important;
        }

        .btn-shadcn-outline {
            background-color: #ffffff !important;
            color: #0f172a !important;
            border: 1px solid var(--border-slate-200) !important;
            border-radius: 10px !important;
            padding: 8px 16px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .btn-shadcn-outline:hover {
            background-color: #f8fafc !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
            transform: translateY(-1px);
        }

        .btn-shadcn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            color: #ffffff !important;
            border: 1px solid #dc2626 !important;
            border-radius: 10px !important;
            padding: 8px 16px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            transition: all 0.2s ease !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .btn-shadcn-danger:hover {
            background: #dc2626 !important;
            color: #ffffff !important;
            transform: translateY(-1px);
        }

        .btn-shadcn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: #ffffff !important;
            border: 1px solid #059669 !important;
            border-radius: 10px !important;
            padding: 8px 16px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            transition: all 0.2s ease !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .btn-shadcn-success:hover {
            background: #059669 !important;
            color: #ffffff !important;
            transform: translateY(-1px);
        }

        /* Inputs & Form Controls */
        .form-control, .form-select {
            border-radius: 10px !important;
            border: 1px solid var(--border-slate-200) !important;
            padding: 9px 14px !important;
            font-size: 13.5px !important;
            color: var(--text-slate-900) !important;
            background-color: #ffffff !important;
            box-shadow: none !important;
            transition: border-color 0.2s ease, box-shadow 0.2s ease !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: #0284c7 !important;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.12) !important;
        }

        /* Modal Customization */
        .modal-content {
            border-radius: 16px !important;
            border: 1px solid var(--border-slate-200) !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important;
            overflow: hidden;
        }

        .modal-header {
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 18px 24px !important;
            background: #f8fafc;
        }

        .modal-footer {
            border-top: 1px solid #f1f5f9 !important;
            padding: 14px 24px !important;
            background: #f8fafc;
        }

        /* Footer */
        .pc-footer {
            padding: 16px 28px !important;
            background: #ffffff !important;
            border-top: 1px solid var(--border-slate-200) !important;
            font-size: 12.5px;
            color: var(--text-slate-500);
        }

        /* Mobile Optimization */
        @media (max-width: 767.98px) {
            .pc-header {
                height: 60px !important;
            }

            .card-header {
                padding: 14px 16px !important;
            }

            .card-body {
                padding: 16px !important;
            }

            .btn-shadcn-primary, .btn-shadcn-outline, .btn-shadcn-danger, .btn-shadcn-success {
                padding: 7px 12px !important;
                font-size: 12px !important;
            }

            .table td, .table th {
                padding: 10px 12px !important;
                font-size: 12.5px !important;
            }
        }
    </style>
    @yield('style')
</head>
<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Sidebar Menu ] start -->
    @include('template-admin.navbar')
    <!-- [ Sidebar Menu ] end -->

    <!-- [ Header Topbar ] start -->
    @include('template-admin.header')
    <!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    @yield('content')
    <!-- [ Main Content ] end -->

    <!-- [ Footer ] start -->
    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row align-items-center justify-content-between">
                <div class="col-sm my-1">
                    <p class="m-0 fw-medium">
                        🏁 <strong>Pacu Jalur: The Pixel Race</strong> &copy; {{ date('Y') }} Admin Management Panel.
                    </p>
                </div>
                <div class="col-auto my-1">
                    <ul class="list-inline footer-link mb-0">
                        <li class="list-inline-item"><a href="{{ route('main-menu') }}" target="_blank" class="text-emerald-600 fw-semibold" style="color: #059669;">🎮 Buka Game</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <!-- [ Footer ] end -->

    @include('sweetalert::alert')

    @yield('script')

    <!-- [Required JS] -->
    <script src="{{ asset('admin') }}/assets/js/plugins/popper.min.js"></script>
    <script src="{{ asset('admin') }}/assets/js/plugins/simplebar.min.js"></script>
    <script src="{{ asset('admin') }}/assets/js/plugins/bootstrap.min.js"></script>
    <script src="{{ asset('admin') }}/assets/js/fonts/custom-font.js"></script>
    <script src="{{ asset('admin') }}/assets/js/pcoded.js"></script>
    <script src="{{ asset('admin') }}/assets/js/plugins/feather.min.js"></script>

    <!-- Datatable JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('admin') }}/assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="{{ asset('admin') }}/assets/js/plugins/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            if ($('#simpletable').length) {
                $('#simpletable').DataTable({
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        paginate: {
                            first: "Awal",
                            last: "Akhir",
                            next: "Next →",
                            previous: "← Prev"
                        },
                        zeroRecords: "Data tidak ditemukan",
                    }
                });
            }
        });
    </script>
</body>
</html>
