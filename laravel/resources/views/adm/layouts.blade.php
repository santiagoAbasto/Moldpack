<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Panel de Control</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}?v=20260724">
    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}?v=20260724">

    <!-- Custom fonts for this template-->
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('css/sb-admin-2.min.css')}}" rel="stylesheet">
    {{-- summernote --}}
    <!-- include libraries(jQuery, bootstrap) -->
    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link href="{{asset('css/select2.css')}}" rel="stylesheet"/>

    <!--Alertify-->
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <!-- Default theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
    <!-- Semantic UI theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('slick/slick-theme.css')}}"/>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --admin-bg: #F4F6FA;
            --admin-surface: #FFFFFF;
            --admin-ink: #111827;
            --admin-muted: #667085;
            --admin-line: #D8DEE9;
            --admin-accent: #0B35B7;
            --admin-accent-soft: #EAF0FF;
            --admin-sidebar: #070A12;
            --admin-sidebar-panel: #101522;
            --admin-sidebar-line: rgba(255, 255, 255, 0.10);
            --admin-sidebar-text: #DCE4F2;
            --admin-sidebar-muted: #8F9BB0;
            --admin-pink: #D83282;
            --admin-danger: #E4002B;
            --admin-success: #17B26A;
            --admin-shadow: 0 22px 50px rgba(16, 24, 40, 0.10);
        }

        body {
            background: var(--admin-bg);
            color: var(--admin-ink);
            font-family: "Helvetica Neue", Arial, sans-serif;
        }

        .color {
            background:
                linear-gradient(180deg, rgba(11, 53, 183, 0.16) 0%, rgba(216, 50, 130, 0.10) 42%, rgba(7, 10, 18, 0) 78%),
                var(--admin-sidebar);
            border-right: 1px solid #121826;
            box-shadow: 18px 0 38px rgba(6, 10, 18, 0.22);
        }

        .sidebar {
            width: 14rem !important;
            padding: 10px 10px 16px;
            transition: width 220ms ease, padding 220ms ease;
        }

        .sidebar.toggled {
            width: 5.75rem !important;
            padding-left: 10px;
            padding-right: 10px;
        }

        .sidebar-dark .sidebar-brand {
            justify-content: flex-start !important;
            height: 66px;
            padding: 0 14px;
            margin: 0 0 10px;
            border: 1px solid var(--admin-sidebar-line);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.045);
            color: #FFFFFF;
            letter-spacing: 0;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
        }

        .admin-brand-mark {
            display: block;
            width: 138px;
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        .sidebar-dark .sidebar-brand span {
            color: #FFFFFF !important;
            font-size: 14px;
            font-weight: 900;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .sidebar.toggled .admin-brand-mark {
            margin-right: 0;
            width: 54px;
        }

        .sidebar.toggled .sidebar-brand {
            justify-content: center !important;
            height: 76px;
            padding: 0;
        }

        .sidebar.toggled .sidebar-brand span,
        .sidebar.toggled .nav-item .nav-link span {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .sidebar-divider {
            margin: 12px 0;
            border-top: 1px solid var(--admin-sidebar-line);
        }

        .sidebar .nav-item {
            margin: 4px 0;
        }

        .sidebar.toggled .nav-item {
            margin: 8px 0;
        }

        .sidebar-dark .nav-item .nav-link {
            display: flex;
            align-items: center;
            min-height: 46px;
            padding: 12px 12px;
            border: 1px solid transparent;
            border-radius: 8px;
            color: var(--admin-sidebar-text);
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 0;
            transition: background 180ms ease, border-color 180ms ease, color 180ms ease, transform 180ms ease;
        }

        .sidebar.toggled .nav-item .nav-link {
            justify-content: center;
            width: 56px !important;
            min-height: 56px;
            padding: 0 !important;
            margin: 0 auto;
            border-color: rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.055);
        }

        .sidebar-dark .nav-item .nav-link i {
            display: inline-flex;
            justify-content: center;
            width: 24px;
            margin-right: 10px;
            color: #7FA1FF;
            font-size: 14px;
            opacity: 1;
            transition: color 180ms ease, transform 180ms ease;
        }

        .sidebar.toggled .nav-item .nav-link i {
            width: 24px;
            margin-right: 0;
            font-size: 18px;
        }

        .sidebar-dark .nav-item .nav-link:hover,
        .sidebar-dark .nav-item .nav-link:focus,
        .sidebar-dark .nav-item .nav-link[aria-expanded="true"] {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.09);
            border-color: rgba(255, 255, 255, 0.13);
            transform: translateX(2px);
            text-decoration: none;
        }

        .sidebar.toggled .nav-item .nav-link:hover,
        .sidebar.toggled .nav-item .nav-link:focus,
        .sidebar.toggled .nav-item .nav-link[aria-expanded="true"] {
            transform: translateX(0);
            background: rgba(216, 50, 130, 0.18);
            border-color: rgba(216, 50, 130, 0.36);
        }

        .sidebar-dark .nav-item .nav-link:hover i,
        .sidebar-dark .nav-item .nav-link:focus i,
        .sidebar-dark .nav-item .nav-link[aria-expanded="true"] i {
            color: var(--admin-pink);
            transform: scale(1.04);
        }

        .sidebar-dark .nav-item .nav-link[data-toggle="collapse"]::after {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            min-width: 32px;
            margin-left: auto;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.10);
            color: #FFFFFF;
            font-size: 17px;
            line-height: 1;
            opacity: 1;
            transition: background 180ms ease, color 180ms ease, transform 180ms ease;
        }

        .sidebar.toggled .nav-item .nav-link[data-toggle="collapse"]::after {
            display: none;
        }

        .sidebar-dark .nav-item .nav-link[data-toggle="collapse"]:hover::after,
        .sidebar-dark .nav-item .nav-link[data-toggle="collapse"]:focus::after,
        .sidebar-dark .nav-item .nav-link[data-toggle="collapse"][aria-expanded="true"]::after {
            background: rgba(216, 50, 130, 0.22);
            color: #FFFFFF;
        }

        .sidebar .collapse-inner {
            margin: 6px 0 10px 34px;
            padding: 8px !important;
            border: 1px solid var(--admin-sidebar-line);
            border-radius: 8px !important;
            background: rgba(16, 21, 34, 0.98) !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08), 0 16px 32px rgba(0, 0, 0, 0.22);
        }

        .sidebar.toggled .nav-item .collapse,
        .sidebar.toggled .nav-item .collapsing {
            left: calc(5.75rem + 10px);
            top: 0;
            margin: 0;
        }

        .sidebar.toggled .nav-item .collapse-inner,
        .sidebar.toggled .nav-item .collapsing .collapse-inner {
            min-width: 190px;
            margin: 0;
        }

        .sidebar .collapse-inner .collapse-item {
            display: flex;
            align-items: center;
            min-height: 42px;
            padding: 10px 12px !important;
            border-radius: 6px !important;
            color: #F8FAFC !important;
            font-size: 13.5px;
            font-weight: 850;
            white-space: normal;
            opacity: 0.96;
            background: transparent !important;
        }

        .sidebar .collapse-inner .collapse-item:link,
        .sidebar .collapse-inner .collapse-item:visited {
            color: #F8FAFC !important;
            background: transparent !important;
        }

        .sidebar .collapse-inner .collapse-item:hover,
        .sidebar .collapse-inner .collapse-item:focus {
            color: #FFFFFF !important;
            background: rgba(216, 50, 130, 0.28) !important;
            text-decoration: none;
            opacity: 1;
        }

        .sidebar #sidebarToggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            margin: 14px auto 6px;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            border-radius: 14px !important;
            background: rgba(255, 255, 255, 0.11);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
            transition: background 180ms ease, border-color 180ms ease, transform 180ms ease;
        }

        .sidebar #sidebarToggle::after {
            margin: 0;
            color: #FFFFFF;
            font-size: 18px;
            line-height: 1;
        }

        .sidebar #sidebarToggle:hover,
        .sidebar #sidebarToggle:focus {
            background: rgba(216, 50, 130, 0.22);
            border-color: rgba(216, 50, 130, 0.40) !important;
            transform: translateY(-1px);
            outline: 0;
        }

        .sidebar.toggled #sidebarToggle {
            width: 56px;
            height: 56px;
            margin-top: 16px;
        }

        #content-wrapper {
            background:
                linear-gradient(180deg, #FFFFFF 0, #F4F6FA 260px),
                var(--admin-bg);
        }

        .topbar {
            height: 76px;
            border-bottom: 1px solid var(--admin-line);
            box-shadow: 0 14px 36px rgba(16, 24, 40, 0.06) !important;
        }

        .admin-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .admin-topbar-title {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--admin-ink);
            font-size: 13px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .admin-topbar-title::before {
            content: "";
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: var(--admin-success);
            box-shadow: 0 0 0 5px rgba(23, 178, 106, 0.12);
        }

        .admin-topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-user-block {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 44px;
            padding: 8px 16px;
            border-left: 1px solid var(--admin-line);
            border-right: 1px solid var(--admin-line);
            background: #FFFFFF;
        }

        .admin-user-block strong {
            display: block;
            color: var(--admin-ink);
            font-size: 14px;
            line-height: 1;
        }

        .admin-user-block span {
            color: var(--admin-muted);
            font-size: 12px;
        }

        .container-fluid {
            padding: 34px 38px;
        }

        .admin-page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            padding-bottom: 22px;
            margin-bottom: 24px;
            border-bottom: 1px solid var(--admin-line);
        }

        .admin-page-header h1 {
            margin: 0;
            color: var(--admin-ink);
            font-size: 30px;
            font-weight: 800;
            letter-spacing: 0;
        }

        .admin-eyebrow {
            display: block;
            margin-bottom: 8px;
            color: var(--admin-accent);
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .admin-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 10px 16px;
            border: 1px solid var(--admin-line);
            border-radius: 8px;
            background: var(--admin-surface);
            color: var(--admin-ink);
            font-weight: 900;
            text-decoration: none;
            line-height: 1;
            transition: background 180ms ease, border-color 180ms ease, color 180ms ease, transform 180ms ease, box-shadow 180ms ease;
        }

        .admin-btn:hover {
            text-decoration: none;
            color: var(--admin-accent);
            border-color: var(--admin-accent);
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(11, 53, 183, 0.12);
        }

        .admin-btn-primary {
            background: var(--admin-accent);
            border-color: var(--admin-accent);
            color: #FFFFFF;
        }

        .admin-btn-primary:hover {
            background: #001F70;
            border-color: #001F70;
            color: #FFFFFF;
        }

        .admin-btn-danger {
            color: var(--admin-danger);
            border-color: var(--admin-danger);
            background: #FFFFFF;
        }

        .admin-btn-danger:hover {
            background: var(--admin-danger);
            color: #FFFFFF;
        }

        .admin-btn-secondary {
            background: #FFFFFF;
        }

        .btn-success,
        .btn-circle.btn-success {
            border-color: #16B978 !important;
            background: #16B978 !important;
            color: #FFFFFF !important;
            box-shadow: 0 16px 28px rgba(22, 185, 120, 0.24);
        }

        .btn-warning,
        .btn-circle.btn-warning {
            border-color: #FFB83D !important;
            background: #FFB83D !important;
            color: #111827 !important;
        }

        .btn-danger,
        .btn-circle.btn-danger {
            border-color: var(--admin-danger) !important;
            background: var(--admin-danger) !important;
            color: #FFFFFF !important;
        }

        .btn-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 999px !important;
            transition: transform 180ms ease, box-shadow 180ms ease;
        }

        .btn-circle:hover {
            transform: translateY(-2px);
        }

        .admin-filter-bar,
        .admin-form-panel,
        .admin-table-wrap,
        .dashboard-section {
            background: var(--admin-surface);
            border: 1px solid var(--admin-line);
            border-radius: 8px;
            box-shadow: 0 14px 34px rgba(16, 24, 40, 0.05);
        }

        .admin-filter-bar {
            display: grid;
            grid-template-columns: minmax(240px, 1fr) minmax(180px, 240px) auto;
            gap: 14px;
            align-items: end;
            padding: 18px;
            margin-bottom: 22px;
        }

        .admin-field label,
        .admin-form-panel label {
            display: block;
            margin-bottom: 7px;
            color: #344054;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .admin-filter-actions,
        .admin-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .form-control {
            min-height: 44px;
            border-radius: 8px;
            border-color: #B8C0CC;
            color: var(--admin-ink);
            box-shadow: none;
        }

        .form-control:focus {
            border-color: var(--admin-accent);
            box-shadow: 0 0 0 3px rgba(0, 47, 167, 0.12);
        }

        .admin-table {
            margin: 0;
        }

        .admin-table th {
            color: #667085;
            border-top: 0;
            border-bottom: 1px solid var(--admin-line);
            font-size: 12px;
            text-transform: uppercase;
        }

        .admin-table td {
            vertical-align: middle;
            border-top: 1px solid var(--admin-line);
        }

        .admin-muted {
            color: var(--admin-muted);
        }

        .admin-chip,
        .module-badges span,
        .state-pill {
            display: inline-flex;
            align-items: center;
            min-height: 26px;
            padding: 4px 8px;
            border: 1px solid var(--admin-line);
            border-radius: 999px;
            color: #344054;
            background: #F8FAFF;
            font-size: 12px;
            font-weight: 800;
        }

        .module-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .admin-form-grid {
            display: grid;
            grid-template-columns: minmax(320px, 0.85fr) minmax(360px, 1.15fr);
            gap: 20px;
        }

        .admin-form-panel {
            padding: 22px;
        }

        .admin-form-panel h2,
        .dashboard-section h2 {
            margin: 0 0 18px;
            color: var(--admin-ink);
            font-size: 17px;
            font-weight: 800;
        }

        .permission-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(180px, 1fr));
            gap: 10px;
        }

        .permission-item {
            display: grid;
            grid-template-columns: 20px 1fr;
            gap: 10px;
            min-height: 76px;
            padding: 12px;
            border: 1px solid var(--admin-line);
            border-radius: 8px;
            margin: 0;
        }

        .permission-item strong {
            display: block;
            color: var(--admin-ink);
            font-size: 13px;
        }

        .permission-item small {
            color: var(--admin-muted);
            font-size: 12px;
        }

        .permission-item input:checked + span strong {
            color: var(--admin-accent);
        }

        .admin-readonly-box {
            display: grid;
            gap: 6px;
            padding: 16px;
            border: 1px solid rgba(11, 53, 183, 0.22);
            border-radius: 8px;
            background: linear-gradient(135deg, rgba(11, 53, 183, 0.08), rgba(216, 50, 130, 0.08));
            color: var(--admin-ink);
        }

        .admin-readonly-box strong {
            font-size: 15px;
            font-weight: 900;
        }

        .admin-readonly-box span {
            color: var(--admin-muted);
            font-size: 13px;
            font-weight: 700;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(160px, 1fr));
            border-top: 1px solid var(--admin-line);
            border-left: 1px solid var(--admin-line);
            background: var(--admin-surface);
            margin-bottom: 22px;
        }

        .metric-cell {
            min-height: 128px;
            padding: 18px;
            border-right: 1px solid var(--admin-line);
            border-bottom: 1px solid var(--admin-line);
            background: #FFFFFF;
        }

        .metric-cell span {
            display: block;
            color: var(--admin-muted);
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .metric-cell strong {
            display: block;
            margin-top: 18px;
            color: var(--admin-ink);
            font-size: 34px;
            line-height: 1;
            font-weight: 800;
            font-variant-numeric: tabular-nums;
        }

        .metric-cell small {
            display: block;
            margin-top: 10px;
            color: var(--admin-muted);
            font-size: 12px;
        }

        .dashboard-columns {
            display: grid;
            grid-template-columns: minmax(420px, 1fr) minmax(320px, 0.7fr);
            gap: 22px;
        }

        .dashboard-section {
            padding: 18px;
        }

        .dashboard-list {
            display: grid;
            gap: 10px;
        }

        .dashboard-row {
            display: grid;
            grid-template-columns: 88px 1fr auto;
            gap: 14px;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--admin-line);
        }

        .dashboard-row:last-child {
            border-bottom: 0;
        }

        .dashboard-row strong {
            color: var(--admin-ink);
            font-size: 14px;
        }

        .dashboard-row span,
        .dashboard-row small {
            color: var(--admin-muted);
        }

        .state-list {
            display: grid;
            gap: 8px;
        }

        .state-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid var(--admin-line);
        }

        .state-row:last-child {
            border-bottom: 0;
        }

        .admin-form-footer {
            grid-column: 1 / -1;
            display: flex;
            justify-content: flex-end;
        }

        .pedido-col {
            width: 110px !important;
            min-width: 110px;
            max-width: 110px;
        }

        .pedido-numero {
            display: block;
            width: 90px;
            min-width: 90px;
            white-space: nowrap;
            text-align: left;
        }

        @media (max-width: 991px) {
            .admin-filter-bar,
            .admin-form-grid,
            .dashboard-columns {
                grid-template-columns: 1fr;
            }

            .dashboard-grid {
                grid-template-columns: repeat(2, minmax(140px, 1fr));
            }

            .permission-grid {
                grid-template-columns: 1fr;
            }

            .admin-page-header,
            .admin-topbar {
                flex-direction: column;
                align-items: stretch;
            }

            .sidebar {
                width: 5.75rem !important;
                padding-left: 10px;
                padding-right: 10px;
            }

            .container-fluid {
                padding: 24px 18px;
            }
        }
    </style>
    @php
        $adminDashboardCssPath = public_path('css/admin-dashboard.css');
        $adminDashboardHttpdocsCssPath = base_path('../httpdocs/css/admin-dashboard.css');
        $adminDashboardCssVersion = file_exists($adminDashboardCssPath)
            ? filemtime($adminDashboardCssPath)
            : (file_exists($adminDashboardHttpdocsCssPath) ? filemtime($adminDashboardHttpdocsCssPath) : '20260724');
    @endphp
    <link href="{{asset('css/admin-dashboard.css')}}?v={{$adminDashboardCssVersion}}" rel="stylesheet">
    @yield('css')
