@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Usage Details</h4>
                <h6>{{ $business->name }}</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('superadmin.usage-limits.index') }}" class="btn btn-secondary">
                    <i data-feather="arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5>Stores Usage</h5>
                        <h2>{{ $business->stores->count() }} / {{ $business->subscriptions->first()->plan->max_stores ?? 0 }}</h2>
                        <small class="text-muted">stores used</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5>Users</h5>
                        <h2>{{ $business->users->count() }}</h2>
                        <small class="text-muted">total users</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5>Current Plan</h5>
                        <h2>{{ $business->subscriptions->first()->plan->name ?? 'N/A' }}</h2>
                        <small class="text-muted">subscription plan</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Detailed Usage Metrics</h5>
            </div>
            <div class="card-body">
                @if(empty($usage))
                    <p class="text-muted">No usage tracking data available yet.</p>
                @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Metric</th>
                                <th>Current Value</th>
                                <th>Limit</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usage as $metric => $data)
                            <tr>
                                <td>{{ $metric }}</td>
                                <td>{{ $data['current_value'] ?? 0 }}</td>
                                <td>{{ $data['limit_value'] ?? 'Unlimited' }}</td>
                                <td>
                                    @if(isset($data['limit_value']) && $data['current_value'] < $data['limit_value'])
                                        <span class="badge bg-success">Within Limit</span>
                                    @else
                                        <span class="badge bg-warning">Check Limit</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


