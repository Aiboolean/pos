<!DOCTYPE html>
<html>
<head>
    <title>Sales Report - Cup Street</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            color: #5c4d3c; 
            line-height: 1.4; 
            font-size: 0.8em; 
            margin: 0; 
            padding: 15px; 
        }
        .header { 
            text-align: center; 
            margin-bottom: 1rem; 
            padding-bottom: 0.75rem; 
            border-bottom: 1px solid #d9c7b3; 
        }
        .header h1 { 
            margin: 0; 
            font-size: 1.3em; 
            color: #5c4d3c; 
        }
        .header p { 
            margin: 0.25rem 0 0 0; 
            color: #a67c52; 
            font-size: 0.9em; 
        }
        .period { 
            text-align: center; 
            margin-bottom: 1rem; 
            font-weight: bold; 
            background-color: #f8f3e9; 
            padding: 0.5rem; 
            border-radius: 4px; 
            font-size: 0.85em;
        }
        
        /* Executive Summary */
        .analysis { 
            margin: 1rem 0; 
            padding: 1rem; 
            background-color: #faf7f2; 
            border-radius: 6px; 
            border-left: 3px solid #a67c52; 
        }
        .analysis p { 
            margin: 0.5rem 0; 
            text-align: justify; 
            font-size: 0.85em; 
        }
        .highlight { 
            background-color: #fffaf0; 
            padding: 2px 5px; 
            border-radius: 3px; 
            font-weight: 600; 
        }
        
        /* Financial Summary - Full Width */
        .financial-summary {
            border: 1px solid #e6d7c1;
            border-radius: 6px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 1rem;
        }
        .financial-header {
            background-color: #f0e6d8;
            padding: 0.75rem;
            text-align: center;
            font-weight: bold;
            color: #5c4d3c;
            font-size: 0.8em;
            border-bottom: 1px solid #e6d7c1;
        }
        .financial-content {
            padding: 1rem;
        }
        .financial-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0e6d8;
        }
        .financial-row:last-child {
            border-bottom: none;
        }
        .financial-label {
            font-weight: 600;
            color: #5c4d3c;
        }
        .financial-value {
            font-weight: bold;
            color: #5c4d3c;
        }
        
        /* Product Comparison - Side by Side */
        .product-comparison { 
            display: flex; 
            gap: 1rem; 
        }
        .table-container { 
            flex: 1;
            border: 1px solid #e6d7c1; 
            border-radius: 6px; 
            overflow: hidden;
            background: #fff;
        }
        .table-header { 
            background-color: #f0e6d8; 
            padding: 0.75rem; 
            text-align: center; 
            font-weight: bold; 
            color: #5c4d3c; 
            font-size: 0.8em;
            border-bottom: 1px solid #e6d7c1;
        }
        
        /* Product Tables */
        .product-table { 
            width: 100%; 
            border-collapse: collapse;
            font-size: 0.75em; 
        }
        .product-table thead th { 
            padding: 0.6rem; 
            background-color: #f8f3e9; 
            color: #5c4d3c; 
            font-size: 0.8em;
            border-bottom: 2px solid #e6d7c1;
        }
        .product-table th:nth-child(1) { 
            text-align: left; 
            padding-left: 0.8rem;
            width: 50%;
        }
        .product-table th:nth-child(2) { 
            text-align: center; 
            width: 25%;
        }
        .product-table th:nth-child(3) { 
            text-align: right; 
            width: 25%;
            padding-right: 0.8rem;
        }
        
        .product-table td { 
            padding: 0.6rem; 
            border-bottom: 1px solid #f0e6d8; 
        }
        .product-table td:nth-child(1) { 
            text-align: left; 
            padding-left: 0.8rem;
        }
        .product-table td:nth-child(2) { 
            text-align: center; 
            font-variant-numeric: tabular-nums;
        }
        .product-table td:nth-child(3) { 
            text-align: right; 
            font-variant-numeric: tabular-nums;
            padding-right: 0.8rem;
        }
        .product-table tr:last-child td {
            border-bottom: none;
        }
        .top-product { 
            font-weight: bold; 
            background-color: #fffaf0; 
        }
        .bottom-product { 
            font-style: italic; 
            color: #a67c52; 
        }
        
        /* Footer */
        .footer { 
            margin-top: 1.5rem; 
            padding-top: 0.75rem; 
            text-align: center; 
            font-size: 0.7em; 
            color: #a67c52; 
            border-top: 1px solid #e6d7b3; 
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Cup Street</h1>
        <p>Sales Performance Report</p>
    </div>

    <!-- Reporting Period -->
    <div class="period">
        @if(request('start_date') && request('end_date'))
            {{ \Carbon\Carbon::parse(request('start_date'))->format('M j, Y') }} - {{ \Carbon\Carbon::parse(request('end_date'))->format('M j, Y') }}
        @else
            Complete Sales History
        @endif
    </div>

    <!-- Executive Summary -->
    <div class="analysis">
        <p>This period demonstrated <span class="highlight">strong sales performance</span> with ₱{{ number_format($totalSales, 2) }} generated from {{ $totalOrders }} transactions. Customer spending averaged ₱{{ number_format($averageOrderValue, 2) }} per order, indicating healthy engagement with our menu offerings.</p>

        <p>Product analysis reveals <span class="highlight">{{ $productPerformance->first()->product->name }}</span> as the top performer with {{ $productPerformance->first()->total_quantity }} units sold, while <span class="highlight">{{ $productPerformance->last()->product->name }}</span> presents the greatest opportunity for growth with only {{ $productPerformance->last()->total_quantity }} units sold.</p>

        <p>Operational efficiency remained high throughout the period, with all transactions processed accurately through our POS system.</p>
    </div>

    <!-- Financial Summary Table - Full Width -->
    <div class="financial-summary">
        <div class="financial-header">💰 Financial Summary</div>
        <div class="financial-content">
            <div class="financial-row">
                <span class="financial-label">Total Revenue:</span>
                <span class="financial-value">₱{{ number_format($totalSales, 2) }}</span>
            </div>
            <div class="financial-row">
                <span class="financial-label">Total Orders:</span>
                <span class="financial-value">{{ $totalOrders }}</span>
            </div>
            <div class="financial-row">
                <span class="financial-label">Average Order Value:</span>
                <span class="financial-value">₱{{ number_format($averageOrderValue, 2) }}</span>
            </div>
            <div class="financial-row">
                <span class="financial-label">Products Sold:</span>
                <span class="financial-value">{{ $productPerformance->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Product Comparison - Side by Side Tables -->
    <div class="product-comparison">
        <!-- Top 5 Products -->
        <div class="table-container">
            <div class="table-header">🏆 Top Products</div>
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productPerformance->take(5) as $product)
                    <tr class="{{ $loop->first ? 'top-product' : '' }}">
                        <td>{{ $product->product->name }}</td>
                        <td>{{ $product->total_quantity }}</td>
                        <td>₱{{ number_format($product->total_revenue, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Bottom 5 Products -->
        <div class="table-container">
            <div class="table-header">📈 Least Products</div>
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productPerformance->sortBy('total_quantity')->take(5) as $product)
                    <tr class="{{ $loop->last ? 'bottom-product' : '' }}">
                        <td>{{ $product->product->name }}</td>
                        <td>{{ $product->total_quantity }}</td>
                        <td>₱{{ number_format($product->total_revenue, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Generated on {{ now()->format('M j, Y g:i A') }} • Cup Street POS</p>
    </div>
</body>
</html>