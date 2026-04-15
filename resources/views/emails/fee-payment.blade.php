<!DOCTYPE html>
<html>
<head><meta charset="UTF-8">
<style>
    body { font-family: 'Segoe UI', Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 20px; }
    .ec { max-width: 500px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; }
    .eh { background: linear-gradient(135deg, #10b981, #4f46e5); padding: 30px; text-align: center; color: white; }
    .eb { padding: 30px; }
    .amount-box { background: #dcfce7; border-radius: 8px; padding: 15px; text-align: center; margin: 15px 0; }
    .ef { background: #f8fafc; padding: 15px; text-align: center; font-size: 12px; color: #94a3b8; }
</style>
</head>
<body>
<div class="ec">
    <div class="eh"><h2 style="margin:0;">Payment Confirmed!</h2><p style="margin:5px 0 0;opacity:0.8;">Fee Receipt</p></div>
    <div class="eb">
        <p>Dear <strong>{{ $transaction->student->user->name }}</strong>,</p>
        <p>Your fee payment has been successfully processed.</p>
        <div class="amount-box">
            <div style="font-size:28px;font-weight:900;color:#16a34a;">₹{{ number_format($transaction->amount, 2) }}</div>
            <div style="color:#64748b;font-size:13px;">Amount Paid</div>
        </div>
        <table style="width:100%;border-collapse:collapse;">
            <tr><td style="padding:5px;color:#64748b;">Receipt No</td><td style="padding:5px;font-weight:600;">{{ $transaction->receipt_number }}</td></tr>
            <tr><td style="padding:5px;color:#64748b;">Transaction ID</td><td style="padding:5px;font-family:monospace;">{{ $transaction->transaction_id }}</td></tr>
            <tr><td style="padding:5px;color:#64748b;">Payment Method</td><td style="padding:5px;">{{ ucfirst($transaction->payment_method) }}</td></tr>
            <tr><td style="padding:5px;color:#64748b;">Date</td><td style="padding:5px;">{{ $transaction->created_at->format('d M Y H:i') }}</td></tr>
        </table>
    </div>
    <div class="ef">© {{ date('Y') }} School ERP. All Rights Reserved.</div>
</div>
</body>
</html>
