<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thermal Receipt - {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            width: 80mm;
            max-width: 80mm;
            margin: 0 auto;
            padding: 10px;
            font-size: 12px;
            line-height: 1.4;
            background: white;
            color: black;
        }
        
        .center {
            text-align: center;
        }
        
        .bold {
            font-weight: bold;
        }
        
        .store-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .store-info {
            font-size: 10px;
            margin-bottom: 3px;
        }
        
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        
        .receipt-header {
            margin-bottom: 10px;
        }
        
        .receipt-info {
            font-size: 11px;
            margin-bottom: 3px;
        }
        
        .items-table {
            width: 100%;
            margin: 10px 0;
            font-size: 11px;
        }
        
        .items-table td {
            padding: 3px 0;
        }
        
        .item-name {
            font-weight: bold;
        }
        
        .item-details {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
        }
        
        .totals {
            margin-top: 10px;
            font-size: 11px;
        }
        
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
        }
        
        .grand-total {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 5px 0;
            margin: 5px 0;
        }
        
        .payment-info {
            margin-top: 10px;
            font-size: 11px;
        }
        
        .footer {
            margin-top: 15px;
            font-size: 10px;
        }
        
        .qr-code {
            text-align: center;
            margin: 15px 0;
        }
        
        .qr-code img {
            width: 120px;
            height: 120px;
        }
        
        @media print {
            body {
                width: 80mm;
                margin: 0;
                padding: 10px;
            }
            
            .divider {
                border-top: 1px dashed #000 !important;
            }
        }
    </style>
</head>
<body>
    <!-- Store Header -->
    <div class="center receipt-header">
        <div class="store-name">{{ $order->store->name ?? 'LAUNDRY SYSTEM' }}</div>
        @if($order->store)
        <div class="store-info">{{ $order->store->address ?? '' }}</div>
        <div class="store-info">Tel: {{ $order->store->phone ?? 'N/A' }}</div>
        @endif
    </div>
    
    <div class="divider"></div>
    
    <!-- Receipt Info -->
    <div class="receipt-info center bold">SALES RECEIPT</div>
    <div class="divider"></div>
    
    <div class="receipt-info">Receipt No: {{ $order->order_number }}</div>
    <div class="receipt-info">Date: {{ $order->created_at->format('d/m/Y H:i') }}</div>
    <div class="receipt-info">Cashier: {{ $order->user->name ?? 'N/A' }}</div>
    
    @if($order->customer_name && $order->customer_name !== 'Walk-in Customer')
    <div class="divider"></div>
    <div class="receipt-info">Customer: {{ $order->customer_name }}</div>
    @if($order->customer_phone)
    <div class="receipt-info">Phone: {{ $order->customer_phone }}</div>
    @endif
    @endif
    
    <div class="divider"></div>
    
    <!-- Items -->
    <table class="items-table">
        @foreach($order->items as $item)
        <tr>
            <td colspan="2" class="item-name">{{ $item->product_name }}</td>
        </tr>
        <tr>
            <td>
                {{ number_format($item->quantity, 2) }} 
                @if($item->product && $item->product->unit_type)
                    {{ strtoupper($item->product->unit_type) }}
                @else
                    PCS
                @endif
                x MYR {{ number_format($item->price, 2) }}
            </td>
            <td style="text-align: right;">MYR {{ number_format($item->subtotal, 2) }}</td>
        </tr>
        <tr><td colspan="2" style="height: 5px;"></td></tr>
        @endforeach
    </table>
    
    <div class="divider"></div>
    
    <!-- Totals -->
    <div class="totals">
        <div class="totals-row">
            <span>Subtotal:</span>
            <span>MYR {{ number_format($order->subtotal, 2) }}</span>
        </div>
        
        @if($order->tax > 0)
        <div class="totals-row">
            <span>Tax ({{ $order->order_tax_percent ?? 0 }}%):</span>
            <span>MYR {{ number_format($order->tax, 2) }}</span>
        </div>
        @endif
        
        @if($order->discount > 0)
        <div class="totals-row">
            <span>Discount:</span>
            <span>- MYR {{ number_format($order->discount, 2) }}</span>
        </div>
        @endif
        
        <div class="grand-total totals-row">
            <span>TOTAL:</span>
            <span>MYR {{ number_format($order->total, 2) }}</span>
        </div>
    </div>
    
    <!-- Payment Info -->
    <div class="payment-info">
        <div class="totals-row bold">
            <span>PAID (
                @if($order->payment_method == 'cash')
                    CASH
                @elseif($order->payment_method == 'qr')
                    QR/E-WALLET
                @elseif($order->payment_method == 'card' || $order->payment_method == 'debit_card')
                    CARD
                @else
                    {{ strtoupper($order->payment_method ?? 'CASH') }}
                @endif
            ):</span>
            <span>MYR {{ number_format($order->total, 2) }}</span>
        </div>
        <div class="totals-row">
            <span>Change:</span>
            <span>MYR 0.00</span>
        </div>
    </div>
    
    @if($order->special_instructions)
    <div class="divider"></div>
    <div class="center receipt-info">
        <strong>Special Instructions:</strong><br>
        {{ $order->special_instructions }}
    </div>
    @endif
    
    @if($order->notes)
    <div class="divider"></div>
    <div class="center receipt-info">
        <strong>Notes:</strong><br>
        {{ $order->notes }}
    </div>
    @endif
    
    <div class="divider"></div>
    
    <!-- Expected Completion -->
    @if($order->expected_completion)
    <div class="center receipt-info bold">
        Expected Completion
    </div>
    <div class="center receipt-info">
        {{ $order->expected_completion->format('d M Y H:i') }}
    </div>
    <div class="divider"></div>
    @endif
    
    <!-- QR Code -->
    <div class="qr-code">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode(route('laundry.show', $order->id)) }}" alt="QR Code">
        <div style="font-size: 10px; margin-top: 5px;">Scan for Order Details</div>
    </div>
    
    <div class="divider"></div>
    
    <!-- Footer -->
    <div class="footer center">
        <div>Thank you for your business!</div>
        <div>Please keep this receipt</div>
        <div style="margin-top: 5px;">{{ $order->store->email ?? '' }}</div>
    </div>
    
    <div class="divider"></div>
    
    <!-- Powered By -->
    <div class="center" style="font-size: 9px; margin-top: 10px;">
        Powered by Laundry Management System
    </div>
</body>
</html>

