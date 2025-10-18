<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock History Report</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            background-color: #f5f1ea; /* Coffee theme background */
            color: #5c4d3c; /* Coffee theme primary text */
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #5c4d3c;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #5c4d3c;
            font-size: 24px;
        }
        .header .period, .header div {
            color: #8c7b6b; /* Coffee theme secondary text */
            font-size: 14px;
        }
        .summary {
            background: #fdfbf7; /* Lighter cream for summary */
            padding: 15px;
            border-radius: 0.75rem;
            border: 1px solid #e0d6c2; /* Coffee theme border */
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f5f1ea; /* Coffee theme table header */
            color: #5c4d3c;
            padding: 10px;
            text-align: left;
            border: 1px solid #e0d6c2;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
        }
        td {
            padding: 10px;
            border: 1px solid #e0d6c2;
            color: #5c4d3c;
        }
        tr:nth-child(even) {
             background-color: #f9f7f3; /* Coffee theme hover/alternate color */
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #8c7b6b;
            font-size: 10px;
            border-top: 1px solid #e0d6c2;
            padding-top: 10px;
        }
        .section-title {
            background-color: #f5f1ea;
            border: 1px solid #e0d6c2;
            border-bottom: none;
            color: #5c4d3c;
            padding: 10px;
            margin: 20px 0 0 0;
            font-weight: bold;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
        }
        .negative {
            color: #c45e4c; /* Coffee theme danger color */
            font-weight: bold;
        }
        .positive {
            color: #6f8c6b; /* Coffee theme success/active color */
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Stock History Report</h1>
        <div class="period">
            Period: {{ $start_date->format('F j, Y') }} to {{ $end_date->format('F j, Y') }}
        </div>
        <div>Generated on: {{ $generated_at->format('F j, Y g:i A') }}</div>
    </div>

    <div class="summary">
        <strong>Report Summary:</strong><br>
        • Period: {{ $start_date->format('M j, Y') }} to {{ $end_date->format('M j, Y') }}<br>
        • Total Movements: {{ $total_movements }}<br>
        • Report Date: {{ $generated_at->format('M j, Y g:i A') }}
    </div>

    <div class="section-title">Stock Movement Details</div>
    <table>
        <thead>
            <tr>
                <th>Date & Time</th>
                <th>Ingredient</th>
                <th>Type</th>
                <th>Previous Stock</th>
                <th>New Stock</th>
                <th>Change Amount</th>
                <th>Reason</th>
                <th>User</th>
                <th>Order ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stock_history as $history)
            <tr>
                <td>{{ $history['date'] }}</td>
                <td><strong>{{ $history['ingredient']->name }}</strong> ({{ $history['ingredient']->unit }})</td>
                <td>{{ $history['change_type'] }}</td>
                <td>{{ number_format($history['movement']->previous_stock, 2) }}</td>
                <td>{{ number_format($history['movement']->new_stock, 2) }}</td>
                <td class="{{ $history['movement']->change_amount < 0 ? 'negative' : 'positive' }}">
                    {{ $history['movement']->change_amount > 0 ? '+' : '' }}{{ number_format($history['movement']->change_amount, 2) }}
                </td>
                <td>{{ $history['movement']->reason ?? 'N/A' }}</td>
                <td>{{ $history['user'] }}</td>
                <td>
                    @if($history['movement']->order_id)
                        #{{ $history['movement']->order_id }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if(count($stock_history) === 0)
    <div style="text-align: center; padding: 20px; color: #8c7b6b; border: 1px solid #e0d6c2; background-color: #fff;">
        No stock movements found for the selected period.
    </div>
    @endif

    <div class="footer">
        Report generated by CupsStreet POS System | Page 1 of 1
    </div>
</body>
</html>