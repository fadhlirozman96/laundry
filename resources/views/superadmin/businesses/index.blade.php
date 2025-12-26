@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Business Management</h4>
                <h6>Manage all business tenants</h6>
            </div>
        </div>

        <!-- Stats Cards -->
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
                            <h6 class="text-muted">Total Businesses</h6>
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
                            <span class="dash-widget-icon bg-warning">
                                <i data-feather="clock"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">On Trial</h6>
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
                            <h6 class="text-muted">Suspended</h6>
                            <h3>{{ $stats['suspended'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business List -->
        <div class="card">
            <div class="card-body">
                <div class="table-top">
                    <div class="search-set">
                        <h5>Business List</h5>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Business Name</th>
                                <th>Owner</th>
                                <th>Stores</th>
                                <th>Status</th>
                                <th>Plan</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($businesses as $business)
                            <tr>
                                <td>
                                    <strong>{{ $business->name }}</strong><br>
                                    <small class="text-muted">{{ $business->slug }}</small>
                                </td>
                                <td>
                                    {{ $business->owner->name ?? 'N/A' }}<br>
                                    <small class="text-muted">{{ $business->owner->email ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $business->stores->count() }} stores</span>
                                </td>
                                <td>
                                    @if($business->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($business->status === 'trial')
                                        <span class="badge bg-warning">Trial</span>
                                    @elseif($business->status === 'suspended')
                                        <span class="badge bg-danger">Suspended</span>
                                    @endif
                                </td>
                                <td>
                                    @if($business->subscriptions->first())
                                        {{ $business->subscriptions->first()->plan->name ?? 'N/A' }}
                                    @else
                                        <span class="text-muted">No Plan</span>
                                    @endif
                                </td>
                                <td>{{ $business->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('superadmin.businesses.show', $business->id) }}" class="btn btn-sm btn-info">
                                        <i data-feather="eye"></i> View
                                    </a>
                                    @if($business->status === 'active')
                                        <form action="{{ route('superadmin.businesses.suspend', $business->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning">Suspend</button>
                                        </form>
                                    @elseif($business->status === 'suspended')
                                        <form action="{{ route('superadmin.businesses.activate', $business->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Activate</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No businesses found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $businesses->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


