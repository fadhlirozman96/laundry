@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Invoice Details</h4>
                <h6>Invoice #{{ $invoice->invoice_number }}</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('superadmin.invoices') }}" class="btn btn-secondary">
                    <i data-feather="arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="invoice-details">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5>Invoice Information</h5>
                            <p><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
                            <p><strong>Business:</strong> {{ $invoice->business->name ?? 'N/A' }}</p>
                            <p><strong>Plan:</strong> {{ $invoice->subscription->plan->name ?? 'N/A' }}</p>
                            <p><strong>Status:</strong> {!! $invoice->getStatusBadge() !!}</p>
                        </div>
                        <div class="col-lg-6">
                            <h5>Dates</h5>
                            <p><strong>Issue Date:</strong> {{ $invoice->issue_date->format('d M Y') }}</p>
                            <p><strong>Due Date:</strong> {{ $invoice->due_date->format('d M Y') }}</p>
                            @if($invoice->paid_at)
                                <p><strong>Paid Date:</strong> {{ $invoice->paid_at->format('d M Y') }}</p>
                                <p><strong>Payment Method:</strong> {{ $invoice->payment_method }}</p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <h5>Amount Breakdown</h5>
                    <table class="table">
                        <tr>
                            <td>Subtotal</td>
                            <td class="text-end">MYR {{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Tax</td>
                            <td class="text-end">MYR {{ number_format($invoice->tax_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Discount</td>
                            <td class="text-end">-MYR {{ number_format($invoice->discount_amount, 2) }}</td>
                        </tr>
                        <tr class="fw-bold">
                            <td>Total</td>
                            <td class="text-end">MYR {{ number_format($invoice->total_amount, 2) }}</td>
                        </tr>
                    </table>

                    @if($invoice->notes)
                        <hr>
                        <h5>Notes</h5>
                        <p>{{ $invoice->notes }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

