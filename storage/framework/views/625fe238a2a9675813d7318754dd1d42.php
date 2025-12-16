<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation - <?php echo e($quotation->quotation_number); ?></title>
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
        .quotation-info {
            text-align: right;
        }
        .quotation-info h2 {
            color: #0067e2;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .quotation-info p {
            margin-bottom: 3px;
        }
        .quotation-info .ref {
            font-size: 14px;
            font-weight: bold;
        }
        .addresses {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .address-box {
            width: 48%;
        }
        .address-box h3 {
            color: #0067e2;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        .address-box p {
            margin-bottom: 3px;
        }
        .address-box .name {
            font-weight: bold;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #0067e2;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        table th:nth-child(2),
        table th:nth-child(3) {
            text-align: center;
        }
        table th:nth-child(4),
        table th:nth-child(5) {
            text-align: right;
        }
        table td {
            padding: 10px 8px;
            border-bottom: 1px solid #eee;
        }
        table td:nth-child(2),
        table td:nth-child(3) {
            text-align: center;
        }
        table td:nth-child(4),
        table td:nth-child(5) {
            text-align: right;
        }
        table tbody tr:hover {
            background-color: #f9f9f9;
        }
        .totals {
            width: 300px;
            margin-left: auto;
            margin-bottom: 30px;
        }
        .totals table {
            margin-bottom: 0;
        }
        .totals td {
            padding: 8px;
            border: none;
        }
        .totals td:first-child {
            text-align: right;
            padding-right: 20px;
        }
        .totals td:last-child {
            text-align: right;
            font-weight: 500;
        }
        .totals .grand-total {
            background-color: #0067e2;
            color: white;
        }
        .totals .grand-total td {
            font-size: 14px;
            font-weight: bold;
            padding: 12px 8px;
        }
        .notes-section, .terms-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .notes-section h3, .terms-section h3 {
            color: #0067e2;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .terms-section pre {
            white-space: pre-wrap;
            font-family: inherit;
            font-size: 11px;
            color: #666;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #999;
            font-size: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-accepted { background-color: #d4edda; color: #155724; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }
        
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
        
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #0067e2;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .print-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">🖨️ Print / Save PDF</button>

    <div class="header">
        <div class="logo-section">
            <h1><?php echo e($quotation->store->name ?? 'Store Name'); ?></h1>
            <p><?php echo e($quotation->store->address ?? ''); ?></p>
            <p><?php echo e($quotation->store->phone ?? ''); ?> <?php echo e($quotation->store->email ? '| ' . $quotation->store->email : ''); ?></p>
        </div>
        <div class="quotation-info">
            <h2>QUOTATION</h2>
            <p class="ref"><?php echo e($quotation->quotation_number); ?></p>
            <p><strong>Date:</strong> <?php echo e($quotation->created_at->format('d M Y')); ?></p>
            <p><strong>Valid Until:</strong> <?php echo e($quotation->valid_until ? \Carbon\Carbon::parse($quotation->valid_until)->format('d M Y') : '-'); ?></p>
            <p>
                <span class="status-badge status-<?php echo e($quotation->status); ?>">
                    <?php echo e(ucfirst($quotation->status)); ?>

                </span>
            </p>
        </div>
    </div>

    <div class="addresses">
        <div class="address-box">
            <h3>Bill To</h3>
            <p class="name"><?php echo e($quotation->customer_name ?: 'Walk in Customer'); ?></p>
            <?php if($quotation->customer_address): ?>
                <p><?php echo e($quotation->customer_address); ?></p>
            <?php endif; ?>
            <?php if($quotation->customer_phone): ?>
                <p>Phone: <?php echo e($quotation->customer_phone); ?></p>
            <?php endif; ?>
            <?php if($quotation->customer_email): ?>
                <p>Email: <?php echo e($quotation->customer_email); ?></p>
            <?php endif; ?>
        </div>
        <div class="address-box">
            <h3>From</h3>
            <p class="name"><?php echo e($quotation->store->name ?? 'Store'); ?></p>
            <?php if($quotation->store && $quotation->store->address): ?>
                <p><?php echo e($quotation->store->address); ?></p>
            <?php endif; ?>
            <?php if($quotation->store && $quotation->store->phone): ?>
                <p>Phone: <?php echo e($quotation->store->phone); ?></p>
            <?php endif; ?>
            <?php if($quotation->store && $quotation->store->email): ?>
                <p>Email: <?php echo e($quotation->store->email); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40%;">Product / Service</th>
                <th style="width: 12%;">Qty</th>
                <th style="width: 12%;">Unit</th>
                <th style="width: 18%;">Price (MYR)</th>
                <th style="width: 18%;">Subtotal (MYR)</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $quotation->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $unitName = $item->product && $item->product->unit 
                        ? ($item->product->unit->short_name ?: $item->product->unit->name) 
                        : 'pc';
                ?>
                <tr>
                    <td><?php echo e($item->product_name); ?></td>
                    <td><?php echo e(number_format($item->quantity, 2)); ?></td>
                    <td><?php echo e($unitName); ?></td>
                    <td><?php echo e(number_format($item->price, 2)); ?></td>
                    <td><?php echo e(number_format($item->subtotal, 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td>MYR <?php echo e(number_format($quotation->subtotal, 2)); ?></td>
            </tr>
            <tr>
                <td>Tax:</td>
                <td>MYR <?php echo e(number_format($quotation->tax ?? 0, 2)); ?></td>
            </tr>
            <tr>
                <td>Discount:</td>
                <td>- MYR <?php echo e(number_format($quotation->discount ?? 0, 2)); ?></td>
            </tr>
            <tr class="grand-total">
                <td>Total:</td>
                <td>MYR <?php echo e(number_format($quotation->total, 2)); ?></td>
            </tr>
        </table>
    </div>

    <?php if($quotation->notes && $quotation->notes != '<p><br></p>'): ?>
        <div class="notes-section">
            <h3>Notes</h3>
            <div><?php echo $quotation->notes; ?></div>
        </div>
    <?php endif; ?>

    <?php if($quotation->terms): ?>
        <div class="terms-section">
            <h3>Terms & Conditions</h3>
            <pre><?php echo e($quotation->terms); ?></pre>
        </div>
    <?php endif; ?>

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>This is a computer-generated document. No signature is required.</p>
        <p>Generated on <?php echo e(now()->format('d M Y, h:i A')); ?></p>
    </div>
</body>
</html>

<?php /**PATH C:\laragon\www\laundry\resources\views/pdf/quotation.blade.php ENDPATH**/ ?>