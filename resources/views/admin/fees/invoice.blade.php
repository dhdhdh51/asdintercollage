<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
    .header { background: #4f46e5; color: white; padding: 20px; text-align: center; }
    .invoice-box { max-width: 800px; margin: auto; }
    .section { padding: 15px; border-bottom: 1px solid #eee; }
    .row { display: flex; justify-content: space-between; margin-bottom: 8px; }
    .total { background: #f8f9fa; padding: 10px; font-size: 14px; font-weight: bold; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #f1f5f9; padding: 8px; text-align: left; }
    td { padding: 8px; border-bottom: 1px solid #eee; }
    .status-paid { color: #16a34a; font-weight: bold; }
    .status-pending { color: #d97706; font-weight: bold; }
</style>
</head>
<body>
<div class="invoice-box">
    <div class="header">
        <h1 style="margin:0;font-size:24px;">FEE INVOICE</h1>
        <p style="margin:5px 0;">{{ config('app.name') }}</p>
    </div>

    <div class="section">
        <div class="row">
            <div>
                <strong>Invoice Number:</strong> {{ $fee->invoice_number }}<br>
                <strong>Date:</strong> {{ now()->format('d M Y') }}<br>
                <strong>Due Date:</strong> {{ $fee->due_date->format('d M Y') }}
            </div>
            <div>
                <strong>Student Name:</strong> {{ $fee->student->user->name }}<br>
                <strong>Student ID:</strong> {{ $fee->student->student_id }}<br>
                <strong>Class:</strong> {{ $fee->student->class->name ?? 'N/A' }}
            </div>
        </div>
    </div>

    <div class="section">
        <table>
            <thead>
                <tr><th>Description</th><th>Month</th><th>Amount</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $fee->category->name }}</td>
                    <td>{{ $fee->month ?? $fee->academic_year }}</td>
                    <td>₹{{ number_format($fee->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="row"><span>Subtotal</span><span>₹{{ number_format($fee->amount, 2) }}</span></div>
        <div class="row"><span>Discount</span><span>-₹{{ number_format($fee->discount, 2) }}</span></div>
        <div class="row"><span>Fine / Late Fee</span><span>+₹{{ number_format($fee->fine, 2) }}</span></div>
        <div class="row"><span>Amount Paid</span><span>₹{{ number_format($fee->paid_amount, 2) }}</span></div>
    </div>

    <div class="total">
        <div class="row">
            <span>BALANCE DUE</span>
            <span>₹{{ number_format($fee->balance, 2) }}</span>
        </div>
        <div class="row" style="margin-top:5px;">
            <span>STATUS</span>
            <span class="status-{{ $fee->status === 'paid' ? 'paid' : 'pending' }}">{{ strtoupper($fee->status) }}</span>
        </div>
    </div>

    <div style="padding:15px;text-align:center;color:#64748b;font-size:11px;">
        This is a computer generated invoice. For queries contact the school office.
    </div>
</div>
</body>
</html>
