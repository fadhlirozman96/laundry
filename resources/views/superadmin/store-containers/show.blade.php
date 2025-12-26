@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Store Details</h4>
                <h6>{{ $store->name }}</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('superadmin.store-containers.index') }}" class="btn btn-secondary">
                    <i data-feather="arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <p><strong>Store Name:</strong> {{ $store->name }}</p>
                        <p><strong>Business:</strong> {{ $store->owner->name ?? 'N/A' }}</p>
                        <p><strong>Status:</strong> 
                            @if($store->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-warning">Inactive</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-lg-6">
                        <p><strong>Created:</strong> {{ $store->created_at->format('d M Y') }}</p>
                        <p><strong>Users:</strong> {{ $store->users->count() }}</p>
                    </div>
                </div>

                <hr>

                <h5>Assigned Users</h5>
                <div class="table-responsive mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($store->users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-secondary">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No users assigned</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


