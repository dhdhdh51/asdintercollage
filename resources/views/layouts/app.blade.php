<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', $siteSettings['site_name'] ?? 'School ERP') | {{ $siteSettings['site_name'] ?? 'School ERP' }}</title>
    <meta name="description" content="@yield('meta_description', $siteSettings['meta_description'] ?? 'Complete School Management System')">
    <meta name="keywords" content="@yield('meta_keywords', $siteSettings['meta_keywords'] ?? 'school erp, school management')">
    <meta name="robots" content="noindex, nofollow">

    <!-- Favicon -->
    @if(!empty($siteSettings['favicon']))
        <link rel="icon" type="image/x-icon" href="{{ $siteSettings['favicon'] }}">
    @endif

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --secondary: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --sidebar-width: 260px;
            --sidebar-bg: #1e293b;
            --sidebar-text: #94a3b8;
            --sidebar-active: #4f46e5;
        }

        body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; background: #f1f5f9; }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .sidebar-brand img { height: 36px; width: auto; object-fit: contain; }
        .sidebar-brand-text { color: #fff; font-weight: 700; font-size: 1.1rem; }
        .sidebar-brand-text span { display: block; font-size: 0.7rem; font-weight: 400; color: var(--sidebar-text); }

        .sidebar-nav { padding: 1rem 0; }
        .nav-section-title {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #475569;
            padding: 0.5rem 1.25rem;
            margin-top: 0.5rem;
        }
        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.25rem;
            color: var(--sidebar-text);
            font-size: 0.875rem;
            border-radius: 0;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            text-decoration: none;
        }
        .sidebar-nav .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.05);
            border-left-color: var(--primary);
        }
        .sidebar-nav .nav-link.active {
            color: #fff;
            background: rgba(79,70,229,0.15);
            border-left-color: var(--primary);
        }
        .sidebar-nav .nav-link .bi { font-size: 1rem; min-width: 1rem; }
        .badge-count {
            background: var(--primary);
            color: white;
            font-size: 0.65rem;
            padding: 0.2em 0.5em;
            border-radius: 20px;
            margin-left: auto;
        }

        /* Main Content */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        /* Top Navbar */
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .topbar-title { font-weight: 600; font-size: 1.05rem; color: #1e293b; }
        .topbar-right { display: flex; align-items: center; gap: 1rem; }

        .user-avatar {
            width: 38px; height: 38px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
        }

        /* Content Area */
        .content-area {
            padding: 1.5rem;
            flex: 1;
        }

        /* Cards */
        .stat-card {
            border: none;
            border-radius: 12px;
            padding: 1.25rem;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .stat-card-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }
        .stat-card-value { font-size: 1.6rem; font-weight: 700; line-height: 1; }
        .stat-card-label { font-size: 0.8rem; color: #64748b; margin-top: 0.2rem; }

        /* Tables */
        .table-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .table-card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .table-card-title { font-weight: 600; font-size: 0.95rem; color: #1e293b; margin: 0; }
        .table th { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; background: #f8fafc; }
        .table td { font-size: 0.875rem; vertical-align: middle; }

        /* Badges */
        .badge-status { font-size: 0.72rem; font-weight: 600; padding: 0.3em 0.65em; border-radius: 6px; }

        /* Forms */
        .form-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            padding: 1.5rem;
        }
        .form-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
        .form-control, .form-select {
            border-radius: 8px;
            border-color: #e2e8f0;
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
        }

        /* Buttons */
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn { border-radius: 8px; font-size: 0.875rem; font-weight: 500; }

        /* Alert Styling */
        .alert { border-radius: 10px; border: none; font-size: 0.875rem; }

        /* Mobile Sidebar Toggle */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            color: #374151;
            padding: 0;
            cursor: pointer;
        }

        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .sidebar-toggle { display: block; }
        }

        /* Breadcrumb */
        .breadcrumb { font-size: 0.8rem; margin: 0; }
        .breadcrumb-item a { color: var(--primary); text-decoration: none; }

        /* Page Header */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .page-title { font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0; }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay d-lg-none" id="sidebarOverlay" onclick="closeSidebar()" style="display:none!important;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:999;"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            @if(!empty($siteSettings['logo']))
                <img src="{{ $siteSettings['logo'] }}" alt="Logo">
            @else
                <div style="width:36px;height:36px;background:var(--primary);border-radius:8px;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:1.1rem;">
                    S
                </div>
            @endif
            <div class="sidebar-brand-text">
                {{ $siteSettings['site_name'] ?? 'School ERP' }}
                <span>{{ ucfirst(auth()->user()->role ?? 'Panel') }} Panel</span>
            </div>
        </div>

        <div class="sidebar-nav">
            @yield('sidebar-menu')
        </div>
    </nav>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Topbar -->
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle d-lg-none" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <h5 class="topbar-title mb-0">@yield('page-title', 'Dashboard')</h5>
                    <nav aria-label="breadcrumb" class="d-none d-md-block">
                        <ol class="breadcrumb mb-0">
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="topbar-right">
                <!-- Notification Bell -->
                <a href="#" class="text-secondary position-relative" style="font-size:1.2rem;">
                    <i class="bi bi-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.55rem;">3</span>
                </a>

                <!-- User Dropdown -->
                <div class="dropdown">
                    <div class="user-avatar dropdown-toggle" data-bs-toggle="dropdown" style="list-style:none;">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width:200px;font-size:0.875rem;">
                        <li>
                            <div class="px-3 py-2">
                                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                <div class="text-muted small">{{ auth()->user()->email }}</div>
                                <span class="badge bg-primary mt-1">{{ ucfirst(auth()->user()->role) }}</span>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>My Profile</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-4 pt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="content-area">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="py-3 px-4 border-top" style="background:#fff;font-size:0.8rem;color:#64748b;">
            {{ $siteSettings['footer_text'] ?? '© ' . date('Y') . ' School ERP. All Rights Reserved.' }}
        </footer>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('show');
            overlay.style.setProperty('display', sidebar.classList.contains('show') ? 'block' : 'none', 'important');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('show');
            document.getElementById('sidebarOverlay').style.setProperty('display', 'none', 'important');
        }

        // Auto-dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(a => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(a);
                bsAlert.close();
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>
