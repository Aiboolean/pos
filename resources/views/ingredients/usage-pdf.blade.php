<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ingredient Usage Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header .period {
            color: #666;
            font-size: 14px;
        }
        .summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            text-align: center;
        }
        .summary-item {
            padding: 10px;
        }
        .summary-value {
            font-size: 24px;
            font-weight: bold;
            color: #2c5aa0;
        }
        .summary-label {
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #2c5aa0;
            color: white;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .low-stock {
            background-color: #fff3cd !important;
        }
        .out-of-stock {
            background-color: #f8d7da !important;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .section-title {
            background-color: #495057;
            color: white;
            padding: 8px;
            margin: 15px 0 10px 0;
            font-weight: bold;
        }
        .text-success { color: #28a745; }
        .text-warning { color: #ffc107; }
        .text-danger { color: #dc3545; }
    </style>
</head>
<body>
    <div class="header">
    <h1>Ingredient Usage Report</h1>
    <div class="period">
        Period: {{ $start_date->format('F j, Y') }} to {{ $end_date->format('F j, Y') }}
    </div>
    <div>Generated on: {{ $generated_at->format('F j, Y g:i A') }}</div>
    <div style="color: #dc3545; font-size: 10px; margin-top: 5px;">
        ⚠️ Note: Stock levels shown are current values. Historical stock levels are not available.
    </div>
</div>

    <!-- Summary Section -->
    <div class="summary">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-value">{{ $total_ingredients }}</div>
                <div class="summary-label">Total Ingredients</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">{{ $low_stock_count }}</div>
                <div class="summary-label">Low Stock</div>
            </div>
            <div class="summary-item">
                <div class="summary-value">{{ $period_days }}</div>
                <div class="summary-label">Days in Period</div>
            </div>
        </div>
    </div>

    <!-- Detailed Usage Table -->
    <div class="section-title">Ingredient Usage Details</div>
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
                        <span class="text-danger"> Out of Stock</span>
                    @elseif($data['status'] === 'Low Stock')
                        <span class="text-warning"> Low Stock</span>
                    @else
                        <span class="text-success"> In Stock</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Top Used Ingredients -->
    @if(count($usage_data) > 0)
    <div class="section-title">Top 5 Most Used Ingredients</div>
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