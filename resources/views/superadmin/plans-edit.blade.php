@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Edit Plan: {{ $plan->name }}</h4>
                <h6>Modify plan features and settings</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('superadmin.plans') }}" class="btn btn-secondary">
                    <i data-feather="arrow-left"></i> Back to Plans
                </a>
            </div>
        </div>

        <form action="{{ route('superadmin.plans.update', $plan->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-4">
                <!-- Basic Information -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Plan Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ $plan->name }}" required>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ $plan->description }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Monthly Price (MYR) <span class="text-danger">*</span></label>
                                        <input type="number" name="price" class="form-control" value="{{ $plan->price }}" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Annual Price (MYR)</label>
                                        <input type="number" name="annual_price" class="form-control" value="{{ $plan->annual_price }}" step="0.01">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select name="is_active" class="form-control">
                                    <option value="1" {{ $plan->is_active ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$plan->is_active ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Plan Limits -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Plan Limits</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Maximum Stores</label>
                                <div class="input-group">
                                    <input type="number" name="max_stores" class="form-control" 
                                           value="{{ $plan->max_stores != 'unlimited' && $plan->max_stores != -1 ? $plan->max_stores : 1 }}" 
                                           min="1" id="max_stores_input"
                                           {{ $plan->max_stores === 'unlimited' || $plan->max_stores == -1 ? 'disabled' : '' }}>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="unlimited_stores" value="1" id="unlimited_stores"
                                                   {{ $plan->max_stores === 'unlimited' || $plan->max_stores == -1 ? 'checked' : '' }}
                                                   onchange="toggleUnlimited('stores')">
                                            <label class="ms-2 mb-0" for="unlimited_stores">Unlimited</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>QC Level</label>
                                <select name="qc_level" class="form-control">
                                    <option value="basic" {{ $plan->qc_level === 'basic' ? 'selected' : '' }}>Basic</option>
                                    <option value="standard" {{ $plan->qc_level === 'standard' ? 'selected' : '' }}>Standard</option>
                                    <option value="advanced" {{ $plan->qc_level === 'advanced' ? 'selected' : '' }}>Advanced</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Audit Trail Level</label>
                                <select name="audit_trail_level" class="form-control">
                                    <option value="basic" {{ $plan->audit_trail_level === 'basic' ? 'selected' : '' }}>Basic</option>
                                    <option value="full" {{ $plan->audit_trail_level === 'full' ? 'selected' : '' }}>Full</option>
                                    <option value="none" {{ $plan->audit_trail_level === 'none' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Trial Days</label>
                                <input type="number" name="trial_days" class="form-control" value="{{ $plan->trial_days }}" min="0">
                            </div>

                            <div class="form-group">
                                <label>Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" value="{{ $plan->sort_order }}" min="0">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Plan Capacity Limits -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Capacity Limits</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Maximum Users per Store</label>
                                <div class="input-group">
                                    <input type="number" name="features[max_users]" class="form-control" 
                                           value="{{ isset($plan->features['max_users']) && $plan->features['max_users'] != 'unlimited' ? $plan->features['max_users'] : 5 }}" 
                                           min="1" id="max_users_input" 
                                           {{ isset($plan->features['max_users']) && $plan->features['max_users'] === 'unlimited' ? 'disabled' : '' }}>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="features[unlimited_users]" value="1" id="unlimited_users"
                                                   {{ isset($plan->features['max_users']) && $plan->features['max_users'] === 'unlimited' ? 'checked' : '' }}
                                                   onchange="toggleUnlimited('users')">
                                            <label class="ms-2 mb-0" for="unlimited_users">Unlimited</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Maximum Products per Store</label>
                                <div class="input-group">
                                    <input type="number" name="features[max_products]" class="form-control" 
                                           value="{{ isset($plan->features['max_products']) && $plan->features['max_products'] != 'unlimited' ? $plan->features['max_products'] : 100 }}" 
                                           min="1" id="max_products_input"
                                           {{ isset($plan->features['max_products']) && $plan->features['max_products'] === 'unlimited' ? 'disabled' : '' }}>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="features[unlimited_products]" value="1" id="unlimited_products"
                                                   {{ isset($plan->features['max_products']) && $plan->features['max_products'] === 'unlimited' ? 'checked' : '' }}
                                                   onchange="toggleUnlimited('products')">
                                            <label class="ms-2 mb-0" for="unlimited_products">Unlimited</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Maximum Orders per Month</label>
                                <div class="input-group">
                                    <input type="number" name="features[max_orders_per_month]" class="form-control" 
                                           value="{{ isset($plan->features['max_orders_per_month']) && $plan->features['max_orders_per_month'] != 'unlimited' ? $plan->features['max_orders_per_month'] : 500 }}" 
                                           min="1" id="max_orders_input"
                                           {{ isset($plan->features['max_orders_per_month']) && $plan->features['max_orders_per_month'] === 'unlimited' ? 'disabled' : '' }}>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="features[unlimited_orders]" value="1" id="unlimited_orders"
                                                   {{ isset($plan->features['max_orders_per_month']) && $plan->features['max_orders_per_month'] === 'unlimited' ? 'checked' : '' }}
                                                   onchange="toggleUnlimited('orders')">
                                            <label class="ms-2 mb-0" for="unlimited_orders">Unlimited</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Customer Support Level</label>
                                <select name="features[customer_support]" class="form-control">
                                    <option value="email" {{ isset($plan->features['customer_support']) && $plan->features['customer_support'] === 'email' ? 'selected' : '' }}>Email Support</option>
                                    <option value="priority_email" {{ isset($plan->features['customer_support']) && $plan->features['customer_support'] === 'priority_email' ? 'selected' : '' }}>Priority Email</option>
                                    <option value="phone_priority" {{ isset($plan->features['customer_support']) && $plan->features['customer_support'] === 'phone_priority' ? 'selected' : '' }}>Phone + Priority</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Feature Access Control</h5>
                    <p class="text-muted mb-0 small">Click to enable or disable features for this plan</p>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Core Features -->
                        <div class="col-lg-4">
                            <h6 class="feature-category-title">Core Features</h6>

                            <div class="feature-item" data-feature="store_switcher">
                                <input type="hidden" name="has_store_switcher" value="0">
                                <input class="feature-checkbox" type="checkbox" name="has_store_switcher" value="1" id="store_switcher" {{ $plan->has_store_switcher ? 'checked' : '' }}>
                                <label class="feature-label" for="store_switcher">
                                    <span class="feature-icon">
                                        <i class="icon-check" data-feather="check-circle"></i>
                                        <i class="icon-x" data-feather="x-circle"></i>
                                    </span>
                                    <span class="feature-content">
                                        <strong class="feature-name">Store Switcher</strong>
                                        <small class="feature-desc">Switch between multiple stores</small>
                                    </span>
                                </label>
                            </div>

                            <div class="feature-item" data-feature="all_stores_view">
                                <input type="hidden" name="has_all_stores_view" value="0">
                                <input class="feature-checkbox" type="checkbox" name="has_all_stores_view" value="1" id="all_stores_view" {{ $plan->has_all_stores_view ? 'checked' : '' }}>
                                <label class="feature-label" for="all_stores_view">
                                    <span class="feature-icon">
                                        <i class="icon-check" data-feather="check-circle"></i>
                                        <i class="icon-x" data-feather="x-circle"></i>
                                    </span>
                                    <span class="feature-content">
                                        <strong class="feature-name">All Stores View</strong>
                                        <small class="feature-desc">View data from all stores</small>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Laundry Features -->
                        <div class="col-lg-4">
                            <h6 class="feature-category-title">Laundry Operations</h6>
                            
                            <div class="feature-item" data-feature="laundry_qc">
                                <input type="hidden" name="features[laundry_qc]" value="0">
                                <input class="feature-checkbox" type="checkbox" name="features[laundry_qc]" value="1" id="laundry_qc" 
                                       {{ ((isset($plan->features['laundry_qc']) && $plan->features['laundry_qc']) || in_array($plan->qc_level, ['full', 'advanced'])) ? 'checked' : '' }}>
                                <label class="feature-label" for="laundry_qc">
                                    <span class="feature-icon">
                                        <i class="icon-check" data-feather="check-circle"></i>
                                        <i class="icon-x" data-feather="x-circle"></i>
                                    </span>
                                    <span class="feature-content">
                                        <strong class="feature-name">Quality Control Module</strong>
                                        <small class="feature-desc">Advanced QC workflows</small>
                                    </span>
                                </label>
                            </div>

                            <div class="feature-item" data-feature="machine_tracking">
                                <input type="hidden" name="features[machine_tracking]" value="0">
                                <input class="feature-checkbox" type="checkbox" name="features[machine_tracking]" value="1" id="machine_tracking" {{ (isset($plan->features['machine_tracking']) && $plan->features['machine_tracking']) ? 'checked' : '' }}>
                                <label class="feature-label" for="machine_tracking">
                                    <span class="feature-icon">
                                        <i class="icon-check" data-feather="check-circle"></i>
                                        <i class="icon-x" data-feather="x-circle"></i>
                                    </span>
                                    <span class="feature-content">
                                        <strong class="feature-name">Machine Usage Tracking</strong>
                                        <small class="feature-desc">Track machine usage and maintenance</small>
                                    </span>
                                </label>
                            </div>

                            <div class="feature-item feature-item-locked" data-feature="pos_system">
                                <input type="hidden" name="features[pos_system]" value="1">
                                <input class="feature-checkbox" type="checkbox" name="features[pos_system]" value="1" id="pos_system" checked disabled>
                                <label class="feature-label" for="pos_system">
                                    <span class="feature-icon">
                                        <i class="icon-check" data-feather="check-circle"></i>
                                    </span>
                                    <span class="feature-content">
                                        <strong class="feature-name">POS System</strong>
                                        <small class="feature-desc">Always enabled for all plans</small>
                                    </span>
                                    <span class="feature-badge">
                                        <span class="badge bg-success">Required</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Advanced Features -->
                        <div class="col-lg-4">
                            <h6 class="feature-category-title">Advanced Features</h6>
                            
                            <div class="feature-item" data-feature="advanced_reporting">
                                <input type="hidden" name="features[advanced_reporting]" value="0">
                                <input class="feature-checkbox" type="checkbox" name="features[advanced_reporting]" value="1" id="advanced_reporting" {{ (isset($plan->features['advanced_reporting']) && $plan->features['advanced_reporting']) ? 'checked' : '' }}>
                                <label class="feature-label" for="advanced_reporting">
                                    <span class="feature-icon">
                                        <i class="icon-check" data-feather="check-circle"></i>
                                        <i class="icon-x" data-feather="x-circle"></i>
                                    </span>
                                    <span class="feature-content">
                                        <strong class="feature-name">Advanced Reporting</strong>
                                        <small class="feature-desc">Detailed analytics and insights</small>
                                    </span>
                                </label>
                            </div>

                            <div class="feature-item" data-feature="api_access">
                                <input type="hidden" name="features[api_access]" value="0">
                                <input class="feature-checkbox" type="checkbox" name="features[api_access]" value="1" id="api_access" {{ (isset($plan->features['api_access']) && $plan->features['api_access']) ? 'checked' : '' }}>
                                <label class="feature-label" for="api_access">
                                    <span class="feature-icon">
                                        <i class="icon-check" data-feather="check-circle"></i>
                                        <i class="icon-x" data-feather="x-circle"></i>
                                    </span>
                                    <span class="feature-content">
                                        <strong class="feature-name">API Access</strong>
                                        <small class="feature-desc">RESTful API for integrations</small>
                                    </span>
                                </label>
                            </div>

                            <div class="feature-item" data-feature="landing_page_module">
                                <input type="hidden" name="features[landing_page_module]" value="0">
                                <input class="feature-checkbox" type="checkbox" name="features[landing_page_module]" value="1" id="landing_page_module" {{ (isset($plan->features['landing_page_module']) && $plan->features['landing_page_module']) ? 'checked' : '' }}>
                                <label class="feature-label" for="landing_page_module">
                                    <span class="feature-icon">
                                        <i class="icon-check" data-feather="check-circle"></i>
                                        <i class="icon-x" data-feather="x-circle"></i>
                                    </span>
                                    <span class="feature-content">
                                        <strong class="feature-name">Landing Page Module</strong>
                                        <small class="feature-desc">Custom storefront landing pages</small>
                                    </span>
                                </label>
                            </div>

                            <div class="feature-item" data-feature="theme_customization">
                                <input type="hidden" name="features[theme_customization]" value="0">
                                <input class="feature-checkbox" type="checkbox" name="features[theme_customization]" value="1" id="theme_customization" {{ (isset($plan->features['theme_customization']) && $plan->features['theme_customization']) ? 'checked' : '' }}>
                                <label class="feature-label" for="theme_customization">
                                    <span class="feature-icon">
                                        <i class="icon-check" data-feather="check-circle"></i>
                                        <i class="icon-x" data-feather="x-circle"></i>
                                    </span>
                                    <span class="feature-content">
                                        <strong class="feature-name">Theme & CMS</strong>
                                        <small class="feature-desc">Customize store theme and content</small>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="text-end">
                        <a href="{{ route('superadmin.plans') }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i data-feather="save"></i> Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
/* Input Group Styling */
.input-group {
    display: flex;
    gap: 1rem;
}

.input-group .form-control {
    flex: 1;
    max-width: 200px;
}

.input-group-append {
    display: flex;
    align-items: center;
}

.input-group-text {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background-color: transparent;
    border: none;
    padding: 0.375rem 0;
}

.input-group-text input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    margin: 0;
}

.input-group-text label {
    margin: 0;
    cursor: pointer;
    font-weight: 500;
    white-space: nowrap;
}

/* Better form spacing */
.card-body .form-group {
    margin-bottom: 1.5rem;
}

.card-body .form-group label {
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.card-body .form-control {
    padding: 0.5rem 0.75rem;
}

/* Card styling */
.card {
    border: 1px solid #e9ecef;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    margin-bottom: 1.5rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    padding: 1rem 1.25rem;
}

.card-header h5 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #495057;
}

.card-body {
    padding: 1.5rem 1.25rem;
}

/* Small text styling */
small.text-muted {
    font-size: 0.875rem;
    color: #6c757d;
    display: block;
    margin-top: 0.25rem;
}

/* Select styling */
.form-control, .form-select {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
}

.form-control:focus, .form-select:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Feature Items */
.feature-category-title {
    font-size: 1rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

.feature-item {
    position: relative;
    margin-bottom: 0.75rem;
    padding: 0.875rem 1rem;
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    background-color: #fff;
    transition: all 0.2s ease;
}

.feature-item:hover {
    border-color: #80bdff;
    box-shadow: 0 0.125rem 0.5rem rgba(0, 123, 255, 0.15);
    transform: translateY(-1px);
}

.feature-item.feature-item-locked {
    background-color: #f8f9fa;
    cursor: not-allowed;
}

.feature-item.feature-item-locked:hover {
    border-color: #e9ecef;
    box-shadow: none;
    transform: none;
}

.feature-checkbox {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.feature-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
    cursor: pointer;
    width: 100%;
}

.feature-item-locked .feature-label {
    cursor: not-allowed;
}

.feature-icon {
    flex-shrink: 0;
    width: 24px;
    height: 24px;
    position: relative;
}

.feature-icon i {
    position: absolute;
    top: 0;
    left: 0;
    width: 24px;
    height: 24px;
    transition: all 0.2s ease;
}

.feature-icon .icon-check {
    opacity: 0;
    color: #28a745;
    transform: scale(0.8);
}

.feature-icon .icon-x {
    opacity: 1;
    color: #dc3545;
    transform: scale(1);
}

/* Checked state */
.feature-checkbox:checked ~ .feature-label .feature-icon .icon-check {
    opacity: 1;
    transform: scale(1);
}

.feature-checkbox:checked ~ .feature-label .feature-icon .icon-x {
    opacity: 0;
    transform: scale(0.8);
}

/* Locked state (always checked) */
.feature-item-locked .feature-icon .icon-check {
    opacity: 1 !important;
    transform: scale(1) !important;
}

.feature-item-locked .feature-icon .icon-x {
    display: none;
}

.feature-content {
    display: flex;
    flex-direction: column;
    flex: 1;
    gap: 0.25rem;
}

.feature-name {
    font-size: 0.9375rem;
    font-weight: 600;
    color: #212529;
    line-height: 1.3;
}

.feature-desc {
    font-size: 0.8125rem;
    color: #6c757d;
    line-height: 1.4;
}

.feature-badge {
    margin-left: auto;
}

.feature-badge .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

/* Disabled state styling */
.feature-checkbox:not(:checked) ~ .feature-label .feature-name {
    color: #6c757d;
}

.feature-checkbox:not(:checked) ~ .feature-label .feature-desc {
    color: #adb5bd;
}

.feature-checkbox:not(:checked) ~ .feature-label {
    opacity: 0.7;
}

/* Checked state accent */
.feature-checkbox:checked ~ .feature-label {
    opacity: 1;
}

.feature-checkbox:checked + .feature-label:before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, #28a745 0%, #20c997 100%);
    border-radius: 0.5rem 0 0 0.5rem;
}
</style>

<script>
function toggleUnlimited(type) {
    const checkbox = document.getElementById('unlimited_' + type);
    const input = document.getElementById('max_' + type + '_input');
    
    if (checkbox.checked) {
        input.disabled = true;
        input.value = '';
    } else {
        input.disabled = false;
        // Set default values
        if (type === 'stores') input.value = 1;
        if (type === 'users') input.value = 5;
        if (type === 'products') input.value = 100;
        if (type === 'orders') input.value = 500;
    }
}

// Initialize feather icons
document.addEventListener('DOMContentLoaded', function() {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endsection

