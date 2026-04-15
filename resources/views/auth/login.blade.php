<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | {{ $siteSettings['site_name'] ?? 'School ERP' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --primary: #4f46e5; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 430px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #4f46e5, #3730a3);
            padding: 2rem;
            text-align: center;
            color: white;
        }
        .login-logo {
            width: 60px; height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: 800;
            margin: 0 auto 0.75rem;
        }
        .login-body { padding: 2rem; }
        .form-control, .form-select {
            border-radius: 10px;
            border-color: #e2e8f0;
            padding: 0.65rem 1rem;
            font-size: 0.875rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.12);
        }
        .btn-login {
            background: var(--primary);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 0.95rem;
            width: 100%;
            color: white;
            transition: background 0.2s;
        }
        .btn-login:hover { background: #3730a3; color: white; }
        .role-tabs .nav-link {
            border-radius: 8px;
            font-size: 0.8rem;
            padding: 0.4rem 0.75rem;
            color: #64748b;
        }
        .role-tabs .nav-link.active { background: var(--primary); color: white; }
        .input-icon { position: relative; }
        .input-icon .bi {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        .input-icon input { padding-left: 2.5rem; }
        .toggle-password {
            position: absolute;
            right: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
        }
    </style>
</head>
<body>
<div class="login-card m-3">
    <div class="login-header">
        <div class="login-logo">
            @if(!empty($siteSettings['logo']))
                <img src="{{ $siteSettings['logo'] }}" alt="Logo" style="height:40px;">
            @else
                S
            @endif
        </div>
        <h4 class="mb-1 fw-bold">Welcome Back</h4>
        <p class="mb-0 opacity-75 small">Sign in to {{ $siteSettings['site_name'] ?? 'School ERP' }}</p>
    </div>

    <div class="login-body">
        <!-- Role Selector Tabs -->
        <div class="mb-4">
            <label class="form-label small text-muted fw-semibold">Login As</label>
            <div class="d-flex gap-2 flex-wrap role-tabs">
                @foreach(['admin' => ['Admin', 'shield'], 'student' => ['Student', 'mortarboard'], 'teacher' => ['Teacher', 'person-badge'], 'parent' => ['Parent', 'people']] as $roleKey => $roleData)
                <button type="button" class="nav-link {{ $roleKey === 'student' ? 'active' : '' }}"
                    onclick="selectRole('{{ $roleKey }}', this)">
                    <i class="bi bi-{{ $roleData[1] }} me-1"></i>{{ $roleData[0] }}
                </button>
                @endforeach
            </div>
        </div>

        @if($errors->any())
        <div class="alert alert-danger py-2 small">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <input type="hidden" name="role" id="roleInput" value="{{ old('role', 'student') }}">

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-icon">
                    <i class="bi bi-envelope"></i>
                    <input type="email" name="email" class="form-control" placeholder="your@email.com"
                        value="{{ old('email') }}" required autocomplete="email">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-icon">
                    <i class="bi bi-lock"></i>
                    <input type="password" name="password" id="passwordInput" class="form-control"
                        placeholder="Enter password" required autocomplete="current-password">
                    <button class="toggle-password" type="button" onclick="togglePwd()">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small" for="remember">Remember me</label>
                </div>
                <a href="{{ route('password.forgot') }}" class="small text-decoration-none" style="color:var(--primary);">
                    Forgot password?
                </a>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('admission.form') }}" class="small text-decoration-none" style="color:var(--primary);">
                <i class="bi bi-person-plus me-1"></i>Apply for Admission
            </a>
        </div>
    </div>
</div>

<script>
function selectRole(role, el) {
    document.getElementById('roleInput').value = role;
    document.querySelectorAll('.role-tabs .nav-link').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
}
function togglePwd() {
    const input = document.getElementById('passwordInput');
    const icon = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
// Set active tab from old role value
const oldRole = '{{ old('role', 'student') }}';
document.querySelectorAll('.role-tabs .nav-link').forEach(btn => {
    if (btn.textContent.trim().toLowerCase() === oldRole ||
        btn.getAttribute('onclick')?.includes("'" + oldRole + "'")) {
        btn.classList.add('active');
    }
});
</script>
</body>
</html>
