@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Business Details</h4>
                <h6>{{ $business->name }}</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('superadmin.businesses.index') }}" class="btn btn-secondary">
                    <i data-feather="arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Business Info -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Business Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> {{ $business->name }}</p>
                        <p><strong>Slug:</strong> {{ $business->slug }}</p>
                        <p><strong>Status:</strong> 
                            @if($business->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($business->status === 'trial')
                                <span class="badge bg-warning">Trial</span>
                            @elseif($business->status === 'suspended')
                                <span class="badge bg-danger">Suspended</span>
                            @endif
                        </p>
                        <p><strong>Owner:</strong> {{ $business->owner->name ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $business->contact_email }}</p>
                        <p><strong>Phone:</strong> {{ $business->contact_phone }}</p>
                        <p><strong>Created:</strong> {{ $business->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Usage Summary -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Usage & Limits</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="usage-item p-3 mb-3 border rounded">
                                    <h6>Stores</h6>
                                    <h3>{{ $business->stores->count() }}</h3>
                                    <small class="text-muted">Total stores</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="usage-item p-3 mb-3 border rounded">
                                    <h6>Users</h6>
                                    <h3>{{ $business->users->count() }}</h3>
                                    <small class="text-muted">Total users</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="usage-item p-3 mb-3 border rounded">
                                    <h6>Subscription</h6>
                                    <h3>{{ $business->subscriptions->first()->plan->name ?? 'N/A' }}</h3>
                                    <small class="text-muted">Current plan</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stores List -->
                <div class="card">
                    <div class="card-header">
                        <h5>Stores ({{ $business->stores->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Store Name</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($business->stores as $store)
                                    <tr>
                                        <td>{{ $store->store_name }}</td>
                                        <td>
                                            @if($store->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $store->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $store->created_at->format('d M Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No stores</td>
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
@endsection

