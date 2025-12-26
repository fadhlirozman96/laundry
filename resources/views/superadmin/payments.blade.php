@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <h3 class="page-title">Payment History</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Payments</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <select class="select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="completed">Completed</option>
                                <option value="pending">Pending</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search by Transaction ID" id="searchInput">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Transaction ID</th>
                                <th>Customer</th>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Payment Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                            <tr>
                                <td>#{{ $payment->id }}</td>
                                <td>
                                    <code>{{ $payment->transaction_id ?? 'N/A' }}</code>
                                </td>
                                <td>
                                    <div>
                                        <p class="mb-0"><strong>{{ $payment->subscription->user->name ?? 'N/A' }}</strong></p>
                                        <p class="text-muted mb-0 small">{{ $payment->subscription->user->email ?? 'N/A' }}</p>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $payment->subscription->plan->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <strong>{{ $payment->currency ?? 'MYR' }} {{ number_format($payment->amount, 2) }}</strong>
                                </td>
                                <td>
                                    @if($payment->payment_method)
                                    <span class="badge bg-secondary">{{ strtoupper(str_replace('_', ' ', $payment->payment_method)) }}</span>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $payment->payment_date ? $payment->payment_date->format('d M Y H:i') : 'N/A' }}
                                </td>
                                <td>
                                    @if($payment->status === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                    @elseif($payment->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @elseif($payment->status === 'failed')
                                    <span class="badge bg-danger">Failed</span>
                                    @else
                                    <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No payments found</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                <td colspan="4">
                                    <strong>MYR {{ number_format($payments->sum('amount'), 2) }}</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper mt-3">
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

