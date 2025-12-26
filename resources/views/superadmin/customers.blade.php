@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <h3 class="page-title">Customers Management</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Customers</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <span class="badge badge-soft-primary p-3 rounded-circle">
                                    <i data-feather="users" style="width: 24px; height: 24px;"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1">Total Customers</h6>
                                <h3 class="mb-0">{{ $customers->total() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <span class="badge badge-soft-success p-3 rounded-circle">
                                    <i data-feather="check-circle" style="width: 24px; height: 24px;"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1">Active Plans</h6>
                                <h3 class="mb-0">{{ $customers->where('current_plan_id', '!=', null)->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <span class="badge badge-soft-warning p-3 rounded-circle">
                                    <i data-feather="store" style="width: 24px; height: 24px;"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1">Total Stores</h6>
                                <h3 class="mb-0">{{ $customers->sum(fn($c) => $c->ownedStores->count()) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <span class="badge badge-soft-info p-3 rounded-circle">
                                    <i data-feather="user-plus" style="width: 24px; height: 24px;"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-1">This Month</h6>
                                <h3 class="mb-0">{{ $customers->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customers Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">All Customers</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Company</th>
                                <th>Current Plan</th>
                                <th>Stores</th>
                                <th>Status</th>
                                <th>Joined Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-title rounded-circle bg-primary">
                                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $customer->name }}</h6>
                                            <p class="mb-0 text-muted small">{{ $customer->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($customer->company_name)
                                        <span>{{ $customer->company_name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($customer->currentPlan)
                                        <span class="badge badge-soft-primary">{{ $customer->currentPlan->name }}</span>
                                    @else
                                        <span class="badge badge-soft-secondary">No Plan</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $customer->ownedStores->count() }}</span>
                                </td>
                                <td>
                                    @if($customer->activeSubscription)
                                        {!! $customer->activeSubscription->getStatusBadge() !!}
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $customer->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('superadmin.impersonate', $customer->id) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Impersonate">
                                            <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-info" 
                                                onclick="viewCustomerDetails({{ $customer->id }})"
                                                title="Details">
                                            <i data-feather="info" style="width: 14px; height: 14px;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No customers found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewCustomerDetails(customerId) {
    // You can implement a modal or redirect to details page
    alert('View customer details: ' + customerId);
}
</script>

<style>
.badge-soft-primary {
    background-color: rgba(59, 130, 246, 0.1);
    color: rgb(59, 130, 246);
}

.badge-soft-success {
    background-color: rgba(16, 185, 129, 0.1);
    color: rgb(16, 185, 129);
}

.badge-soft-warning {
    background-color: rgba(245, 158, 11, 0.1);
    color: rgb(245, 158, 11);
}

.badge-soft-info {
    background-color: rgba(14, 165, 233, 0.1);
    color: rgb(14, 165, 233);
}

.badge-soft-secondary {
    background-color: rgba(107, 114, 128, 0.1);
    color: rgb(107, 114, 128);
}

.avatar-sm {
    width: 40px;
    height: 40px;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    color: white;
    font-weight: 600;
    font-size: 16px;
}
</style>
@endsection

