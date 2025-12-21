<?php $page = 'audit-trail'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>User Activity: {{ $targetUser->name }}</h4>
                <h6>Activity history and login records</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('audit-trail.index') }}" class="btn btn-secondary">
                    <i data-feather="arrow-left" class="me-2"></i>Back to Audit Trail
                </a>
            </div>
        </div>

        <div class="row">
            <!-- User Info & Summary -->
            <div class="col-lg-4">
                <!-- User Card -->
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img src="{{ asset('build/img/users/user-01.jpg') }}" alt="User" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                        <h5 class="mb-1">{{ $targetUser->name }}</h5>
                        <p class="text-muted mb-2">{{ $targetUser->email }}</p>
                        <span class="badge bg-primary">
                            @php
                                $role = $targetUser->roles?->first();
                            @endphp
                            {{ $role ? ucfirst(str_replace('_', ' ', $role->name)) : 'User' }}
                        </span>
                    </div>
                </div>

                <!-- Activity Summary -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Activity Summary</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i data-feather="plus-circle" class="text-success me-2" style="width:16px;"></i>Created</span>
                                <span class="badge bg-success">{{ $summary['create'] ?? 0 }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i data-feather="eye" class="text-info me-2" style="width:16px;"></i>Viewed</span>
                                <span class="badge bg-info">{{ $summary['read'] ?? 0 }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i data-feather="edit" class="text-warning me-2" style="width:16px;"></i>Updated</span>
                                <span class="badge bg-warning">{{ $summary['update'] ?? 0 }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i data-feather="trash-2" class="text-danger me-2" style="width:16px;"></i>Deleted</span>
                                <span class="badge bg-danger">{{ $summary['delete'] ?? 0 }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i data-feather="log-in" class="text-primary me-2" style="width:16px;"></i>Logins</span>
                                <span class="badge bg-primary">{{ $summary['login'] ?? 0 }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Recent Logins -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Logins</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($recentLogins as $login)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small>{{ $login->created_at->format('d M Y H:i') }}</small><br>
                                        <small class="text-muted">{{ $login->ip_address }} • {{ $login->browser }}</small>
                                    </div>
                                    <span class="badge bg-success">✓</span>
                                </div>
                            </li>
                            @empty
                            <li class="list-group-item text-center text-muted">No login records</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Activity Timeline</h5>
                    </div>
                    <div class="card-body">
                        <div class="activity-timeline">
                            @forelse($activities as $activity)
                            <div class="activity-item d-flex mb-4">
                                <div class="activity-icon me-3">
                                    <div class="icon-circle bg-{{ \App\Models\ActivityLog::ACTION_COLORS[$activity->action] ?? 'secondary' }}">
                                        <i data-feather="{{ \App\Models\ActivityLog::ACTION_ICONS[$activity->action] ?? 'activity' }}"></i>
                                    </div>
                                </div>
                                <div class="activity-content flex-grow-1">
                                    <div class="d-flex justify-content-between mb-1">
                                        <strong>{{ $activity->action_label }}</strong>
                                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $activity->description }}</p>
                                    <small class="text-muted">
                                        @if($activity->model_name)
                                            <span class="badge bg-light text-dark">{{ $activity->model_name }}</span>
                                        @endif
                                        @if($activity->store_name)
                                            • {{ $activity->store_name }}
                                        @endif
                                        • {{ $activity->created_at->format('d M Y H:i:s') }}
                                    </small>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-muted py-4">
                                <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                                <p class="mt-2">No activity records found</p>
                            </div>
                            @endforelse
                        </div>

                        <div class="mt-3">
                            {{ $activities->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    .icon-circle svg {
        width: 18px;
        height: 18px;
    }
    .activity-item {
        position: relative;
    }
    .activity-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 19px;
        top: 45px;
        height: calc(100% - 25px);
        width: 2px;
        background: #e9ecef;
    }
</style>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush


