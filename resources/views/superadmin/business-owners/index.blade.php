@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Business Owners</h4>
                <h6>Manage business owner accounts and their subscriptions</h6>
            </div>
        </div>

        <!-- Stats -->
        <div class="row">
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-primary">
                                <i data-feather="briefcase"></i>
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
                            <h6 class="text-muted">With Stores</h6>
                            <h3>{{ $stats['with_stores'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-info">
                                <i data-feather="gift"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Free Plan</h6>
                            <h3>{{ $stats['on_free'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-warning">
                                <i data-feather="dollar-sign"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Paid Plans</h6>
                            <h3>{{ $stats['on_paid'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Owners List -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Owner</th>
                                <th>Company</th>
                                <th>Plan</th>
                                <th>Stores</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($owners as $owner)
                            <tr>
                                <td>
                                    <strong>{{ $owner->name }}</strong><br>
                                    <small class="text-muted">{{ $owner->email }}</small>
                                </td>
                                <td>{{ $owner->company_name ?? 'N/A' }}</td>
                                <td>
                                    @if($owner->currentPlan)
                                        <span class="badge bg-{{ $owner->currentPlan->slug == 'free' ? 'secondary' : 'primary' }}">
                                            {{ $owner->currentPlan->name }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">No Plan</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $owner->ownedStores->count() }} store(s)</span>
                                </td>
                                <td>
                                    @php
                                        $subscription = $owner->subscriptions->first();
                                    @endphp
                                    @if($subscription)
                                        @if($subscription->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($subscription->status === 'trial')
                                            <span class="badge bg-info">Trial</span>
                                        @else
                                            <span class="badge bg-danger">{{ ucfirst($subscription->status) }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">No Subscription</span>
                                    @endif
                                </td>
                                <td>{{ $owner->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('superadmin.users.show', $owner->id) }}" class="btn btn-sm btn-info">
                                        <i data-feather="eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No business owners found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $owners->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

