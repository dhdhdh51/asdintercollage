<!DOCTYPE html>
<html>
<head><meta charset="UTF-8">
<style>
    body { font-family: 'Segoe UI', Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 20px; }
    .ec { max-width: 500px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; }
    .eh { background: linear-gradient(135deg, #4f46e5, #06b6d4); padding: 30px; text-align: center; color: white; }
    .eb { padding: 30px; }
    .ef { background: #f8fafc; padding: 15px; text-align: center; font-size: 12px; color: #94a3b8; }
</style>
</head>
<body>
<div class="ec">
    <div class="eh"><h2 style="margin:0;">{{ $notification->title }}</h2><p style="margin:5px 0 0;opacity:0.8;">School ERP Notification</p></div>
    <div class="eb">
        <p>Dear <strong>{{ $recipient->name }}</strong>,</p>
        <p>{{ $notification->message }}</p>
        <p style="color:#64748b;font-size:13px;">This is an automated notification from School ERP.</p>
    </div>
    <div class="ef">© {{ date('Y') }} School ERP. All Rights Reserved.</div>
</div>
</body>
</html>
