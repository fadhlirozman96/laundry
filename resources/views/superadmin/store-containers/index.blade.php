@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Store Containers</h4>
                <h6>Manage all stores across businesses</h6>
            </div>
        </div>

        <!-- Stats -->
        <div class="row">
            <div class="col-xl-4 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-primary">
                                <i data-feather="box"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Total Stores</h6>
                            <h3>{{ $stats['total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12">
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
            <div class="col-xl-4 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-warning">
                                <i data-feather="x-circle"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Inactive</h6>
                            <h3>{{ $stats['inactive'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Store List -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Store Name</th>
                                <th>Business</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stores as $store)
                            <tr>
                                <td><strong>{{ $store->name }}</strong></td>
                                <td>{{ $store->owner->name ?? 'N/A' }}</td>
                                <td>
                                    @if($store->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-warning">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $store->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('superadmin.store-containers.show', $store->id) }}" class="btn btn-sm btn-info">
                                        <i data-feather="eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No stores found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $stores->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


