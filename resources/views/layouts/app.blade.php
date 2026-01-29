<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (session('success'))
        <meta name="success-message" content="{{ session('success') }}">
    @endif
    @if (session('error'))
        <meta name="error-message" content="{{ session('error') }}">
    @endif
    @if (session('warning'))
        <meta name="warning-message" content="{{ session('warning') }}">
    @endif
    <title>@yield('title', 'DMS Dapen') - Document Management System</title>

    <!-- Bootstrap 5 CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="{{ asset('assets/css/bootstrap-icons-custom.css') }}" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="{{ asset('assets/css/sweetalert2.min.css') }}" rel="stylesheet">

    @stack('styles')

    <style>
        :root {
            --sidebar-width: 280px;
            --header-height: 60px;
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --text-muted: #94a3b8;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f1f5f9;
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h5 {
            color: #fff;
            margin: 0;
            font-weight: 600;
        }

        .sidebar-header small {
            color: var(--text-muted);
            font-size: 0.75rem;
        }

        .sidebar-search {
            padding: 15px 20px;
        }

        .sidebar-search input {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            color: #fff;
            width: 100%;
            font-size: 0.875rem;
        }

        .sidebar-search input::placeholder {
            color: var(--text-muted);
        }

        .nav-section {
            padding: 15px 20px 8px;
            color: var(--text-muted);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-item {
            margin: 2px 10px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: #e2e8f0;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 0.875rem;
        }

        .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .nav-link.active {
            background: var(--primary-color);
            color: #fff;
        }

        .nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 1rem;
        }

        .nav-item.has-submenu>.nav-link::after {
            content: '\F282';
            font-family: 'bootstrap-icons';
            margin-left: auto;
            transition: transform 0.2s ease;
            font-size: 0.75rem;
        }

        .nav-item.has-submenu.open>.nav-link::after {
            transform: rotate(90deg);
        }

        .submenu {
            display: none;
            padding-left: 20px;
        }

        .nav-item.has-submenu.open .submenu {
            display: block;
        }

        .submenu .nav-link {
            padding: 8px 15px;
            font-size: 0.813rem;
            color: var(--text-muted);
        }

        .submenu .nav-link:hover,
        .submenu .nav-link.active {
            color: #fff;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .main-header {
            background: #fff;
            height: var(--header-height);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .page-content {
            padding: 24px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
            font-size: 0.875rem;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            color: #94a3b8;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-search {
            position: relative;
            width: 300px;
        }

        .header-search i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .header-search input {
            padding-left: 40px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .notification-btn:hover {
            background: #f1f5f9;
        }

        .notification-btn .badge {
            position: absolute;
            top: 4px;
            right: 4px;
            font-size: 0.65rem;
            padding: 2px 5px;
        }

        .user-dropdown {
            position: relative;
        }

        .user-dropdown .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 12px;
            background: none;
            border: none;
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .user-dropdown .dropdown-toggle:hover {
            background: #f1f5f9;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary-color);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .user-dropdown .dropdown-menu {
            min-width: 200px;
            margin-top: 8px;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        @media (max-width: 768px) {
            .header-search {
                display: none;
            }
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 16px 20px;
            font-weight: 600;
        }

        .table th {
            background: #f8fafc;
            font-weight: 600;
            font-size: 0.813rem;
            color: #64748b;
            text-transform: uppercase;
        }

        .table td {
            vertical-align: middle;
            padding: 12px 16px;
            font-size: 0.875rem;
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
            padding: 8px 16px;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .badge-secret {
            background: #fef2f2;
            color: #dc2626;
        }

        .badge-public {
            background: #f0fdf4;
            color: #16a34a;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    @include('layouts.partials.sidebar')

    <div class="main-content">
        @include('layouts.partials.header')
        <div class="page-content">
            @yield('content')
        </div>
    </div>

    <!-- jQuery (required for Select2) -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <!-- Bootstrap 5 JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <!-- SweetAlert Helper -->
    <script src="{{ asset('assets/js/sweetalert-helper.js') }}"></script>
    <script>
        document.querySelectorAll('.nav-item.has-submenu > .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                this.parentElement.classList.toggle('open');
            });
        });

        const currentPath = window.location.pathname;
        document.querySelectorAll('.submenu .nav-link').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
                link.closest('.has-submenu').classList.add('open');
            }
        });

        // Toggle Sidebar on Mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent immediate closing if we add outside click listener
                sidebar.classList.toggle('show');
            });

            // Close sidebar when clicking outside of it on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth < 992 &&
                    sidebar.classList.contains('show') &&
                    !sidebar.contains(e.target) &&
                    e.target !== sidebarToggle &&
                    !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            });
        }
    </script>

    <!-- File Preview Modal -->
    @include('components.file-preview-modal')
    <script src="{{ asset('assets/js/file-preview.js') }}"></script>

    <!-- Request Access Modal -->
    @include('partials.request-access-modal')

    @stack('scripts')
</body>

</html>
