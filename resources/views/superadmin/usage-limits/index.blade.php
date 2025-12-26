@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Usage & Limits Tracking</h4>
                <h6>Monitor usage across all businesses</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Business</th>
                                <th>Plan</th>
                                <th>Stores Usage</th>
                                <th>Users</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usageData as $data)
                            @php
                                $business = $data['business'];
                                $usage = $data['usage'];
                                $plan = $business->subscriptions->first()->plan ?? null;
                                $storesUsed = $business->stores->count();
                                $storesLimit = $plan ? $plan->max_stores : 0;
                                $usersUsed = $business->users->count();
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $business->name }}</strong><br>
                                    <small class="text-muted">{{ $business->owner->name ?? 'N/A' }}</small>
                                </td>
                                <td>{{ $plan->name ?? 'No Plan' }}</td>
                                <td>
                                    <div class="progress" style="height: 25px;">
                                        @php
                                            $percentage = $storesLimit > 0 ? ($storesUsed / $storesLimit) * 100 : 0;
                                            $color = $percentage >= 90 ? 'danger' : ($percentage >= 75 ? 'warning' : 'success');
                                        @endphp
                                        <div class="progress-bar bg-{{ $color }}" style="width: {{ min($percentage, 100) }}%">
                                            {{ $storesUsed }} / {{ $storesLimit == -1 ? '∞' : $storesLimit }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $usersUsed }} users</span>
                                </td>
                                <td>
                                    @if($storesLimit == -1 || $storesUsed < $storesLimit)
                                        <span class="badge bg-success">Within Limit</span>
                                    @elseif($storesUsed == $storesLimit)
                                        <span class="badge bg-warning">At Limit</span>
                                    @else
                                        <span class="badge bg-danger">Over Limit</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('superadmin.usage-limits.business', $business->id) }}" class="btn btn-sm btn-info">
                                        <i data-feather="bar-chart"></i> Details
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


