<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'DejaVu Sans', 'Noto Sans', sans-serif; color: #1f2937; margin: 0; padding: 24px; }
        .header { border-bottom: 2px solid #059669; padding-bottom: 12px; margin-bottom: 24px; }
        .title { font-size: 24px; font-weight: bold; color: #065f46; }
        .details-table { width: 100%; margin-bottom: 24px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        .items-table th, .items-table td { border: 1px solid #d1d5db; padding: 8px 12px; text-align: left; }
        .items-table th { background: #f3f4f6; }
        .total-box { margin-top: 20px; text-align: right; font-size: 16px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ $invoice->organization->name }} - Sustainability Invoice / চালানপত্র</div>
        <div style="font-size: 13px; color: #6b7280;">Invoice Number: {{ $invoice->invoice_number }} | Date: {{ $invoice->created_at->toDateString() }}</div>
    </div>

    <table class="details-table">
        <tr>
            <td><strong>Billed To:</strong><br>{{ $invoice->client_name }}<br>{{ $invoice->billing_email }}</td>
            <td style="text-align: right;"><strong>Due Date:</strong> {{ $invoice->due_date }}<br><strong>Status:</strong> {{ strtoupper($invoice->status) }}</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Unit Price</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($item->unit_price_cents / 100, 2) }} {{ $invoice->currency }}</td>
                    <td style="text-align: right;">{{ number_format($item->line_total_cents / 100, 2) }} {{ $invoice->currency }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <strong>Subtotal:</strong> {{ number_format($invoice->subtotal_cents / 100, 2) }} {{ $invoice->currency }}<br>
        <strong>Tax / VAT:</strong> {{ number_format($invoice->tax_amount_cents / 100, 2) }} {{ $invoice->currency }}<br>
        <span style="font-size: 18px; color: #065f46;"><strong>Total Due:</strong> {{ number_format($invoice->total_amount_cents / 100, 2) }} {{ $invoice->currency }}</span>
    </div>
</body>
</html>

