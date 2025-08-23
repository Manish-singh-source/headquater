@extends('layouts.master')

@section('main-content')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Notifications</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">All Notifications</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">All Notifications</h5>
                            <button type="button" class="btn btn-primary btn-sm" id="markAllReadBtn">
                                <i class="material-icons-outlined">done_all</i> Mark All as Read
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($notifications->count() > 0)
                            <div class="notification-list">
                                @foreach($notifications as $notification)
                                    <div class="notification-item {{ !$notification->is_read ? 'bg-light' : '' }} border-bottom py-3" data-id="{{ $notification->id }}">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="notification-icon">
                                                <div class="user-wrapper {{ $notification->type == 'success' ? 'text-success' : ($notification->type == 'error' ? 'text-danger' : ($notification->type == 'warning' ? 'text-warning' : 'text-info')) }} bg-opacity-10">
                                                    <i class="{{ $notification->icon ?: 'bi bi-bell' }}"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="notification-title mb-1">
                                                            {{ $notification->title }}
                                                            @if(!$notification->is_read)
                                                                <span class="badge bg-primary ms-2">New</span>
                                                            @endif
                                                        </h6>
                                                        <p class="notification-message text-muted mb-2">{{ $notification->message }}</p>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <small class="text-muted">
                                                                <i class="material-icons-outlined fs-6">schedule</i>
                                                                {{ $notification->time_ago }}
                                                            </small>
                                                            <small class="text-muted">
                                                                <i class="material-icons-outlined fs-6">category</i>
                                                                {{ ucfirst(str_replace('_', ' ', $notification->module)) }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="notification-actions">
                                                        @if($notification->url)
                                                            <a href="{{ $notification->url }}" class="btn btn-outline-primary btn-sm me-2" onclick="markAsRead({{ $notification->id }})">
                                                                <i class="material-icons-outlined">visibility</i> View
                                                            </a>
                                                        @endif
                                                        @if(!$notification->is_read)
                                                            <button type="button" class="btn btn-outline-success btn-sm me-2" onclick="markAsRead({{ $notification->id }})">
                                                                <i class="material-icons-outlined">done</i> Mark Read
                                                            </button>
                                                        @endif
                                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteNotification({{ $notification->id }})">
                                                            <i class="material-icons-outlined">delete</i> Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $notifications->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="material-icons-outlined" style="font-size: 4rem; color: #ccc;">notifications_none</i>
                                <h5 class="mt-3 text-muted">No notifications yet</h5>
                                <p class="text-muted">You'll see notifications here when there are updates to your orders, products, and shipments.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!--end main wrapper-->
@endsection

@section('script')
<script>
    function markAsRead(notificationId) {
        $.ajax({
            url: `/notifications/${notificationId}/mark-read`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Remove the "New" badge and background
                    const notificationItem = $(`.notification-item[data-id="${notificationId}"]`);
                    notificationItem.removeClass('bg-light');
                    notificationItem.find('.badge').remove();
                    notificationItem.find('.btn-outline-success').remove();
                    
                    // Update the notification manager if available
                    if (window.notificationManager) {
                        window.notificationManager.updateNotificationCount();
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Mark as read error:', error);
            }
        });
    }

    function deleteNotification(notificationId) {
        if (confirm('Are you sure you want to delete this notification?')) {
            $.ajax({
                url: `/notifications/${notificationId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $(`.notification-item[data-id="${notificationId}"]`).fadeOut(300, function() {
                            $(this).remove();
                            
                            // Check if there are any notifications left
                            if ($('.notification-item').length === 0) {
                                location.reload();
                            }
                        });
                        
                        // Update the notification manager if available
                        if (window.notificationManager) {
                            window.notificationManager.updateNotificationCount();
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Delete notification error:', error);
                    alert('Failed to delete notification');
                }
            });
        }
    }

    // Mark all as read
    $('#markAllReadBtn').on('click', function() {
        $.ajax({
            url: '/notifications/mark-all-read',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Remove all "New" badges and backgrounds
                    $('.notification-item').removeClass('bg-light');
                    $('.notification-item .badge').remove();
                    $('.notification-item .btn-outline-success').remove();
                    
                    // Update the notification manager if available
                    if (window.notificationManager) {
                        window.notificationManager.updateNotificationCount();
                    }
                    
                    // Show success message
                    if (typeof success_noti === 'function') {
                        success_noti();
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Mark all as read error:', error);
                if (typeof error_noti === 'function') {
                    error_noti();
                }
            }
        });
    });
</script>
@endsection
