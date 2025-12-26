@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>SaaS Billing & Invoices</h4>
                <h6>Manage subscription invoices</h6>
            </div>
        </div>

        <!-- Stats -->
        <div class="row">
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-success">
                                <i data-feather="dollar-sign"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Total Billed</h6>
                            <h3>MYR {{ number_format($stats['total'], 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-primary">
                                <i data-feather="check-circle"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Paid</h6>
                            <h3>MYR {{ number_format($stats['paid'], 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-danger">
                                <i data-feather="alert-circle"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Overdue</h6>
                            <h3>{{ $stats['overdue'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-warning">
                                <i data-feather="clock"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Pending</h6>
                            <h3>{{ $stats['pending'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice List -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Business</th>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                            <tr>
                                <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                <td>{{ $invoice->business->name ?? 'N/A' }}</td>
                                <td>{{ $invoice->subscription->plan->name ?? 'N/A' }}</td>
                                <td>MYR {{ number_format($invoice->total_amount, 2) }}</td>
                                <td>{!! $invoice->getStatusBadge() !!}</td>
                                <td>{{ $invoice->issue_date->format('d M Y') }}</td>
                                <td>{{ $invoice->due_date->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('superadmin.invoices.show', $invoice->id) }}" class="btn btn-sm btn-info">
                                        <i data-feather="eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No invoices found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