</head>

<body id="page-top">
    @php
        $adminModules = \App\Support\AdminModules::modulesForUser(auth()->user());
        $canModule = function ($module) use ($adminModules) {
            return array_key_exists($module, $adminModules);
        };
        $currentRoleLabel = \App\Support\AdminModules::roleLabel(auth()->user()->role ?? null);
    @endphp

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav color sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" 
            href="{{route('home')}}">
                <img
                    class="admin-brand-mark"
                    src="{{ asset('storage/logos/MwllbreCt8Pu62sEfGvKSyvip4KKgKZxqC4NEpaP.svg') }}"
                    alt="Moldpack"
                    onerror="this.onerror=null;this.src='{{ asset('img/logo2.jpg') }}';"
                >
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

           

            <!-- Divider -->
            <hr class="sidebar-divider">

        @if ($canModule('home'))
            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{route('slider', 'inicio')}}">Slider</a>
                        <a class="collapse-item d-none" href="{{route('editarinicio', 1)}}">Editar Contenido</a>
                        {{-- <a class="collapse-item" href="{{route('icono')}}">Editar Icono</a> --}}
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-city"></i>
                    <span>Empresa</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{route('slider', 'empresa')}}">Slider</a>                 
                        <a class="collapse-item" href="{{route('editarempresa', 1)}}">Editar Contenido</a>                           
                    </div>
                </div>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-boxes"></i>
                    <span>Productos</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">      
                        <a class="collapse-item" href="{{route('Categorias')}}">Categorias</a>
                        <a class="collapse-item" href="{{route('familiaProductos')}}">Subcategorias</a>
                        <a class="collapse-item" href="{{route('Productos')}}">Producto</a>
                        <a class="collapse-item" href="{{route('Colors')}}">Colores</a>
                        <a class="collapse-item" href="{{route('precios')}}">Actualizar precios</a>
						<a class="collapse-item" href="{{route('deescarga')}}">Catalogo</a>
                    </div>
                </div>
            </li>
            
            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#donde"
                    aria-expanded="true" aria-controls="donde">
                    <i class="fas fa-boxes"></i>
                    <span>Donde comprar</span>
                </a>
                <div id="donde" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">      
                        <a class="collapse-item" href="{{route('service','comprar')}}">Lista de locales</a>                        
                    </div>
                </div>
            </li>

            <!-- Novedades -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#novedad"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-palette"></i>
                    <span>Novedades</span>
                </a>
                <div id="novedad" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">                          
                            <a class="collapse-item" href="{{route('novedadCategoria')}}">Novedades Categorias</a>
                            <a class="collapse-item" href="{{route('novedad')}}">Novedades</a>
                    </div>
                </div>
            </li>
            <!-- Zona privada -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#zp"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-palette"></i>
                    <span>Zona privada</span>
                </a>
                <div id="zp" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded"> 
                        <a class="collapse-item" href="{{route('carrito_zp')}}">Carrito</a>                          
                        <a class="collapse-item" href="{{route('clientes.view')}}">Lista de clientes</a>
                        
                    </div>
                </div>
            </li>
        @endif        
        @if ($canModule('logistica'))
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#logistica"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-palette"></i>
                    <span>Logistica</span>
                </a>
                <div id="logistica" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">                          
                        <a class="collapse-item" href="{{route('pedido')}}">Pedidos</a>        
						<a class="collapse-item" href="{{route('export.stock')}}">Stock</a>
                    </div>
                </div>
            </li>            
        @endif
        @if ($canModule('contabilidad'))
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#contabilidad"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-palette"></i>
                    <span>Contabilidad</span>
                </a>
                <div id="contabilidad" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">                          
	                        <a class="collapse-item" href="{{route('adm.contabilidad.pedidos')}}">Pedidos</a>
                        <a class="collapse-item" href="{{route('adm.facturado')}}">Facturado</a>
						<a class="collapse-item" href="{{route('adm.facturas')}}">Facturas</a>
							<a class="collapse-item" href="{{route('adm.contabilidad.pedidoAll')}}">Todos los pedidos</a>
						<a class="collapse-item" href="{{ route('pedidoexcel') }}?t={{ time() }}">Excel pedidos</a>
                    </div>
                </div>
            </li>
        @endif
			@if ($canModule('estadisticas'))
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#estadistica"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-palette"></i>
                    <span>Estadisticas</span>
                </a>
                <div id="estadistica" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">                          
                        <a class="collapse-item" href="{{route('estat.ventas')}}">Ventas</a>
                        <a class="collapse-item" href="{{route('estat.clientes')}}">Clientes</a>
						<a class="collapse-item" href="{{route('estat.grafventas')}}">Gráficos</a>
                    </div>
                </div>
            </li>
        @endif
        @if ($canModule('contacto') || $canModule('usuarios') || $canModule('metadatos') || $canModule('newsletter'))
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsecontacto"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-address-book"></i>
                    <span>Contacto</span>
                </a>
                <div id="collapsecontacto" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded"> 
                        <a class="collapse-item" href="{{route('editarcontacto', 1)}}">Editar Contacto</a>
                        <a class="collapse-item" href="{{route('editarredes', 1)}}">Editar Redes sociales</a>
                        <a class="collapse-item" href="{{route('logos')}}">Editar logos</a>   
                    </div>
                </div>
               
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseusuarios"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-mail-bulk"></i>
                    <span>Usuarios</span>
                </a>
                <div id="collapseusuarios" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded"> 
                          <a class="collapse-item" href="{{route('usuarios')}}">Ver Usuarios</a> 
                               
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsemetadatos"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-mail-bulk"></i>
                    <span>
                        Metadatos
                    </span>
                </a>
                <div id="collapsemetadatos" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded"> 
                          <a class="collapse-item" href="{{route('metadatos')}}">Ver Metadatos</a> 
                               
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsenew"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-mail-bulk"></i>
                    <span>
                        Newsletter
                    </span>
                </a>
                <div id="collapsenew" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded"> 
                       <a class="collapse-item" href="{{route('Subcriptores.view')}}">Subcriptores</a> 
                               
                    </div>
                </div>
            </li>
            <li class="nav-item d-none">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsesoporte"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-mail-bulk"></i>
                    <span>
                        Soporte
                    </span>
                </a>
                <div id="collapsesoporte" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded"> 
                          <a class="collapse-item" href="mailto:soporte@osole.es">Enviar mail</a> 
                               
                    </div>
                </div>
            </li>
            @endif
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button type="button" id="sidebarToggle" aria-label="Contraer o expandir menu" title="Contraer o expandir menu"></button>
            </div>

            <!-- Sidebar Message -->


        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <div class="admin-topbar">
                        <div class="d-flex align-items-center">
                            <button id="sidebarToggleTop" class="btn btn-link d-md-none mr-3">
                                <i class="fa fa-bars"></i>
                            </button>
                            <span class="admin-topbar-title">Panel de control</span>
                        </div>

                        <div class="admin-topbar-actions">
                            <button type="button" class="admin-btn admin-btn-secondary" onclick="window.location.reload()">Actualizar</button>
                            <div class="admin-user-block">
                                <div>
                                    <strong>{{auth()->user()->name}}</strong>
                                    <span>{{$currentRoleLabel}}</span>
                                </div>
                            </div>
                            <form class="m-0" id="logout-form" action="{{route('logout')}}" method="POST">
                                @csrf
                                <button type="submit" class="admin-btn admin-btn-danger">Cerrar sesion</button>
                            </form>
                        </div>
                    </div>
                </nav>
                <!-- End of Topbar -->

	                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                   @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-right my-auto">
                        <a target="_blank" href="https://osole.com.ar/"><span>By osole</span></a>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

   
    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>

    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('js/sb-admin-2.min.js')}}"></script>

    <!-- Page level plugins -->
   

    @yield('js')
    <script src="{{asset('js/select2.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    if (window.jQuery) {
        $(document).ajaxError(function (event, xhr) {
            if ([401, 419].indexOf(xhr.status) !== -1) {
                var redirect = (xhr.responseJSON && xhr.responseJSON.redirect) ? xhr.responseJSON.redirect : '{{route('login')}}';
                window.location.href = redirect;
            }
        });
    }
</script>

</body>

</html>
