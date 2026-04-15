<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | {{ $siteSettings['site_name'] ?? 'School ERP' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { min-height:100vh; background:linear-gradient(135deg,#4f46e5,#06b6d4); display:flex; align-items:center; justify-content:center; font-family:'Segoe UI',system-ui,sans-serif; }
        .card { border-radius:20px; border:none; box-shadow:0 25px 60px rgba(0,0,0,0.2); width:100%; max-width:420px; }
        .form-control { border-radius:10px; padding:0.65rem 1rem; }
        .form-control:focus { border-color:#4f46e5; box-shadow:0 0 0 3px rgba(79,70,229,0.12); }
        .btn-primary { background:#4f46e5; border-color:#4f46e5; border-radius:10px; }
        .btn-primary:hover { background:#3730a3; border-color:#3730a3; }
    </style>
</head>
<body>
<div class="card m-3">
    <div class="card-body p-4">
        <div class="text-center mb-4">
            <div style="width:56px;height:56px;background:#4f46e5;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;color:white;font-size:1.5rem;">
                <i class="bi bi-key"></i>
            </div>
            <h4 class="fw-bold">Forgot Password</h4>
            <p class="text-muted small">Enter your registered email to receive OTP</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success small"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger small">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.otp.send') }}">
            @csrf
            <div class="mb-4">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="your@email.com"
                    value="{{ old('email') }}" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">
                <i class="bi bi-send me-2"></i>Send OTP
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="small text-decoration-none text-muted">
                <i class="bi bi-arrow-left me-1"></i>Back to Login
            </a>
        </div>
    </div>
</div>
</body>
</html>
