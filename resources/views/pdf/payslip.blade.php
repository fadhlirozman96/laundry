<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - {{ $payroll->month_year }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .container {
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
        }
        .info-section {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .info-left, .info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .info-left p, .info-right p {
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .salary-table th, .salary-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .salary-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .salary-table .amount {
            text-align: right;
        }
        .total-row {
            background-color: #e9e9e9;
            font-weight: bold;
        }
        .net-salary {
            background-color: #4caf50;
            color: white;
        }
        .net-salary td {
            font-size: 14px;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 50px;
        }
        .signature-box {
            display: table-cell;
            width: 33%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $payroll->store ? $payroll->store->name : 'Company Name' }}</h1>
            <p>{{ $payroll->store ? $payroll->store->address : '' }}</p>
            <h2 style="margin-top: 15px;">PAYSLIP</h2>
            <p>For the month of {{ $payroll->month_year }}</p>
        </div>

        <div class="info-section">
            <div class="info-left">
                <p><span class="info-label">Employee Name:</span> {{ $payroll->user ? $payroll->user->name : '-' }}</p>
                <p><span class="info-label">Employee ID:</span> {{ $payroll->user && $payroll->user->employee_id ? $payroll->user->employee_id : '-' }}</p>
                <p><span class="info-label">Email:</span> {{ $payroll->user ? $payroll->user->email : '-' }}</p>
            </div>
            <div class="info-right">
                <p><span class="info-label">Department:</span> {{ $payroll->user && $payroll->user->department ? $payroll->user->department->name : '-' }}</p>
                <p><span class="info-label">Designation:</span> {{ $payroll->user && $payroll->user->designation ? $payroll->user->designation->name : '-' }}</p>
                <p><span class="info-label">Payment Date:</span> {{ $payroll->payment_date ? $payroll->payment_date->format('d M Y') : '-' }}</p>
            </div>
        </div>

        <table class="salary-table">
            <thead>
                <tr>
                    <th colspan="2">Earnings</th>
                    <th colspan="2">Deductions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Basic Salary</td>
                    <td class="amount">MYR {{ number_format($payroll->basic_salary, 2) }}</td>
                    <td>PF</td>
                    <td class="amount">MYR {{ number_format($payroll->pf_deduction, 2) }}</td>
                </tr>
                <tr>
                    <td>HRA Allowance</td>
                    <td class="amount">MYR {{ number_format($payroll->hra_allowance, 2) }}</td>
                    <td>Professional Tax</td>
                    <td class="amount">MYR {{ number_format($payroll->professional_tax, 2) }}</td>
                </tr>
                <tr>
                    <td>Conveyance</td>
                    <td class="amount">MYR {{ number_format($payroll->conveyance, 2) }}</td>
                    <td>TDS</td>
                    <td class="amount">MYR {{ number_format($payroll->tds, 2) }}</td>
                </tr>
                <tr>
                    <td>Medical Allowance</td>
                    <td class="amount">MYR {{ number_format($payroll->medical_allowance, 2) }}</td>
                    <td>Loans & Others</td>
                    <td class="amount">MYR {{ number_format($payroll->loans_deduction, 2) }}</td>
                </tr>
                <tr>
                    <td>Bonus</td>
                    <td class="amount">MYR {{ number_format($payroll->bonus, 2) }}</td>
                    <td>Other Deductions</td>
                    <td class="amount">MYR {{ number_format($payroll->other_deduction, 2) }}</td>
                </tr>
                <tr>
                    <td>Other Allowance</td>
                    <td class="amount">MYR {{ number_format($payroll->other_allowance, 2) }}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr class="total-row">
                    <td>Total Earnings</td>
                    <td class="amount">MYR {{ number_format($payroll->basic_salary + $payroll->total_allowance, 2) }}</td>
                    <td>Total Deductions</td>
                    <td class="amount">MYR {{ number_format($payroll->total_deduction, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="salary-table">
            <tr class="net-salary">
                <td>NET SALARY</td>
                <td class="amount" style="text-align: right;">MYR {{ number_format($payroll->net_salary, 2) }}</td>
            </tr>
        </table>

        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">Employee Signature</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">HR Manager</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Authorized Signatory</div>
            </div>
        </div>

        <div class="footer">
            <p>This is a computer-generated payslip. No signature is required.</p>
            <p>Copyright © {{ date('Y') }} {{ $payroll->store ? $payroll->store->name : 'Company' }}. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>


