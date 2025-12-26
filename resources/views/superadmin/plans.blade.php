@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Plan Management</h4>
                <h6>Manage subscription plans and features</h6>
            </div>
        </div>

        <!-- Plans List -->
        <div class="row">
            @foreach($plans as $plan)
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h3>{{ $plan->name }}</h3>
                            <p class="text-muted">{{ $plan->description }}</p>
                            <h2 class="text-primary">MYR {{ number_format($plan->price, 0) }}<small>/month</small></h2>
                            <p class="text-muted">or MYR {{ number_format($plan->annual_price, 0) }}/year</p>
                            
                            @if($plan->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                            
                            <span class="badge bg-info">{{ $plan->subscriptions->where('status', 'active')->count() }} Active Subscribers</span>
                        </div>

                        <hr>

                        <h5 class="mb-3">Plan Limits</h5>
                        <ul class="list-unstyled plan-limits-list">
                            <li class="limit-item">
                                <i data-feather="home" class="limit-icon"></i>
                                <span class="limit-content">
                                    <strong>Stores:</strong> {{ $plan->max_stores === 'unlimited' || $plan->max_stores == -1 ? 'Unlimited' : $plan->max_stores }}
                                </span>
                            </li>
                            <li class="limit-item">
                                <i data-feather="shield" class="limit-icon"></i>
                                <span class="limit-content">
                                    <strong>QC Level:</strong> {{ ucfirst($plan->qc_level) }}
                                </span>
                            </li>
                            <li class="limit-item">
                                <i data-feather="list" class="limit-icon"></i>
                                <span class="limit-content">
                                    <strong>Audit Trail:</strong> {{ ucfirst($plan->audit_trail_level) }}
                                </span>
                            </li>
                            <li class="limit-item">
                                <i data-feather="calendar" class="limit-icon"></i>
                                <span class="limit-content">
                                    <strong>Trial Days:</strong> {{ $plan->trial_days }} days
                                </span>
                            </li>
                        </ul>

                        <hr>

                        <h5 class="mb-3">Capacity Limits</h5>
                        <ul class="list-unstyled capacity-list">
                            @php
                                $planFeatures = is_array($plan->features) ? $plan->features : json_decode($plan->features, true) ?? [];
                            @endphp
                            <li class="capacity-item">
                                <i data-feather="users" class="capacity-icon"></i>
                                <span class="capacity-content">
                                    <strong>Max Users:</strong> {{ isset($planFeatures['max_users']) ? ($planFeatures['max_users'] === 'unlimited' || $planFeatures['max_users'] == -1 ? 'Unlimited' : $planFeatures['max_users']) : 'N/A' }}
                                </span>
                            </li>
                            <li class="capacity-item">
                                <i data-feather="package" class="capacity-icon"></i>
                                <span class="capacity-content">
                                    <strong>Max Products:</strong> {{ isset($planFeatures['max_products']) ? ($planFeatures['max_products'] === 'unlimited' || $planFeatures['max_products'] == -1 ? 'Unlimited' : $planFeatures['max_products']) : 'N/A' }}
                                </span>
                            </li>
                            <li class="capacity-item">
                                <i data-feather="shopping-cart" class="capacity-icon"></i>
                                <span class="capacity-content">
                                    <strong>Max Orders/Month:</strong> {{ isset($planFeatures['max_orders_per_month']) ? ($planFeatures['max_orders_per_month'] === 'unlimited' || $planFeatures['max_orders_per_month'] == -1 ? 'Unlimited' : $planFeatures['max_orders_per_month']) : 'N/A' }}
                                </span>
                            </li>
                            <li class="capacity-item">
                                <i data-feather="headphones" class="capacity-icon"></i>
                                <span class="capacity-content">
                                    <strong>Support:</strong> {{ isset($planFeatures['customer_support']) ? ucwords(str_replace('_', ' ', $planFeatures['customer_support'])) : 'N/A' }}
                                </span>
                            </li>
                        </ul>

                        <hr>

                        <h5 class="mb-3">Features</h5>
                        <ul class="list-unstyled feature-list">
                            @php
                                $planFeatures = is_array($plan->features) ? $plan->features : json_decode($plan->features, true) ?? [];
                                
                                // Define ALL possible features with their labels
                                $allFeatures = [
                                    // Core Features
                                    'has_store_switcher' => ['label' => 'Store Switcher', 'type' => 'property'],
                                    'has_all_stores_view' => ['label' => 'All Stores View', 'type' => 'property'],
                                    
                                    // Laundry Operations
                                    'laundry_qc' => ['label' => 'Quality Control Module', 'type' => 'feature'],
                                    'machine_tracking' => ['label' => 'Machine Usage Tracking', 'type' => 'feature'],
                                    'pos_system' => ['label' => 'POS System', 'type' => 'feature'],
                                    
                                    // Advanced Features
                                    'advanced_reporting' => ['label' => 'Advanced Reporting', 'type' => 'feature'],
                                    'api_access' => ['label' => 'API Access', 'type' => 'feature'],
                                    'landing_page_module' => ['label' => 'Landing Page Module', 'type' => 'feature'],
                                    'theme_customization' => ['label' => 'Theme & CMS', 'type' => 'feature'],
                                ];
                            @endphp
                            
                            @foreach($allFeatures as $key => $config)
                                @php
                                    $isEnabled = false;
                                    
                                    if ($config['type'] === 'property') {
                                        // Check plan properties (has_store_switcher, has_all_stores_view, etc.)
                                        $isEnabled = $plan->{$key} ?? false;
                                    } else {
                                        // Check features array
                                        if ($key === 'laundry_qc') {
                                            // QC module is enabled if qc_level is not 'none'
                                            $isEnabled = in_array($plan->qc_level, ['basic', 'full', 'advanced']);
                                        } else {
                                            $isEnabled = isset($planFeatures[$key]) && $planFeatures[$key];
                                        }
                                    }
                                @endphp
                                
                                <li class="feature-item-display {{ $isEnabled ? 'feature-enabled' : 'feature-disabled' }}">
                                    @if($isEnabled)
                                        <i data-feather="check-circle" class="feature-icon feature-icon-enabled"></i>
                                    @else
                                        <i data-feather="x-circle" class="feature-icon feature-icon-disabled"></i>
                                    @endif
                                    <span class="feature-text">{{ $config['label'] }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-4">
                            <a href="{{ route('superadmin.plans.edit', $plan->id) }}" class="btn btn-primary btn-block">
                                <i data-feather="edit"></i> Edit Plan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($plans->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i data-feather="package" style="width: 60px; height: 60px;" class="text-muted mb-3"></i>
                <h5>No Plans Found</h5>
                <p class="text-muted">Create your first subscription plan to get started.</p>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
/* Feature List Styling */
.feature-list {
    margin: 0;
    padding: 0;
}

.feature-item-display {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0;
    margin-bottom: 0.25rem;
    transition: all 0.2s ease;
}

.feature-icon {
    flex-shrink: 0;
    width: 18px !important;
    height: 18px !important;
    stroke-width: 2.5;
}

.feature-icon-enabled {
    color: #28a745 !important;
    stroke: #28a745 !important;
}

.feature-icon-disabled {
    color: #dc3545 !important;
    stroke: #dc3545 !important;
}

.feature-text {
    font-size: 0.9rem;
    line-height: 1.4;
}

.feature-enabled .feature-text {
    color: #212529;
    font-weight: 500;
}

.feature-disabled .feature-text {
    color: #6c757d;
}

/* Plan Limits List Styling */
.plan-limits-list {
    margin: 0;
    padding: 0;
}

.limit-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0;
    margin-bottom: 0.25rem;
}

.limit-icon {
    flex-shrink: 0;
    width: 18px !important;
    height: 18px !important;
    color: #007bff;
    stroke: #007bff;
}

.limit-content {
    font-size: 0.9rem;
    line-height: 1.4;
}

/* Capacity List Styling */
.capacity-list {
    margin: 0;
    padding: 0;
}

.capacity-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0;
    margin-bottom: 0.25rem;
}

.capacity-icon {
    flex-shrink: 0;
    width: 18px !important;
    height: 18px !important;
    color: #17a2b8;
    stroke: #17a2b8;
}

.capacity-content {
    font-size: 0.9rem;
    line-height: 1.4;
}

/* Plan Card Styling */
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    margin-bottom: 1.5rem;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

/* Heading Styling */
h5 {
    font-size: 1rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 1rem;
}

/* Global Icon sizing */
[data-feather] {
    width: 18px !important;
    height: 18px !important;
}

/* Button Styling */
.btn-primary {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-primary [data-feather] {
    width: 16px !important;
    height: 16px !important;
}

/* Badge spacing */
.text-center .badge {
    margin: 0 0.25rem;
}
</style>
@endsection
