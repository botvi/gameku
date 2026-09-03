<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>Home | Panenpro</title>
    <!-- [Meta] -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
    <meta name="keywords"
        content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
    <meta name="author" content="CodedThemes">

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('env') }}/logo.png" type="image/x-icon">
    <!-- [Google Font] Family -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        id="main-font-link">
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('admin') }}/assets/fonts/tabler-icons.min.css">
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ asset('admin') }}/assets/fonts/feather.css">
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('admin') }}/assets/fonts/fontawesome.css">
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('admin') }}/assets/fonts/material.css">
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('admin') }}/assets/css/style.css" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('admin') }}/assets/css/style-preset.css">

    <!-- Shadcn UI Design System -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        :root {
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --bg-slate-50: #f8fafc;
            --border-slate-200: #e2e8f0;
            --border-slate-300: #cbd5e1;
            --text-slate-900: #0f172a;
            --text-slate-700: #334155;
            --text-slate-500: #64748b;
            --text-slate-400: #94a3b8;
            --sidebar-bg: #0f172a;
            --primary-slate: #0f172a;
        }

        body {
            font-family: var(--font-sans) !important;
            background-color: var(--bg-slate-50) !important;
            color: var(--text-slate-900) !important;
            -webkit-font-smoothing: antialiased;
        }

        /* Page Container */
        .pc-container {
            background-color: var(--bg-slate-50) !important;
            min-height: 100vh;
        }
        .pc-content {
            padding: 24px 32px !important;
        }

        /* Shadcn Card */
        .card {
            background-color: #ffffff !important;
            border: 1px solid var(--border-slate-200) !important;
            border-radius: 12px !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05) !important;
            transition: all 0.2s ease-in-out;
        }

        .card-header {
            background-color: #ffffff !important;
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 18px 24px !important;
        }

        /* Shadcn Table */
        .table {
            margin-bottom: 0 !important;
        }
        .table th {
            font-size: 11px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            color: var(--text-slate-500) !important;
            background-color: #f8fafc !important;
            border-bottom: 1px solid var(--border-slate-200) !important;
            padding: 12px 16px !important;
        }
        .table td {
            padding: 14px 16px !important;
            border-bottom: 1px solid #f1f5f9 !important;
            vertical-align: middle !important;
            font-size: 13.5px !important;
            color: var(--text-slate-700) !important;
        }
        .table tbody tr:hover {
            background-color: #f8fafc !important;
        }

        /* Shadcn Badges */
        .shadcn-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 11.5px;
            font-weight: 600;
            line-height: 1;
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

        /* Shadcn Buttons */
        .btn-shadcn-primary {
            background-color: #0f172a !important;
            color: #ffffff !important;
            border: 1px solid #0f172a !important;
            border-radius: 8px !important;
            padding: 8px 16px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            transition: all 0.15s ease-in-out !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-shadcn-primary:hover {
            background-color: #1e293b !important;
            border-color: #1e293b !important;
            color: #ffffff !important;
            transform: translateY(-1px);
        }
        
        .btn-shadcn-outline {
            background-color: #ffffff !important;
            color: #0f172a !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 8px 16px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            transition: all 0.15s ease-in-out !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-shadcn-outline:hover {
            background-color: #f8fafc !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }

        .btn-shadcn-danger {
            background-color: #ef4444 !important;
            color: #ffffff !important;
            border: 1px solid #dc2626 !important;
            border-radius: 8px !important;
            padding: 8px 16px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            transition: all 0.15s ease-in-out !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-shadcn-danger:hover {
            background-color: #dc2626 !important;
            color: #ffffff !important;
        }

        .btn-shadcn-success {
            background-color: #10b981 !important;
            color: #ffffff !important;
            border: 1px solid #059669 !important;
            border-radius: 8px !important;
            padding: 8px 16px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            transition: all 0.15s ease-in-out !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-shadcn-success:hover {
            background-color: #059669 !important;
            color: #ffffff !important;
        }

        /* Shadcn Inputs */
        .form-control, .form-select {
            border-radius: 8px !important;
            border: 1px solid var(--border-slate-200) !important;
            padding: 9px 13px !important;
            font-size: 13.5px !important;
            color: var(--text-slate-900) !important;
            box-shadow: none !important;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0f172a !important;
            box-shadow: 0 0 0 2px rgba(15, 23, 42, 0.1) !important;
        }

        /* Modal Shadcn styling */
        .modal-content {
            border-radius: 14px !important;
            border: 1px solid var(--border-slate-200) !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
        }
        .modal-header {
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 18px 24px !important;
        }
        .modal-footer {
            border-top: 1px solid #f1f5f9 !important;
            padding: 14px 24px !important;
        }

        /* Header & Navbar styling override */
        .pc-sidebar {
            background: #0f172a !important;
            border-right: 1px solid #1e293b !important;
        }
        .pc-sidebar .pc-mtext, .pc-sidebar .pc-micon {
            color: #94a3b8 !important;
        }
        .pc-sidebar .pc-link:hover .pc-mtext, 
        .pc-sidebar .pc-link:hover .pc-micon {
            color: #ffffff !important;
        }
        .pc-sidebar .pc-item.active > .pc-link {
            background: rgba(255, 255, 255, 0.08) !important;
            border-radius: 8px !important;
        }
        .pc-sidebar .pc-item.active > .pc-link .pc-mtext,
        .pc-sidebar .pc-item.active > .pc-link .pc-micon {
            color: #38bdf8 !important;
        }

        .pc-header {
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0 !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03) !important;
        }

        @media (max-width: 1024px) {
            .pc-content {
                padding: 16px !important;
            }
        }
    </style>
    @yield('style')

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

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
	
	
    <!-- [ Sidebar Menu ] end --> <!-- [ Header Topbar ] start -->
    @include('template-admin.header')
   
    <!-- [ Header ] end -->



    <!-- [ Main Content ] start -->
	@yield('content')

    <!-- [ Main Content ] end -->
    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row">
                <div class="col-sm my-1">
                    <p class="m-0">Panenpro &#9829;</p>
                </div>
                <div class="col-auto my-1">
                    <ul class="list-inline footer-link mb-0">
                        <li class="list-inline-item"><a href="{{ asset('admin') }}/index.html">Home</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <!--end switcher-->
    @include('sweetalert::alert')

    @yield('script')
    <!-- [Page Specific JS] start -->
    <script src="{{ asset('admin') }}/assets/js/plugins/apexcharts.min.js"></script>
    <script src="{{ asset('admin') }}/assets/js/pages/dashboard-default.js"></script>
    <!-- [Page Specific JS] end -->
    <!-- Required Js -->
    <script src="{{ asset('admin') }}/assets/js/plugins/popper.min.js"></script>
    <script src="{{ asset('admin') }}/assets/js/plugins/simplebar.min.js"></script>
    <script src="{{ asset('admin') }}/assets/js/plugins/bootstrap.min.js"></script>
    <script src="{{ asset('admin') }}/assets/js/fonts/custom-font.js"></script>
    <script src="{{ asset('admin') }}/assets/js/pcoded.js"></script>
    <script src="{{ asset('admin') }}/assets/js/plugins/feather.min.js"></script>





    <script>
        layout_change('light');
    </script>




    <script>
        change_box_container('false');
    </script>



    <script>
        layout_rtl_change('false');
    </script>


    <script>
        preset_change("preset-1");
    </script>


    <script>
        font_change("Public-Sans");
    </script>

 <!-- [Page Specific JS] start -->
    <!-- datatable Js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('admin') }}/assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="{{ asset('admin') }}/assets/js/plugins/dataTables.bootstrap5.min.js"></script>
    <script>
      // [ Zero Configuration ] start
      $('#simpletable').DataTable();

      // [ Default Ordering ] start
      $('#order-table').DataTable({
        order: [[3, 'desc']]
      });

      // [ Multi-Column Ordering ]
      $('#multi-colum-dt').DataTable({
        columnDefs: [
          {
            targets: [0],
            orderData: [0, 1]
          },
          {
            targets: [1],
            orderData: [1, 0]
          },
          {
            targets: [4],
            orderData: [4, 0]
          }
        ]
      });

      // [ Complex Headers ]
      $('#complex-dt').DataTable();

      // [ DOM Positioning ]
      $('#DOM-dt').DataTable({
        dom: '<"top"i>rt<"bottom"flp><"clear">'
      });

      // [ Alternative Pagination ]
      $('#alt-pg-dt').DataTable({
        pagingType: 'full_numbers'
      });

      // [ Scroll - Vertical ]
      $('#scr-vrt-dt').DataTable({
        scrollY: '200px',
        scrollCollapse: true,
        paging: false
      });

      // [ Scroll - Vertical, Dynamic Height ]
      $('#scr-vtr-dynamic').DataTable({
        scrollY: '50vh',
        scrollCollapse: true,
        paging: false
      });

      // [ Language - Comma Decimal Place ]
      $('#lang-dt').DataTable({
        language: {
          decimal: ',',
          thousands: '.'
        }
      });
    </script>
    <!-- [Page Specific JS] end -->

</body>
<!-- [Body] end -->

</html>
