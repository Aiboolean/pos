<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ingredient Usage Report</title>
    <style>
        /* --- Coffee Shop Theme Applied --- */
        :root {
            --bg-color: #f5f1ea;
            --card-bg-color: #fff;
            --border-color: #e0d6c2;
            --primary-text: #5c4d3c;
            --secondary-text: #8c7b6b;
            --success-text: #6f8c6b;
            --warning-text: #c4a76c;
            --danger-text: #c45e4c;
            --success-bg: #e8f5e9;
            --warning-bg: #fff8e1;
            --danger-bg: #fbe9e7;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            background-color: var(--card-bg-color); /* Use white for printing */
            color: var(--primary-text);
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid var(--primary-text);
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: var(--primary-text);
            font-size: 26px;
            font-weight: 600;
        }
        .header .period, .header div {
            color: var(--secondary-text);
            font-size: 14px;
        }
        .summary {
            background: #fdfbf7;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            margin-bottom: 25px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .summary-item {
            text-align: center;
        }
        .summary-value {
            font-size: 28px;
            font-weight: bold;
            color: var(--primary-text);
            margin: 0;
        }
        .summary-label {
            font-size: 12px;
            color: var(--secondary-text);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th {
            background-color: var(--bg-color);
            color: var(--primary-text);
            padding: 12px;
            text-align: left;
            border: 1px solid var(--border-color);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            padding: 12px;
            border: 1px solid var(--border-color);
            vertical-align: middle;
        }
        tr:nth-child(even) {
            background-color: #f9f7f3;
        }
        .low-stock {
            background-color: var(--warning-bg) !important;
        }
        .out-of-stock {
            background-color: var(--danger-bg) !important;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: var(--secondary-text);
            font-size: 10px;
            border-top: 1px solid var(--border-color);
            padding-top: 15px;
        }
        .section-title {
            color: var(--primary-text);
            padding-bottom: 8px;
            margin: 30px 0 15px 0;
            font-weight: bold;
            font-size: 16px;
            border-bottom: 1px solid var(--border-color);
        }
        .text-success { color: var(--success-text); font-weight: 600; }
        .text-warning { color: var(--warning-text); font-weight: 600; }
        .text-danger { color: var(--danger-text); font-weight: 600; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Ingredient Usage Report</h1>
        <div class="period">
            Period: {{ $start_date->format('F j, Y') }} to {{ $end_date->format('F j, Y') }}
        </div>
        <div>Generated on: {{ $generated_at->format('F j, Y g:i A') }}</div>
        <div style="color: var(--danger-text); font-size: 10px; margin-top: 5px;">
            ⚠️ Note: Stock levels shown are current values.
        </div>
    </div>

    <div class="summary">
        <div class="summary-grid">
            <div class="summary-item">
                <p class="summary-value">{{ $total_ingredients }}</p>
                <p class="summary-label">Total Ingredients</p>
            </div>
            <div class="summary-item">
                <p class="summary-value">{{ $low_stock_count }}</p>
                <p class="summary-label">Items with Low Stock</p>
            </div>
            <div class="summary-item">
                <p class="summary-value">{{ $period_days }}</p>
                <p class="summary-label">Days in Period</p>
            </div>
        </div>
    </div>

    <h2 class="section-title">Ingredient Usage Details</h2>
    <table>
        <thead>
            <tr>
                <th>Ingredient</th>
                <th>Unit</th>
                <th>Current Stock</th>
                <th>Alert Threshold</th>
                <th>Total Used (Period)</th>
                <th>Daily Usage Rate</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usage_data as $data)
            @php
                $ingredient = $data['ingredient'];
                $statusClass = $data['status'] === 'Out of Stock' ? 'out-of-stock' : 
                               ($data['status'] === 'Low Stock' ? 'low-stock' : '');
            @endphp
            <tr class="{{ $statusClass }}">
                <td><strong>{{ $ingredient->name }}</strong></td>
                <td>{{ $ingredient->unit }}</td>
                <td>{{ number_format($data['current_stock'], 2) }}</td>
                <td>{{ number_format($data['alert_threshold'], 2) }}</td>
                <td><strong>{{ number_format($data['total_used'], 2) }}</strong></td>
                <td>{{ number_format($data['usage_rate_per_day'], 2) }}/day</td>
                <td>
                    @if($data['status'] === 'Out of Stock')
                        <span class="text-danger">● Out of Stock</span>
                    @elseif($data['status'] === 'Low Stock')
                        <span class="text-warning">● Low Stock</span>
                    @else
                        <span class="text-success">● In Stock</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if(count($usage_data) > 0)
    <h2 class="section-title">Top 5 Most Used Ingredients</h2>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Ingredient</th>
                <th>Total Used</th>
                <th>Daily Usage</th>
                <th>% of Total Usage</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalUsage = array_sum(array_column($usage_data, 'total_used'));
            @endphp
            @foreach(array_slice($usage_data, 0, 5) as $index => $data)
            <tr>
                <td>#{{ $index + 1 }}</td>
                <td>{{ $data['ingredient']->name }}</td>
                <td>{{ number_format($data['total_used'], 2) }} {{ $data['ingredient']->unit }}</td>
                <td>{{ number_format($data['usage_rate_per_day'], 2) }}/day</td>
                <td>
                    @if($totalUsage > 0)
                        {{ number_format(($data['total_used'] / $totalUsage) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        Report generated by CupsStreet POS System | Page 1 of 1
    </div>
</body>
</html>