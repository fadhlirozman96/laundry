<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #0067e2;
        }
        .logo-section h1 {
            color: #0067e2;
            font-size: 28px;
            margin-bottom: 5px;
        }
        .logo-section p {
            color: #666;
            font-size: 11px;
        }
        .receipt-info {
            text-align: right;
        }
        .receipt-info h2 {
            color: #0067e2;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .receipt-info p {
            margin-bottom: 3px;
        }
        .receipt-info .ref {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-box {
            flex: 1;
        }
        .info-box h3 {
            color: #0067e2;
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .info-box p {
            margin-bottom: 5px;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead {
            background-color: #0067e2;
            color: #fff;
        }
        thead th {
            padding: 10px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
        }
        tbody td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }
        tbody tr:hover {
            background-color: #f8f9fa;
        }
        .totals {
            margin-top: 20px;
            margin-left: auto;
            width: 300px;
        }
        .totals table {
            margin-bottom: 0;
        }
        .totals td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
            font-size: 12px;
        }
        .totals td:first-child {
            text-align: right;
            font-weight: 600;
            color: #666;
        }
        .totals td:last-child {
            text-align: right;
            font-weight: 600;
            color: #333;
        }
        .totals .grand-total td {
            border-top: 2px solid #0067e2;
            border-bottom: 2px solid #0067e2;
            font-size: 16px;
            font-weight: bold;
            color: #0067e2;
            padding: 12px 10px;
        }
        .qr-section {
            margin-top: 30px;
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }
        .qr-section h3 {
            color: #0067e2;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .qr-code {
            display: inline-block;
            padding: 10px;
            background: #fff;
            border: 2px solid #0067e2;
            border-radius: 8px;
        }
        .qr-code img {
            width: 150px;
            height: 150px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        .payment-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .payment-info h3 {
            color: #0067e2;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .payment-info p {
            margin-bottom: 5px;
            font-size: 11px;
        }
        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-section">
            <h1>{{ $order->store->name ?? 'Laundry Service' }}</h1>
            <p>{{ $order->store->address ?? 'Address not available' }}</p>
            <p>Phone: {{ $order->store->phone ?? 'N/A' }} | Email: {{ $order->store->email ?? 'N/A' }}</p>
        </div>
        <div class="receipt-info">
            <h2>RECEIPT</h2>
            <p class="ref">Receipt #: {{ $order->order_number }}</p>
            <p>Date: {{ $order->created_at->format('d M Y') }}</p>
            <p>Time: {{ $order->created_at->format('H:i:s') }}</p>
            <p>Payment: {{ strtoupper($order->payment_method ?? 'N/A') }}</p>
        </div>
    </div>

    <div class="info-section">
        <div class="info-box">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> {{ $order->customer_name ?? 'Walk-in Customer' }}</p>
            @if($order->customer_phone)
            <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
            @endif
            @if($order->customer_email)
            <p><strong>Email:</strong> {{ $order->customer_email }}</p>
            @endif
        </div>
        <div class="info-box">
            <h3>Order Information</h3>
            <p><strong>Order #:</strong> {{ $order->order_number }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->order_status) }}</p>
            @if($order->expected_completion)
            <p><strong>Expected Completion:</strong> {{ $order->expected_completion->format('d M Y H:i') }}</p>
            @endif
            @if($order->user)
            <p><strong>Processed by:</strong> {{ $order->user->name }}</p>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40%;">Item</th>
                <th style="width: 12%;">Qty</th>
                <th style="width: 18%;">Unit Price (MYR)</th>
                <th style="width: 18%;">Subtotal (MYR)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>
                    {{ number_format($item->quantity, 2) }} 
                    @if($item->product && $item->product->unit_type)
                        {{ strtoupper($item->product->unit_type) }}
                    @else
                        PCS
                    @endif
                </td>
                <td>{{ number_format($item->price, 2) }}</td>
                <td>{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td>MYR {{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->tax > 0)
            <tr>
                <td>Tax ({{ $order->order_tax_percent ?? 6 }}%):</td>
                <td>MYR {{ number_format($order->tax, 2) }}</td>
            </tr>
            @endif
            @if($order->shipping > 0)
            <tr>
                <td>Shipping:</td>
                <td>MYR {{ number_format($order->shipping, 2) }}</td>
            </tr>
            @endif
            @if($order->discount > 0)
            <tr>
                <td>Discount:</td>
                <td>- MYR {{ number_format($order->discount, 2) }}</td>
            </tr>
            @endif
            <tr class="grand-total">
                <td>Total Paid:</td>
                <td>MYR {{ number_format($order->total, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="payment-info">
        <h3>Payment Details</h3>
        <p><strong>Payment Method:</strong> 
            @if($order->payment_method == 'cash')
                CASH
            @elseif($order->payment_method == 'qr')
                QR CODE / E-WALLET
            @elseif($order->payment_method == 'card' || $order->payment_method == 'debit_card')
                DEBIT/CREDIT CARD
            @else
                {{ strtoupper($order->payment_method ?? 'N/A') }}
            @endif
        </p>
        <p><strong>Payment Status:</strong> {{ strtoupper($order->payment_status) }}</p>
        <p><strong>Amount Paid:</strong> MYR {{ number_format($order->total, 2) }}</p>
        <p><strong>Payment Date:</strong> {{ $order->created_at->format('d M Y H:i:s') }}</p>
        
        @if($order->expected_completion)
        <p><strong>Expected Completion:</strong> {{ $order->expected_completion->format('d M Y H:i') }}</p>
        @endif
        
        @if($order->special_instructions)
        <p><strong>Special Instructions:</strong> {{ $order->special_instructions }}</p>
        @endif
        
        @if($order->notes)
        <p><strong>Order Notes:</strong> {{ $order->notes }}</p>
        @endif
    </div>

    @if($order->notes)
    <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
        <h3 style="color: #0067e2; font-size: 14px; margin-bottom: 10px;">Notes</h3>
        <p style="font-size: 11px;">{{ $order->notes }}</p>
    </div>
    @endif

    <div class="qr-section">
        <h3>Order Verification QR Code</h3>
        <div class="qr-code">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(json_encode([
                'order_number' => $order->order_number,
                'total' => $order->total,
                'date' => $order->created_at->format('Y-m-d H:i:s'),
                'store' => $order->store->name ?? 'N/A',
            ])) }}" alt="QR Code">
        </div>
        <p style="margin-top: 10px; font-size: 10px; color: #666;">Scan to verify order details</p>
    </div>

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>This is a computer-generated receipt. No signature required.</p>
        <p>Generated on {{ now()->format('d M Y H:i:s') }}</p>
    </div>
</body>
</html>

