@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <h3 class="page-title">Subscription Management</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Subscriptions</li>
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
                                <option value="active">Active</option>
                                <option value="pending">Pending</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <select class="select" id="planFilter">
                                <option value="">All Plans</option>
                                @foreach(\App\Models\Plan::all() as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscriptions Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Plan</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Next Renewal</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscriptions as $subscription)
                            <tr>
                                <td>#{{ $subscription->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0"><strong>{{ $subscription->user->name ?? 'N/A' }}</strong></p>
                                            <p class="text-muted mb-0 small">{{ $subscription->user->email ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-info">{{ $subscription->plan->name ?? 'N/A' }}</span></td>
                                <td>{{ $subscription->start_date ? $subscription->start_date->format('d M Y') : 'N/A' }}</td>
                                <td>{{ $subscription->end_date ? $subscription->end_date->format('d M Y') : 'N/A' }}</td>
                                <td>{{ $subscription->next_renewal_at ? $subscription->next_renewal_at->format('d M Y') : 'N/A' }}</td>
                                <td>
                                    @if($subscription->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                    @elseif($subscription->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @elseif($subscription->status === 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                    @else
                                    <span class="badge bg-secondary">{{ ucfirst($subscription->status) }}</span>
                                    @endif
                                </td>
                                <td>MYR {{ number_format($subscription->plan->price ?? 0, 2) }}/mo</td>
                                <td>
                                    <div class="edit-delete-action">
                                        <a class="me-2 p-2" href="{{ route('superadmin.customers') }}?user_id={{ $subscription->user_id }}" title="View Customer">
                                            <i data-feather="eye" class="action-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">No subscriptions found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper mt-3">
                    {{ $subscriptions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

