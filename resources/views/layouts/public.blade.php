<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', $siteSettings['site_name'] ?? 'School ERP')</title>
    <meta name="description" content="@yield('meta_description', $siteSettings['meta_description'] ?? '')">
    <meta name="keywords" content="@yield('meta_keywords', $siteSettings['meta_keywords'] ?? '')">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('og_title', $siteSettings['og_title'] ?? $siteSettings['site_name'] ?? 'School ERP')">
    <meta property="og:description" content="@yield('og_description', $siteSettings['og_description'] ?? $siteSettings['meta_description'] ?? '')">
    <meta property="og:image" content="@yield('og_image', $siteSettings['og_image'] ?? '')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Schema Markup -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "EducationalOrganization",
        "name": "{{ $siteSettings['schema_name'] ?? $siteSettings['site_name'] ?? 'School ERP' }}",
        "description": "{{ $siteSettings['schema_description'] ?? $siteSettings['meta_description'] ?? '' }}",
        "url": "{{ $siteSettings['schema_url'] ?? url('/') }}",
        "telephone": "{{ $siteSettings['schema_phone'] ?? $siteSettings['contact_phone'] ?? '' }}",
        "address": {
            "@@type": "PostalAddress",
            "streetAddress": "{{ $siteSettings['schema_address'] ?? $siteSettings['contact_address'] ?? '' }}"
        }
    }
    </script>

    <!-- Favicon -->
    @if(!empty($siteSettings['favicon']))
        <link rel="icon" type="image/x-icon" href="{{ $siteSettings['favicon'] }}">
    @endif

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root { --primary: #4f46e5; --primary-dark: #3730a3; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; }

        .navbar-brand-logo { height: 40px; }
        .navbar { box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .hero-section {
            background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
            min-height: 85vh;
            display: flex;
            align-items: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .btn-primary { background: var(--primary); border-color: var(--primary); border-radius: 8px; }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .footer { background: #1e293b; color: #94a3b8; }
        .feature-icon {
            width: 60px; height: 60px;
            background: rgba(79,70,229,0.1);
            color: var(--primary);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .section-title { font-weight: 800; color: #1e293b; }
        .card { border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 12px; }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-2 sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                @if(!empty($siteSettings['logo']))
                    <img src="{{ $siteSettings['logo'] }}" alt="Logo" class="navbar-brand-logo">
                @else
                    <div style="width:40px;height:40px;background:var(--primary);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:1.2rem;">S</div>
                @endif
                <span style="font-weight:700;color:#1e293b;">{{ $siteSettings['site_name'] ?? 'School ERP' }}</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-1">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admission.form') }}">Admission</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admission.status') }}">Track Status</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('blog') }}">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-primary btn-sm px-4" href="{{ route('login') }}">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success') || session('error'))
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>
    @endif

    <!-- Page Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="footer py-5 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5 class="text-white fw-bold mb-3">{{ $siteSettings['site_name'] ?? 'School ERP' }}</h5>
                    <p class="small">{{ $siteSettings['site_tagline'] ?? 'Complete School Management System' }}</p>
                </div>
                <div class="col-md-2">
                    <h6 class="text-white fw-semibold mb-3">Quick Links</h6>
                    <ul class="list-unstyled small">
                        <li><a href="{{ route('home') }}" class="text-secondary text-decoration-none">Home</a></li>
                        <li><a href="{{ route('admission.form') }}" class="text-secondary text-decoration-none">Admission</a></li>
                        <li><a href="{{ route('blog') }}" class="text-secondary text-decoration-none">Blog</a></li>
                        <li><a href="{{ route('contact') }}" class="text-secondary text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="text-white fw-semibold mb-3">Portals</h6>
                    <ul class="list-unstyled small">
                        <li><a href="{{ route('login') }}" class="text-secondary text-decoration-none">Student Portal</a></li>
                        <li><a href="{{ route('login') }}" class="text-secondary text-decoration-none">Teacher Portal</a></li>
                        <li><a href="{{ route('login') }}" class="text-secondary text-decoration-none">Parent Portal</a></li>
                        <li><a href="{{ route('login') }}" class="text-secondary text-decoration-none">Admin Panel</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="text-white fw-semibold mb-3">Contact</h6>
                    <ul class="list-unstyled small">
                        @if(!empty($siteSettings['contact_email']))
                        <li><i class="bi bi-envelope me-2"></i>{{ $siteSettings['contact_email'] }}</li>
                        @endif
                        @if(!empty($siteSettings['contact_phone']))
                        <li><i class="bi bi-telephone me-2"></i>{{ $siteSettings['contact_phone'] }}</li>
                        @endif
                        @if(!empty($siteSettings['contact_address']))
                        <li><i class="bi bi-geo-alt me-2"></i>{{ $siteSettings['contact_address'] }}</li>
                        @endif
                    </ul>
                </div>
            </div>
            <hr class="border-secondary mt-4">
            <div class="text-center small">
                {{ $siteSettings['footer_text'] ?? '© ' . date('Y') . ' ' . ($siteSettings['site_name'] ?? 'School ERP') . '. All Rights Reserved.' }}
            </div>
        </div>
    </footer>

    <!-- Google Analytics -->
    @if(!empty($siteSettings['google_analytics']))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $siteSettings['google_analytics'] }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $siteSettings['google_analytics'] }}');
    </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
