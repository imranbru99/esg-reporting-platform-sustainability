<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate of Completion</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Noto Sans', sans-serif;
            text-align: center;
            border: 10px solid #059669;
            padding: 40px;
            margin: 20px;
        }
        .org-name { font-size: 22px; font-weight: bold; color: #065f46; margin-bottom: 20px; }
        .cert-header { font-size: 32px; font-weight: bold; color: #111827; margin-bottom: 12px; }
        .presented-to { font-size: 14px; color: #4b5563; margin-bottom: 16px; }
        .recipient { font-size: 26px; font-weight: bold; color: #047857; text-decoration: underline; margin-bottom: 24px; }
        .course-title { font-size: 20px; color: #1f2937; margin-bottom: 20px; }
        .footer { margin-top: 50px; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="org-name">{{ $cert->organization->name }} Academy</div>
    <div class="cert-header">CERTIFICATE OF COMPLETION / সনদপত্র</div>
    <div class="presented-to">This certificate is proudly awarded to</div>
    <div class="recipient">{{ $cert->recipient_name }}</div>
    <div class="course-title">for successfully completing the specialized training curriculum:<br><strong>{{ $cert->course->title }}</strong></div>
    <div style="font-size: 14px; color: #374151;">Issued on: {{ $cert->completion_date->toFormattedDateString() }}</div>

    <div class="footer">
        Certificate ID: {{ $cert->certificate_number }}<br>
        Verified by ESG Reporting Platform Digital Credentials Authority
    </div>
</body>
</html>

