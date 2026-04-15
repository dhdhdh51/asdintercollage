<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: 'Segoe UI', Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 20px; }
    .email-container { max-width: 500px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
    .email-header { background: linear-gradient(135deg, #4f46e5, #06b6d4); padding: 30px; text-align: center; color: white; }
    .email-body { padding: 30px; }
    .otp-box { background: #f8fafc; border: 2px dashed #4f46e5; border-radius: 12px; padding: 20px; text-align: center; margin: 20px 0; }
    .otp-code { font-size: 36px; font-weight: 900; color: #4f46e5; letter-spacing: 8px; }
    .footer { background: #f8fafc; padding: 15px; text-align: center; font-size: 12px; color: #94a3b8; }
</style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <h2 style="margin:0;">Password Reset OTP</h2>
        <p style="margin:5px 0 0;opacity:0.8;">School ERP</p>
    </div>
    <div class="email-body">
        <p>Hello <strong>{{ $user->name }}</strong>,</p>
        <p>You requested to reset your password. Use the OTP below:</p>
        <div class="otp-box">
            <div class="otp-code">{{ $otp }}</div>
            <p style="color:#64748b;margin:8px 0 0;font-size:13px;">Valid for 10 minutes only</p>
        </div>
        <p>If you did not request this, please ignore this email. Do not share this OTP with anyone.</p>
        <p style="color:#64748b;font-size:13px;">This is an automated email. Please do not reply.</p>
    </div>
    <div class="footer">© {{ date('Y') }} School ERP. All Rights Reserved.</div>
</div>
</body>
</html>
