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
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i data-feather="check-circle" class="text-success"></i>
                                <strong>Stores:</strong> {{ $plan->max_stores === 'unlimited' || $plan->max_stores == -1 ? 'Unlimited' : $plan->max_stores }}
                            </li>
                            <li class="mb-2">
                                <i data-feather="check-circle" class="text-success"></i>
                                <strong>QC Level:</strong> {{ ucfirst($plan->qc_level) }}
                            </li>
                            <li class="mb-2">
                                <i data-feather="check-circle" class="text-success"></i>
                                <strong>Audit Trail:</strong> {{ ucfirst($plan->audit_trail_level) }}
                            </li>
                            <li class="mb-2">
                                <i data-feather="check-circle" class="text-success"></i>
                                <strong>Trial Days:</strong> {{ $plan->trial_days }} days
                            </li>
                        </ul>

                        <hr>

                        <h5 class="mb-3">Capacity Limits</h5>
                        <ul class="list-unstyled">
                            @php
                                $planFeatures = is_array($plan->features) ? $plan->features : json_decode($plan->features, true) ?? [];
                            @endphp
                            <li class="mb-2">
                                <i data-feather="users" class="text-info"></i>
                                <strong>Max Users:</strong> {{ isset($planFeatures['max_users']) ? ($planFeatures['max_users'] === 'unlimited' || $planFeatures['max_users'] == -1 ? 'Unlimited' : $planFeatures['max_users']) : 'N/A' }}
                            </li>
                            <li class="mb-2">
                                <i data-feather="package" class="text-info"></i>
                                <strong>Max Products:</strong> {{ isset($planFeatures['max_products']) ? ($planFeatures['max_products'] === 'unlimited' || $planFeatures['max_products'] == -1 ? 'Unlimited' : $planFeatures['max_products']) : 'N/A' }}
                            </li>
                            <li class="mb-2">
                                <i data-feather="shopping-cart" class="text-info"></i>
                                <strong>Max Orders/Month:</strong> {{ isset($planFeatures['max_orders_per_month']) ? ($planFeatures['max_orders_per_month'] === 'unlimited' || $planFeatures['max_orders_per_month'] == -1 ? 'Unlimited' : $planFeatures['max_orders_per_month']) : 'N/A' }}
                            </li>
                            <li class="mb-2">
                                <i data-feather="message-circle" class="text-info"></i>
                                <strong>Support:</strong> {{ isset($planFeatures['customer_support']) ? ucwords(str_replace('_', ' ', $planFeatures['customer_support'])) : 'N/A' }}
                            </li>
                        </ul>

                        <hr>

                        <h5 class="mb-3">Features</h5>
                        <ul class="list-unstyled">
                            @php
                                $planFeatures = is_array($plan->features) ? $plan->features : json_decode($plan->features, true) ?? [];
                            @endphp
                            
                            <li class="mb-2">
                                @if($plan->has_sop_module)
                                    <i data-feather="check-circle" class="text-success"></i>
                                @else
                                    <i data-feather="x-circle" class="text-danger"></i>
                                @endif
                                SOP Module
                            </li>
                            
                            <li class="mb-2">
                                @if($plan->has_store_switcher)
                                    <i data-feather="check-circle" class="text-success"></i>
                                @else
                                    <i data-feather="x-circle" class="text-danger"></i>
                                @endif
                                Store Switcher
                            </li>
                            
                            <li class="mb-2">
                                @if($plan->has_all_stores_view)
                                    <i data-feather="check-circle" class="text-success"></i>
                                @else
                                    <i data-feather="x-circle" class="text-danger"></i>
                                @endif
                                All Stores View
                            </li>

                            @php
                                // Define feature labels for better display
                                $featureLabels = [
                                    'laundry_qc' => 'Quality Control Module',
                                    'machine_tracking' => 'Machine Usage Tracking',
                                    'pos_system' => 'POS System',
                                    'advanced_reporting' => 'Advanced Reporting',
                                    'api_access' => 'API Access',
                                    'landing_page_module' => 'Landing Page Module',
                                    'theme_customization' => 'Theme & CMS',
                                    'max_users' => null, // Don't show these as features
                                    'max_products' => null,
                                    'max_orders_per_month' => null,
                                    'customer_support' => null,
                                    'custom_branding' => null, // Hidden
                                    'dedicated_account_manager' => null, // Hidden
                                ];
                            @endphp

                            @if(!empty($planFeatures))
                                @foreach($planFeatures as $key => $value)
                                    @if($value && is_bool($value) && isset($featureLabels[$key]) && $featureLabels[$key] !== null)
                                        <li class="mb-2">
                                            <i data-feather="check-circle" class="text-success"></i>
                                            {{ $featureLabels[$key] }}
                                        </li>
                                    @elseif($value && !is_bool($value) && !in_array($key, ['max_users', 'max_products', 'max_orders_per_month', 'customer_support']))
                                        <li class="mb-2">
                                            <i data-feather="check-circle" class="text-success"></i>
                                            {{ $featureLabels[$key] ?? ucwords(str_replace('_', ' ', $key)) }}
                                        </li>
                                    @endif
                                @endforeach
                            @endif
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
@endsection
