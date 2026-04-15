<!DOCTYPE html>
<html>
<head><meta charset="UTF-8">
<style>
    body { font-family: 'Segoe UI', Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 20px; }
    .ec { max-width: 500px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
    .eh { background: linear-gradient(135deg, #4f46e5, #06b6d4); padding: 30px; text-align: center; color: white; }
    .eb { padding: 30px; }
    .id-box { background: #e0e7ff; border-radius: 8px; padding: 15px; text-align: center; margin: 15px 0; }
    .id-text { font-size: 24px; font-weight: 900; color: #4f46e5; letter-spacing: 3px; }
    .ef { background: #f8fafc; padding: 15px; text-align: center; font-size: 12px; color: #94a3b8; }
</style>
</head>
<body>
<div class="ec">
    <div class="eh">
        <h2 style="margin:0;">Application Received!</h2>
        <p style="margin:5px 0 0;opacity:0.8;">School ERP Admission System</p>
    </div>
    <div class="eb">
        <p>Dear <strong>{{ $admission->father_name }}</strong>,</p>
        <p>We have received the admission application for <strong>{{ $admission->student_name }}</strong>. Your Application ID is:</p>
        <div class="id-box">
            <div class="id-text">{{ $admission->application_id }}</div>
        </div>
        <p>Use this ID to track your application status at: <a href="{{ url('/admission/status?id=' . $admission->application_id) }}">Track Application</a></p>
        <p><strong>Application Details:</strong></p>
        <ul>
            <li>Student Name: {{ $admission->student_name }}</li>
            <li>Class Applied: Class {{ $admission->class->name ?? 'N/A' }}</li>
            <li>Academic Year: {{ $admission->academic_year }}</li>
        </ul>
        <p>Our admission committee will review your application and notify you within 3-5 working days.</p>
    </div>
    <div class="ef">© {{ date('Y') }} School ERP. All Rights Reserved.</div>
</div>
</body>
</html>
