@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">My Subscription</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Subscription</li>
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

        <!-- Current Subscription -->
        @if($currentSubscription)
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title mb-3">Current Plan: {{ $currentSubscription->plan->name }}</h4>
                                @php $status = $currentSubscription->calculateStatus(); @endphp
                                <span class="badge badge-lg bg-{{ $status === 'active' ? 'success' : ($status === 'grace' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </div>
                            <div class="col-auto">
                                <h2 class="mb-0">MYR {{ number_format($currentSubscription->amount, 2) }}</h2>
                                <p class="text-muted mb-0">/{{ $currentSubscription->billing_cycle === 'annual' ? 'year' : 'month' }}</p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1 small">Start Date</p>
                                <h6>{{ \Carbon\Carbon::parse($currentSubscription->starts_at)->format('d M Y') }}</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1 small">End Date</p>
                                <h6>{{ \Carbon\Carbon::parse($currentSubscription->ends_at)->format('d M Y') }}</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1 small">Next Renewal</p>
                                <h6>{{ $currentSubscription->next_billing_date ? \Carbon\Carbon::parse($currentSubscription->next_billing_date)->format('d M Y') : 'N/A' }}</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1 small">Days Remaining</p>
                                <h6>
                                    @if($status === 'active')
                                        <span class="text-success">{{ $currentSubscription->daysRemaining() }} days</span>
                                    @elseif($status === 'grace')
                                        <span class="text-danger">Grace: {{ $currentSubscription->graceDaysRemaining() }} days</span>
                                    @else
                                        <span class="text-muted">Expired</span>
                                    @endif
                                </h6>
                            </div>
                        </div>

                        @if($status === 'grace')
                        <div class="alert alert-danger">
                            <i data-feather="alert-triangle"></i> <strong>Payment Required!</strong> Your subscription is in grace period. Please make payment to continue service.
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Usage & Limits -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Usage & Limits</h5>
                        
                        @php
                            $storesUsed = (int)$usage['stores']['used'];
                            $storesAllowed = (int)$usage['stores']['allowed'];
                            $usersUsed = (int)$usage['users']['used'];
                            $usersAllowed = (int)$usage['users']['allowed'];
                            $ordersThisMonth = (int)$usage['orders_this_month'];
                            $ordersAllowed = (int)$usage['orders_allowed'];
                            
                            $storePercentage = $storesAllowed > 0 ? ($storesUsed / $storesAllowed) * 100 : 0;
                            $userPercentage = $usersAllowed > 0 ? ($usersUsed / $usersAllowed) * 100 : 0;
                            $orderPercentage = $ordersAllowed > 0 ? ($ordersThisMonth / $ordersAllowed) * 100 : 0;
                        @endphp
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Stores</span>
                                <span><strong>{{ $storesUsed }}</strong> / {{ $storesAllowed }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ $storePercentage >= 100 ? 'danger' : ($storePercentage >= 80 ? 'warning' : 'primary') }}" 
                                     style="width: {{ min(100, $storePercentage) }}%"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Users</span>
                                <span><strong>{{ $usersUsed }}</strong> / {{ $usersAllowed }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ $userPercentage >= 100 ? 'danger' : ($userPercentage >= 80 ? 'warning' : 'success') }}" 
                                     style="width: {{ min(100, $userPercentage) }}%"></div>
                            </div>
                        </div>

                        <div class="mb-0">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Orders This Month</span>
                                <span><strong>{{ $ordersThisMonth }}</strong> / {{ $ordersAllowed }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ $orderPercentage >= 100 ? 'danger' : ($orderPercentage >= 80 ? 'warning' : 'info') }}" 
                                     style="width: {{ min(100, $orderPercentage) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Upgrade Plans -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Available Plans</h5>
                        
                        @foreach($availablePlans as $plan)
                        <div class="mb-3 p-3 border rounded {{ $currentSubscription->plan_id == $plan->id ? 'bg-light' : '' }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1">{{ $plan->name }}</h6>
                                    <p class="text-muted small mb-0">{{ $plan->description }}</p>
                                </div>
                                @if($currentSubscription->plan_id == $plan->id)
                                <span class="badge bg-success">Current</span>
                                @endif
                            </div>
                            <h5 class="mb-2">MYR {{ number_format($plan->price, 2) }}<small class="text-muted">/mo</small></h5>
                            
                            @if($currentSubscription->plan_id != $plan->id)
                            <a href="{{ route('business-owner.checkout', ['plan_id' => $plan->id]) }}" class="btn btn-sm btn-primary w-100">
                                @if($plan->price > $currentSubscription->plan->price)
                                    Upgrade to {{ $plan->name }}
                                @else
                                    Switch to {{ $plan->name }}
                                @endif
                            </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- No Active Subscription -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i data-feather="alert-circle" class="mb-3" style="width: 48px; height: 48px;"></i>
                        <h4>No Active Subscription</h4>
                        <p class="text-muted">Choose a plan to get started</p>
                        <a href="#plans" class="btn btn-primary">View Plans</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Show Available Plans -->
        <div class="row" id="plans">
            @foreach($availablePlans as $plan)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">{{ $plan->name }}</h4>
                        <h2 class="mb-3">MYR {{ number_format($plan->price, 2) }}<small class="text-muted">/mo</small></h2>
                        <p class="text-muted">{{ $plan->description }}</p>
                        <a href="{{ route('business-owner.checkout', ['plan_id' => $plan->id]) }}" class="btn btn-primary w-100">
                            Choose Plan
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Payment History -->
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title mb-3">Payment History</h5>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paymentHistory as $payment)
                            <tr>
                                <td><code>{{ $payment->transaction_id }}</code></td>
                                <td>{{ $payment->subscription->plan->name }}</td>
                                <td>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</td>
                                <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span></td>
                                <td>
                                    <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'failed' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td>{{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : $payment->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No payment history</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $paymentHistory->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

