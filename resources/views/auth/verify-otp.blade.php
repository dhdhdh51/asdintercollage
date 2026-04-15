<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP | {{ $siteSettings['site_name'] ?? 'School ERP' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { min-height:100vh; background:linear-gradient(135deg,#4f46e5,#06b6d4); display:flex; align-items:center; justify-content:center; font-family:'Segoe UI',system-ui,sans-serif; }
        .card { border-radius:20px; border:none; box-shadow:0 25px 60px rgba(0,0,0,0.2); width:100%; max-width:420px; }
        .otp-input { letter-spacing:0.5em; font-size:1.5rem; font-weight:700; text-align:center; border-radius:10px; padding:0.65rem; }
        .otp-input:focus { border-color:#4f46e5; box-shadow:0 0 0 3px rgba(79,70,229,0.12); }
        .btn-primary { background:#4f46e5; border-color:#4f46e5; border-radius:10px; }
    </style>
</head>
<body>
<div class="card m-3">
    <div class="card-body p-4">
        <div class="text-center mb-4">
            <div style="width:56px;height:56px;background:#10b981;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;color:white;font-size:1.5rem;">
                <i class="bi bi-shield-check"></i>
            </div>
            <h4 class="fw-bold">Enter OTP</h4>
            <p class="text-muted small">OTP sent to <strong>{{ $email }}</strong>. Valid for 10 minutes.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger small">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('password.verify-otp.post') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <div class="mb-4">
                <label class="form-label text-center d-block">6-Digit OTP</label>
                <input type="text" name="otp" class="form-control otp-input"
                    maxlength="6" pattern="\d{6}" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">
                <i class="bi bi-check2 me-2"></i>Verify OTP
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('password.forgot') }}" class="small text-decoration-none text-muted">
                <i class="bi bi-arrow-clockwise me-1"></i>Resend OTP
            </a>
        </div>
    </div>
</div>
</body>
</html>
