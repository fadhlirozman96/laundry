@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>User Details</h4>
                <h6>{{ $user->name }}</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">
                    <i data-feather="arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>User Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> {{ $user->name }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Business:</strong> {{ $user->business->name ?? 'N/A' }}</p>
                        <p><strong>Roles:</strong> 
                            @foreach($user->roles as $role)
                                <span class="badge bg-secondary">{{ $role->name }}</span>
                            @endforeach
                        </p>
                        <p><strong>Status:</strong> 
                            @if($user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </p>
                        <p><strong>Joined:</strong> {{ $user->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Stores Access</h5>
                    </div>
                    <div class="card-body">
                        @forelse($user->stores as $store)
                            <p><i data-feather="check-circle" class="text-success"></i> {{ $store->store_name }}</p>
                        @empty
                            <p class="text-muted">No stores assigned</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

