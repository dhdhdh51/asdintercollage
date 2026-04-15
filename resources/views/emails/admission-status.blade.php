<!DOCTYPE html>
<html>
<head><meta charset="UTF-8">
<style>
    body { font-family: 'Segoe UI', Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 20px; }
    .ec { max-width: 500px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
    .eh-approved { background: linear-gradient(135deg, #10b981, #06b6d4); padding: 30px; text-align: center; color: white; }
    .eh-rejected { background: linear-gradient(135deg, #ef4444, #f97316); padding: 30px; text-align: center; color: white; }
    .eb { padding: 30px; }
    .ef { background: #f8fafc; padding: 15px; text-align: center; font-size: 12px; color: #94a3b8; }
</style>
</head>
<body>
<div class="ec">
    <div class="{{ $admission->status === 'approved' ? 'eh-approved' : 'eh-rejected' }}">
        <h2 style="margin:0;">Admission {{ ucfirst($admission->status) }}!</h2>
        <p style="margin:5px 0 0;opacity:0.8;">{{ $admission->application_id }}</p>
    </div>
    <div class="eb">
        <p>Dear <strong>{{ $admission->father_name }}</strong>,</p>
        @if($admission->status === 'approved')
        <p>Congratulations! We are pleased to inform you that the admission of <strong>{{ $admission->student_name }}</strong> in Class {{ $admission->class->name ?? 'N/A' }} has been <strong style="color:#10b981;">APPROVED</strong>.</p>
        <p>Please visit the school office with the following documents:</p>
        <ul><li>Original Birth Certificate</li><li>Transfer Certificate (if any)</li><li>4 Passport Size Photos</li><li>Aadhar Card</li></ul>
        @else
        <p>We regret to inform you that the admission application of <strong>{{ $admission->student_name }}</strong> has been <strong style="color:#ef4444;">REJECTED</strong>.</p>
        @if($admission->remarks)<p><strong>Reason:</strong> {{ $admission->remarks }}</p>@endif
        <p>Please contact the school office for more information.</p>
        @endif
    </div>
    <div class="ef">© {{ date('Y') }} School ERP. All Rights Reserved.</div>
</div>
</body>
</html>
