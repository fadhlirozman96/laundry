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

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Validation Error!</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Stats -->
        <div class="row">
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-primary">
                                <i data-feather="users"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Total Owners</h6>
                            <h3>{{ $stats['total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-success">
                                <i data-feather="check-circle"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Active</h6>
                            <h3>{{ $stats['active'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-info">
                                <i data-feather="clock"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Trial</h6>
                            <h3>{{ $stats['trial'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-danger">
                                <i data-feather="x-circle"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Expired</h6>
                            <h3>{{ $stats['expired'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Owners Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-top mb-3">
                    <div class="page-header">
                        <div class="page-title">
                            <h5>Business Owners & Subscriptions</h5>
                            <h6>Manage business owner plans and subscription periods</h6>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Business Owner</th>
                                <th>Stores</th>
                                <th>Current Plan</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Next Renewal</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($businessOwners as $owner)
                            @php
                                $currentSubscription = $owner->subscriptions->first();
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('superadmin.business-owners.profile', $owner->id) }}" class="text-decoration-none">
                                        <strong>{{ $owner->name }}</strong>
                                    </a><br>
                                    <small class="text-muted">{{ $owner->email }}</small>
                                </td>
                                <td>
                                    @if($owner->ownedStores->count() > 0)
                                        <button class="btn btn-sm btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#stores-{{ $owner->id }}" aria-expanded="false">
                                            {{ $owner->ownedStores->count() }} Store(s) <i data-feather="chevron-down"></i>
                                        </button>
                                        <div class="collapse mt-2" id="stores-{{ $owner->id }}">
                                            <ul class="list-unstyled mb-0">
                                                @foreach($owner->ownedStores as $store)
                                                <li><i data-feather="check-circle" style="width: 14px; height: 14px;"></i> {{ $store->name }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <span class="badge bg-secondary">No Stores</span>
                                    @endif
                                </td>
                                <td>
                                    @if($owner->currentPlan)
                                        <span class="badge bg-{{ $owner->currentPlan->slug == 'free' ? 'secondary' : ($owner->currentPlan->slug == 'pro' ? 'success' : 'primary') }}">
                                            {{ $owner->currentPlan->name }}
                                        </span>
                                        <br>
                                        <small class="text-muted">MYR {{ number_format($owner->currentPlan->price, 2) }}/mo</small>
                                    @else
                                        <span class="badge bg-secondary">No Plan</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $currentSubscription && $currentSubscription->starts_at ? \Carbon\Carbon::parse($currentSubscription->starts_at)->format('d M Y') : 'N/A' }}
                                </td>
                                <td>
                                    {{ $currentSubscription && $currentSubscription->ends_at ? \Carbon\Carbon::parse($currentSubscription->ends_at)->format('d M Y') : 'N/A' }}
                                </td>
                                <td>
                                    @if($currentSubscription && $currentSubscription->next_billing_date)
                                        {{ \Carbon\Carbon::parse($currentSubscription->next_billing_date)->format('d M Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($currentSubscription)
                                        @php
                                            $calcStatus = $currentSubscription->calculateStatus();
                                        @endphp
                                        @if($calcStatus === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($calcStatus === 'trial')
                                            <span class="badge bg-info">Trial</span>
                                        @elseif($calcStatus === 'grace')
                                            <span class="badge bg-warning">Grace Period</span>
                                        @elseif($calcStatus === 'expired')
                                            <span class="badge bg-danger">Expired</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($calcStatus) }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">No Subscription</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('superadmin.business-owners.profile', $owner->id) }}" class="btn btn-sm btn-primary">
                                        <i data-feather="eye"></i> View Profile
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No business owners found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $businessOwners->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Plan Modal -->
<div class="modal fade" id="assignPlanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="assignPlanForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Assign Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Assigning plan to: <strong id="modalOwnerName"></strong></p>
                    
                    <div class="form-group">
                        <label>Select Plan <span class="text-danger">*</span></label>
                        <select name="plan_id" id="modalPlanId" class="form-control" required>
                            <option value="">Choose a plan...</option>
                            @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" data-price="{{ $plan->price }}">
                                {{ $plan->name }} - MYR {{ number_format($plan->price, 2) }}/mo
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="starts_at" id="modalStartDate" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>End Date <span class="text-danger">*</span></label>
                        <input type="date" name="ends_at" id="modalEndDate" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" id="modalStatus" class="form-control" required>
                            <option value="active">Active</option>
                            <option value="trial">Trial</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="expired">Expired</option>
                        </select>
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
function openAssignModal(userId, userName, planId, startDate, endDate, status) {
    document.getElementById('modalOwnerName').textContent = userName;
    // Set form action with proper route
    var baseUrl = '{{ url("superadmin/subscriptions") }}';
    document.getElementById('assignPlanForm').action = baseUrl + '/' + userId + '/assign-plan';
    
    // Pre-fill form fields
    document.getElementById('modalPlanId').value = planId || '';
    document.getElementById('modalStartDate').value = startDate || '';
    document.getElementById('modalEndDate').value = endDate || '';
    document.getElementById('modalStatus').value = status || 'active';
    
    console.log('Form action set to: ' + document.getElementById('assignPlanForm').action);
}

// Re-initialize Feather icons after modal content changes
document.getElementById('assignPlanModal').addEventListener('shown.bs.modal', function () {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endsection

