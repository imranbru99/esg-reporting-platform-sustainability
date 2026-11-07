<!DOCTYPE html>
<html lang="{{ $language ?? 'en' }}">
<head>
    <meta charset="utf-8">
    <title>{{ $project->title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Noto Sans', sans-serif;
            color: #1a202c;
            line-height: 1.6;
            margin: 0;
            padding: 24px;
        }
        .header {
            border-bottom: 3px solid #059669;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .org-name {
            font-size: 24px;
            font-weight: bold;
            color: #065f46;
        }
        .report-title {
            font-size: 20px;
            color: #111827;
            margin-top: 4px;
        }
        .meta-badges {
            margin-top: 8px;
            font-size: 12px;
            color: #4b5563;
        }
        .badge {
            display: inline-block;
            background: #e6f4ea;
            color: #047857;
            padding: 4px 8px;
            border-radius: 4px;
            margin-right: 8px;
            font-weight: bold;
        }
        .section-box {
            margin-bottom: 24px;
            padding: 16px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background: #ffffff;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        .citations-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            font-size: 11px;
        }
        .citations-table th, .citations-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: left;
        }
        .citations-table th {
            background: #f1f5f9;
            color: #334155;
        }
        .integrity-box {
            background: #f8fafc;
            border: 1px dashed #94a3b8;
            padding: 12px;
            border-radius: 4px;
            font-size: 10px;
            color: #64748b;
            margin-top: 32px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="org-name">{{ $project->organization->name }}</div>
        <div class="report-title">{{ $project->title }}</div>
        <div class="meta-badges">
            <span class="badge">{{ strtoupper($project->primary_framework) }}</span>
            <span class="badge">{{ $project->reporting_period }} Reporting Period</span>
            <span class="badge">{{ strtoupper($project->language) }}</span>
            <span class="badge">{{ $project->status === 'locked' ? 'Locked & Assured' : 'Draft' }}</span>
        </div>
    </div>

    @foreach($project->sections as $section)
        <div class="section-box">
            <div class="section-title">{{ $section->title }}</div>
            <div class="section-content">
                {!! nl2br(e($section->content_markdown)) !!}
            </div>

            @if($section->citations->isNotEmpty())
                <h4 style="font-size: 12px; margin-top: 14px; margin-bottom: 4px; color: #475569;">Verified Metric Data Points (Audit Trail)</h4>
                <table class="citations-table">
                    <thead>
                        <tr>
                            <th>Ref</th>
                            <th>Metric Indicator</th>
                            <th>Reported Value</th>
                            <th>Data Source</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($section->citations as $cite)
                            <tr>
                                <td>{{ $cite->citation_token }}</td>
                                <td>{{ $cite->label }}</td>
                                <td><strong>{{ $cite->formatted_value }}</strong></td>
                                <td>{{ $cite->sourceEvidence ? $cite->sourceEvidence->title : 'Verified System Log' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endforeach

    <div class="integrity-box">
        <strong>Report Integrity Fingerprint (SHA-256):</strong> {{ $project->sha256_hash ?? 'Pending publication lock' }}<br>
        <strong>Sign-off Verification:</strong> 
        @forelse($project->signoffs as $signoff)
            {{ $signoff->role_title }} ({{ $signoff->user->name }}) on {{ $signoff->signed_at->toFormattedDateString() }};
        @empty
            No signoffs recorded yet.
        @endforelse
    </div>
</body>
</html>

