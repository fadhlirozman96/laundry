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
            <div class="col-lg-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-primary">
                                <i data-feather="box"></i>
                            </span>
                            <div class="dash-count">
                                <h3>{{ $stats['total'] }}</h3>
                            </div>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Total Stores</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-success">
                                <i data-feather="check-circle"></i>
                            </span>
                            <div class="dash-count">
                                <h3>{{ $stats['active'] }}</h3>
                            </div>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Active</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-warning">
                                <i data-feather="pause-circle"></i>
                            </span>
                            <div class="dash-count">
                                <h3>{{ $stats['paused'] }}</h3>
                            </div>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Paused</h6>
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
                                <td><strong>{{ $store->store_name }}</strong></td>
                                <td>{{ $store->business->name ?? 'N/A' }}</td>
                                <td>
                                    @if($store->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($store->status === 'paused')
                                        <span class="badge bg-warning">Paused</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $store->status }}</span>
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

