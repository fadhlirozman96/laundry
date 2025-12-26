@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Notifications</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Notifications</li>
                    </ul>
                </div>
                <div class="col-auto">
                    <form action="{{ route('business-owner.notifications.read-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i data-feather="check-circle"></i> Mark All as Read
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Notifications List -->
        <div class="card">
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @forelse($notifications as $notification)
                    <div class="list-group-item {{ !$notification->is_read ? 'bg-light' : '' }}" data-notification-id="{{ $notification->id }}">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar avatar-sm bg-{{ $notification->getColor() }}-light text-{{ $notification->getColor() }} rounded-circle">
                                    <i data-feather="{{ $notification->getIcon() }}"></i>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0">
                                        {{ $notification->title }}
                                        @if(!$notification->is_read)
                                        <span class="badge bg-primary ms-2">New</span>
                                        @endif
                                    </h6>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="text-muted mb-2">{{ $notification->message }}</p>
                                @if($notification->action_url)
                                <a href="{{ $notification->action_url }}" class="btn btn-sm btn-outline-primary">
                                    {{ $notification->action_text ?? 'View Details' }}
                                </a>
                                @endif
                                @if(!$notification->is_read)
                                <button type="button" class="btn btn-sm btn-outline-secondary ms-2 mark-read-btn" data-id="{{ $notification->id }}">
                                    Mark as Read
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i data-feather="bell-off" style="width: 48px; height: 48px;" class="text-muted mb-3"></i>
                        <h5 class="text-muted">No notifications yet</h5>
                        <p class="text-muted">We'll notify you when something important happens</p>
                    </div>
                    @endforelse
                </div>

                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Mark individual notification as read
    document.querySelectorAll('.mark-read-btn').forEach(button => {
        button.addEventListener('click', function() {
            const notificationId = this.getAttribute('data-id');
            
            fetch(`/business-owner/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the notification item or update UI
                    const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
                    notificationItem.classList.remove('bg-light');
                    this.remove();
                    const badge = notificationItem.querySelector('.badge');
                    if (badge) badge.remove();
                }
            });
        });
    });
});
</script>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.bg-success-light { background-color: #d4edda; }
.bg-info-light { background-color: #d1ecf1; }
.bg-warning-light { background-color: #fff3cd; }
.bg-danger-light { background-color: #f8d7da; }
.bg-secondary-light { background-color: #e2e3e5; }
</style>
@endsection

