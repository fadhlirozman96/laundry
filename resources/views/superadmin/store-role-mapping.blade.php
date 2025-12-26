@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Store Role Mapping</h4>
                <h6>Map users to stores with roles</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Store</th>
                                <th>Business</th>
                                <th>Users</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stores as $store)
                            <tr>
                                <td><strong>{{ $store->store_name }}</strong></td>
                                <td>{{ $store->business->name ?? 'N/A' }}</td>
                                <td>
                                    @foreach($store->users as $user)
                                        <span class="badge bg-secondary">{{ $user->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary">Manage</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No stores found</td>
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


