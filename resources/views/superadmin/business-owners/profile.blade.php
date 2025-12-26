@extends('layout.mainlayout')

@section('content')
<style>
.feather-sm {
    width: 16px;
    height: 16px;
    vertical-align: middle;
}
.badge-lg {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}
.bg-success-light {
    background-color: #d4edda;
}
.bg-info-light {
    background-color: #d1ecf1;
}
.bg-danger-light {
    background-color: #f8d7da;
}
.bg-warning-light {
    background-color: #fff3cd;
}
</style>
<div class="page-wrapper">
    <div class="content">
        <!-- Breadcrumb -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">
                        <a href="{{ route('superadmin.subscriptions') }}" class="text-primary me-2">
                            <i data-feather="arrow-left"></i>
                        </a>
                        {{ $owner->name }}
                    </h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('superadmin.subscriptions') }}">Subscriptions</a></li>
                        <li class="breadcrumb-item active">Business Owner Profile</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Alerts -->
        @if(count($alerts) > 0)
        <div class="row mb-3">
            <div class="col-12">
                @foreach($alerts as $alert)
                <div class="alert alert-{{ $alert['type'] }} alert-dismissible fade show" role="alert">
                    <i data-feather="{{ $alert['icon'] }}"></i>
                    <strong>Alert:</strong> {{ $alert['message'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Business Overview Card -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar avatar-xxl bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 32px;">
                                    {{ strtoupper(substr($owner->name, 0, 2)) }}
                                </div>
                            </div>
                            <div class="col">
                                <h3 class="mb-1">{{ $owner->name }}</h3>
                                <p class="text-muted mb-2"><i data-feather="mail" class="feather-sm me-1"></i> {{ $owner->email }}</p>
                                <p class="text-muted mb-0"><i data-feather="phone" class="feather-sm me-1"></i> {{ $owner->phone ?? 'N/A' }}</p>
                            </div>
                            <div class="col-auto text-end">
                                <div class="mb-2">
                                    @if($owner->currentPlan)
                                        <span class="badge badge-lg bg-{{ $owner->currentPlan->slug == 'free' ? 'secondary' : ($owner->currentPlan->slug == 'pro' ? 'success' : 'primary') }}">
                                            {{ $owner->currentPlan->name }} Plan
                                        </span>
                                    @else
                                        <span class="badge badge-lg bg-secondary">No Plan</span>
                                    @endif
                                </div>
                                <div class="mb-2">
                                    @if($currentSubscription)
                                        @php
                                            $calcStatus = $currentSubscription->calculateStatus();
                                        @endphp
                                        @if($calcStatus === 'active')
                                            <span class="badge bg-success-light text-success">● Active</span>
                                        @elseif($calcStatus === 'trial')
                                            <span class="badge bg-info-light text-info">● Trial</span>
                                        @elseif($calcStatus === 'grace')
                                            <span class="badge bg-warning-light text-warning">● Grace Period</span>
                                        @else
                                            <span class="badge bg-danger-light text-danger">● {{ ucfirst($calcStatus) }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">No Subscription</span>
                                    @endif
                                </div>
                                @if($owner->currentPlan && $currentSubscription)
                                <small class="text-muted">
                                    MYR {{ number_format($currentSubscription->amount, 2) }}/{{ $currentSubscription->billing_cycle === 'annual' ? 'year' : 'month' }}
                                </small>
                                @endif
                            </div>
                        </div>
                        
                        <hr class="my-3">
                        
                        <div class="row text-center">
                            <div class="col-md-3">
                                <p class="text-muted mb-1 small">Company</p>
                                <h6 class="mb-0">{{ $owner->company_name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted mb-1 small">Member Since</p>
                                <h6 class="mb-0">{{ $owner->created_at->format('d M Y') }}</h6>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted mb-1 small">Last Activity</p>
                                <h6 class="mb-0">{{ $accountHealth['days_since_login'] }} days ago</h6>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted mb-1 small">Health Score</p>
                                <h6 class="mb-0">
                                    <span class="badge bg-{{ $accountHealth['score'] >= 80 ? 'success' : ($accountHealth['score'] >= 50 ? 'warning' : 'danger') }}">
                                        {{ $accountHealth['score'] }}%
                                    </span>
                                    @if($accountHealth['score'] >= 80)
                                        Healthy
                                    @elseif($accountHealth['score'] >= 50)
                                        At Risk
                                    @else
                                        Inactive
                                    @endif
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Actions Bar -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-light border-0">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="mb-0"><i data-feather="settings" class="feather-sm"></i> Admin Actions</h6>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#assignPlanModal">
                                    <i data-feather="package" class="feather-sm"></i> Assign Plan
                                </button>
                                <button class="btn btn-sm btn-info me-2" onclick="alert('Extend Trial feature - Coming Soon')">
                                    <i data-feather="clock" class="feather-sm"></i> Extend Trial
                                </button>
                                <button class="btn btn-sm btn-warning me-2" onclick="if(confirm('Are you sure you want to pause this subscription?')) { alert('Pause feature - Coming Soon'); }">
                                    <i data-feather="pause" class="feather-sm"></i> Pause
                                </button>
                                <button class="btn btn-sm btn-danger me-2" onclick="if(confirm('Are you sure you want to lock this account?')) { alert('Lock Account feature - Coming Soon'); }">
                                    <i data-feather="lock" class="feather-sm"></i> Lock
                                </button>
                                <button class="btn btn-sm btn-secondary" onclick="alert('Impersonate feature - Coming Soon')">
                                    <i data-feather="eye" class="feather-sm"></i> Impersonate
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content Column -->
            <div class="col-lg-8">

                <!-- Billing Health Widget -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-4"><i data-feather="credit-card" class="feather-sm"></i> Billing Health</h5>
                        
                        <div class="row g-3">
                            <div class="col-sm-6 col-xl-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <p class="text-muted mb-2 small">Last Payment</p>
                                    @if($billingHealth['last_payment'])
                                        <h5 class="mb-1">MYR {{ number_format($billingHealth['last_payment']->amount, 2) }}</h5>
                                        <small class="text-success">{{ $billingHealth['last_payment']->paid_at->format('d M Y') }}</small>
                                    @else
                                        <p class="text-muted mb-0">No payments yet</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-sm-6 col-xl-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <p class="text-muted mb-2 small">Failed Attempts</p>
                                    <h5 class="mb-0 text-{{ $billingHealth['failed_attempts'] > 0 ? 'danger' : 'success' }}">
                                        {{ $billingHealth['failed_attempts'] }}
                                    </h5>
                                    <small class="text-muted">Last 30 days</small>
                                </div>
                            </div>
                            
                            <div class="col-sm-6 col-xl-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <p class="text-muted mb-2 small">Days Past Due</p>
                                    <h5 class="mb-0 text-{{ $billingHealth['days_past_due'] > 0 ? 'danger' : 'success' }}">
                                        {{ $billingHealth['days_past_due'] }}
                                    </h5>
                                    <small class="text-muted">days</small>
                                </div>
                            </div>
                            
                            <div class="col-sm-6 col-xl-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <p class="text-muted mb-2 small">Total Revenue</p>
                                    <h5 class="mb-0 text-success">MYR {{ number_format($billingHealth['total_paid'], 2) }}</h5>
                                    <small class="text-muted">all time</small>
                                </div>
                            </div>
                        </div>
                        
                        @if($billingHealth['pending_amount'] > 0)
                        <div class="alert alert-warning mt-3 mb-0">
                            <i data-feather="alert-triangle"></i> <strong>Pending Payment:</strong> MYR {{ number_format($billingHealth['pending_amount'], 2) }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Payment History -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-4"><i data-feather="dollar-sign" class="feather-sm"></i> Payment History</h5>
                        
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Invoice ID</th>
                                        <th>Plan</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($paymentHistory as $payment)
                                    <tr>
                                        <td><strong>#{{ $payment->id }}</strong></td>
                                        <td>{{ $payment->subscription->plan->name }}</td>
                                        <td>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $payment->payment_method ? ucfirst($payment->payment_method) : 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($payment->status === 'completed')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($payment->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($payment->status === 'failed')
                                                <span class="badge bg-danger">Failed</span>
                                            @else
                                                <span class="badge bg-info">{{ ucfirst($payment->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $payment->paid_at ? $payment->paid_at->format('d M Y') : $payment->created_at->format('d M Y') }}</td>
                                        <td>
                                            @if($payment->status === 'failed')
                                                <form action="{{ route('superadmin.payments.retry', $payment->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning" title="Retry Payment">
                                                        <i data-feather="refresh-cw" style="width: 14px; height: 14px;"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if($payment->status === 'pending')
                                                <form action="{{ route('superadmin.payments.mark-paid', $payment->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Mark as Paid">
                                                        <i data-feather="check" style="width: 14px; height: 14px;"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <button class="btn btn-sm btn-info" title="Download Invoice">
                                                <i data-feather="download" style="width: 14px; height: 14px;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No payment history</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- User & Role Summary -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-4"><i data-feather="users" class="feather-sm"></i> Users & Roles</h5>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <div class="text-center p-2 bg-light rounded">
                                    <h4 class="text-primary mb-0">{{ $userMetrics['total_users'] }}</h4>
                                    <p class="text-muted small mb-0">Total</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center p-2 bg-light rounded">
                                    <h4 class="text-success mb-0">{{ $userMetrics['active_users'] }}</h4>
                                    <p class="text-muted small mb-0">Active</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center p-2 bg-light rounded">
                                    <h4 class="text-danger mb-0">{{ $userMetrics['inactive_users'] }}</h4>
                                    <p class="text-muted small mb-0">Inactive</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Roles</th>
                                        <th>Stores</th>
                                        <th>Last Login</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($userSummary as $userInfo)
                                    <tr>
                                        <td>
                                            <strong>{{ $userInfo['user']->name }}</strong><br>
                                            <small class="text-muted">{{ $userInfo['user']->email }}</small>
                                        </td>
                                        <td>
                                            @foreach($userInfo['roles'] as $role)
                                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $role)) }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ count($userInfo['stores']) }} store(s)</span>
                                        </td>
                                        <td>
                                            {{ $userInfo['last_login']->format('d M Y') }}<br>
                                            <small class="text-muted">{{ $userInfo['last_login']->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            @if($userInfo['is_active'])
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No users found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Store Performance -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-4"><i data-feather="shopping-bag" class="feather-sm"></i> Store Performance (Last 30 Days)</h5>
                        
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Store Name</th>
                                        <th>Status</th>
                                        <th>Orders</th>
                                        <th>Revenue</th>
                                        <th>QC Pending</th>
                                        <th>Users</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($storeStats as $store)
                                    <tr>
                                        <td><strong>{{ $store['name'] }}</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $store['is_active'] ? 'success' : 'secondary' }}">
                                                {{ $store['is_active'] ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>{{ $store['orders_30d'] }}</td>
                                        <td>MYR {{ number_format($store['revenue_30d'], 2) }}</td>
                                        <td>
                                            @if($store['qc_pending'] > 0)
                                                <span class="badge bg-warning">{{ $store['qc_pending'] }}</span>
                                            @else
                                                <span class="text-muted">0</span>
                                            @endif
                                        </td>
                                        <td>{{ $store['users_count'] }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No stores found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sidebar Column -->
            <div class="col-lg-4">
                <!-- Usage & Limits -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-4"><i data-feather="bar-chart-2" class="feather-sm"></i> Usage & Limits</h5>
                        
                        @php
                            $storePercentage = $usage['stores']['allowed'] > 0 ? ($usage['stores']['used'] / $usage['stores']['allowed']) * 100 : 0;
                            $userPercentage = $usage['users']['allowed'] > 0 ? ($usage['users']['used'] / $usage['users']['allowed']) * 100 : 0;
                            $orderPercentage = $usage['orders_allowed'] > 0 ? ($usage['orders_this_month'] / $usage['orders_allowed']) * 100 : 0;
                        @endphp
                        
                        <!-- Stores -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Stores</span>
                                <span><strong>{{ $usage['stores']['used'] }}</strong> / {{ $usage['stores']['allowed'] }}</span>
                            </div>
                            <div class="progress mb-1" style="height: 8px;">
                                <div class="progress-bar bg-{{ $storePercentage >= 100 ? 'danger' : ($storePercentage >= 80 ? 'warning' : 'primary') }}" 
                                     style="width: {{ min(100, $storePercentage) }}%">
                                </div>
                            </div>
                            <small class="text-{{ $storePercentage >= 80 ? 'danger' : 'muted' }}">{{ number_format($storePercentage, 0) }}% used</small>
                        </div>

                        <!-- Users -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Users</span>
                                <span><strong>{{ $usage['users']['used'] }}</strong> / {{ $usage['users']['allowed'] }}</span>
                            </div>
                            <div class="progress mb-1" style="height: 8px;">
                                <div class="progress-bar bg-{{ $userPercentage >= 100 ? 'danger' : ($userPercentage >= 80 ? 'warning' : 'success') }}" 
                                     style="width: {{ min(100, $userPercentage) }}%">
                                </div>
                            </div>
                            <small class="text-{{ $userPercentage >= 80 ? 'danger' : 'muted' }}">{{ number_format($userPercentage, 0) }}% used</small>
                        </div>

                        <!-- Orders -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Orders (This Month)</span>
                                <span><strong>{{ $usage['orders_this_month'] }}</strong> / {{ $usage['orders_allowed'] }}</span>
                            </div>
                            <div class="progress mb-1" style="height: 8px;">
                                <div class="progress-bar bg-{{ $orderPercentage >= 100 ? 'danger' : ($orderPercentage >= 80 ? 'warning' : 'info') }}" 
                                     style="width: {{ min(100, $orderPercentage) }}%">
                                </div>
                            </div>
                            <small class="text-{{ $orderPercentage >= 80 ? 'danger' : 'muted' }}">{{ number_format($orderPercentage, 0) }}% used</small>
                        </div>

                        @if($storePercentage >= 80 || $userPercentage >= 80 || $orderPercentage >= 80)
                        <div class="alert alert-warning mb-0">
                            <i data-feather="trending-up" class="feather-sm"></i> <strong>Upsell Opportunity!</strong> Approaching plan limits.
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Current Subscription Details -->
                @if($currentSubscription)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><i data-feather="calendar" class="feather-sm"></i> Current Subscription</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1 small">📅 Start Date</p>
                                <h6>{{ \Carbon\Carbon::parse($currentSubscription->starts_at)->format('d M Y, H:i') }}</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1 small">📅 End Date</p>
                                <h6>{{ \Carbon\Carbon::parse($currentSubscription->ends_at)->format('d M Y, H:i') }}</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1 small">🔄 Next Renewal</p>
                                <h6>{{ $currentSubscription->next_billing_date ? \Carbon\Carbon::parse($currentSubscription->next_billing_date)->format('d M Y') : 'N/A' }}</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1 small">⏱️ Days Remaining</p>
                                <h6>
                                    @if($currentSubscription->calculateStatus() === 'active')
                                        <span class="text-success">{{ $currentSubscription->daysRemaining() }} days</span>
                                    @elseif($currentSubscription->calculateStatus() === 'grace')
                                        <span class="text-danger">Grace: {{ $currentSubscription->graceDaysRemaining() }} days</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </h6>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <p class="text-muted mb-1 small">Billing Cycle</p>
                                <h6>{{ ucfirst($currentSubscription->billing_cycle) }}</h6>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1 small">Amount</p>
                                <h6>MYR {{ number_format($currentSubscription->amount, 2) }}</h6>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1 small">Status</p>
                                <h6>
                                    @php $calcStatus = $currentSubscription->calculateStatus(); @endphp
                                    <span class="badge bg-{{ $calcStatus === 'active' ? 'success' : ($calcStatus === 'grace' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($calcStatus) }}
                                    </span>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Subscription History -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4"><i data-feather="clock" class="feather-sm"></i> Subscription History</h5>
                        
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Plan</th>
                                        <th>Period</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($subscriptionHistory as $subscription)
                                    <tr>
                                        <td><span class="badge bg-primary">{{ $subscription->plan->name }}</span></td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($subscription->starts_at)->format('d M Y') }} - 
                                            {{ \Carbon\Carbon::parse($subscription->ends_at)->format('d M Y') }}
                                        </td>
                                        <td>
                                            @if($subscription->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($subscription->status === 'trial')
                                                <span class="badge bg-info">Trial</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($subscription->status) }}</span>
                                            @endif
                                        </td>
                                        <td>MYR {{ number_format($subscription->amount, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No subscription history</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Plan Modal -->
<div class="modal fade" id="assignPlanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ url('superadmin/subscriptions/' . $owner->id . '/assign-plan') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Assign Plan to {{ $owner->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>📅 Subscription Dates:</strong><br>
                        <small>
                            • <strong>Start Date:</strong> Today ({{ now()->format('d M Y') }})<br>
                            • <strong>End Date:</strong> Calculated automatically based on billing cycle<br>
                            • <strong>Next Renewal:</strong> End Date + 1 day<br>
                            • <strong>Status:</strong> Will be set to "Active"
                        </small>
                    </div>

                    <div class="form-group mb-3">
                        <label>Select Plan <span class="text-danger">*</span></label>
                        <select name="plan_id" id="plan_select" class="form-control" required>
                            <option value="">Choose a plan...</option>
                            @php
                                $allPlans = \App\Models\Plan::where('is_active', true)->orderBy('sort_order')->get();
                            @endphp
                            @foreach($allPlans as $plan)
                            <option value="{{ $plan->id }}" 
                                data-monthly="{{ $plan->price }}" 
                                data-annual="{{ $plan->annual_price }}"
                                {{ $owner->currentPlan && $owner->currentPlan->id == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Billing Cycle <span class="text-danger">*</span></label>
                        <select name="billing_cycle" id="billing_cycle" class="form-control" required>
                            <option value="monthly">Monthly</option>
                            <option value="annual">Annual (Save ~17%)</option>
                        </select>
                    </div>

                    <div id="subscription_preview" class="alert alert-success" style="display: none;">
                        <h6>📋 Subscription Preview:</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td><strong>Amount:</strong></td>
                                <td id="preview_amount">-</td>
                            </tr>
                            <tr>
                                <td><strong>Start Date:</strong></td>
                                <td>{{ now()->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>End Date:</strong></td>
                                <td id="preview_end_date">-</td>
                            </tr>
                            <tr>
                                <td><strong>Next Renewal:</strong></td>
                                <td id="preview_renewal_date">-</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    // Re-initialize Feather icons after any dynamic content changes
    setTimeout(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }, 100);
});

// Also initialize on window load
window.addEventListener('load', function() {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});

// Re-initialize Feather icons when modal is shown
var assignPlanModal = document.getElementById('assignPlanModal');
if (assignPlanModal) {
    assignPlanModal.addEventListener('shown.bs.modal', function () {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
}

// Calculate and preview subscription dates
function updateSubscriptionPreview() {
    var planSelect = document.getElementById('plan_select');
    var billingCycleSelect = document.getElementById('billing_cycle');
    var previewDiv = document.getElementById('subscription_preview');
    
    if (!planSelect.value) {
        previewDiv.style.display = 'none';
        return;
    }
    
    var selectedOption = planSelect.options[planSelect.selectedIndex];
    var billingCycle = billingCycleSelect.value;
    
    var monthlyPrice = parseFloat(selectedOption.getAttribute('data-monthly'));
    var annualPrice = parseFloat(selectedOption.getAttribute('data-annual'));
    
    var amount = billingCycle === 'annual' ? annualPrice : monthlyPrice;
    var cycleText = billingCycle === 'annual' ? '/year' : '/month';
    
    // Calculate dates
    var startDate = new Date();
    var endDate = new Date(startDate);
    
    if (billingCycle === 'annual') {
        endDate.setFullYear(endDate.getFullYear() + 1);
    } else {
        endDate.setMonth(endDate.getMonth() + 1);
    }
    endDate.setDate(endDate.getDate() - 1); // Minus 1 day
    
    var renewalDate = new Date(endDate);
    renewalDate.setDate(renewalDate.getDate() + 1); // Plus 1 day
    
    // Format dates
    var options = { day: 'numeric', month: 'short', year: 'numeric' };
    var endDateStr = endDate.toLocaleDateString('en-GB', options);
    var renewalDateStr = renewalDate.toLocaleDateString('en-GB', options);
    
    // Update preview
    document.getElementById('preview_amount').textContent = 'MYR ' + amount.toFixed(2) + cycleText;
    document.getElementById('preview_end_date').textContent = endDateStr;
    document.getElementById('preview_renewal_date').textContent = renewalDateStr;
    
    previewDiv.style.display = 'block';
}

// Add event listeners
if (document.getElementById('plan_select')) {
    document.getElementById('plan_select').addEventListener('change', updateSubscriptionPreview);
    document.getElementById('billing_cycle').addEventListener('change', updateSubscriptionPreview);
}
</script>
@endsection

