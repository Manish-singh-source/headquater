/**
 * Notification System JavaScript
 * Handles real-time notifications, marking as read, and auto-refresh
 */

class NotificationManager {
    constructor() {
        this.refreshInterval = 30000; // 30 seconds
        this.intervalId = null;
        this.isLoading = false;
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadNotifications();
        this.startAutoRefresh();
    }

    bindEvents() {
        // Load notifications when dropdown is opened
        $(document).on('click', '#notificationDropdown', () => {
            this.loadNotifications();
        });

        // Mark all as read
        $(document).on('click', '#markAllRead', (e) => {
            e.preventDefault();
            this.markAllAsRead();
        });

        // Mark single notification as read
        $(document).on('click', '.notification-item a', (e) => {
            const notificationId = $(e.currentTarget).closest('.notification-item').data('id');
            if (notificationId) {
                this.markAsRead(notificationId);
            }
        });

        // Delete notification
        $(document).on('click', '.notify-close', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const notificationId = $(e.currentTarget).closest('.notification-item').data('id');
            if (notificationId) {
                this.deleteNotification(notificationId);
            }
        });

        // Pause auto-refresh when user is interacting with notifications
        $(document).on('mouseenter', '.dropdown-notify', () => {
            this.pauseAutoRefresh();
        });

        $(document).on('mouseleave', '.dropdown-notify', () => {
            this.resumeAutoRefresh();
        });
    }

    loadNotifications() {
        if (this.isLoading) return;
        
        this.isLoading = true;
        this.showLoading();

        $.ajax({
            url: '/notifications',
            method: 'GET',
            data: { limit: 10 },
            success: (response) => {
                this.hideLoading();
                
                if (response.success && response.notifications.length > 0) {
                    this.displayNotifications(response.notifications);
                    this.updateBadge(response.unread_count);
                } else {
                    this.showNoNotifications();
                    this.updateBadge(0);
                }
            },
            error: (xhr, status, error) => {
                this.hideLoading();
                this.showError('Failed to load notifications');
                console.error('Notification load error:', error);
            },
            complete: () => {
                this.isLoading = false;
            }
        });
    }

    displayNotifications(notifications) {
        let html = '';
        
        notifications.forEach((notification) => {
            const isUnread = !notification.is_read;
            const bgClass = isUnread ? 'bg-light' : '';
            const iconClass = this.getNotificationIcon(notification.type);
            const iconColor = this.getNotificationColor(notification.type);
            
            html += `
                <div class="notification-item ${bgClass}" data-id="${notification.id}">
                    <a class="dropdown-item border-bottom py-2" href="${notification.url || 'javascript:;'}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="user-wrapper ${iconColor} bg-opacity-10">
                                <i class="${iconClass}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="notify-title mb-1">${notification.title}</h6>
                                <p class="mb-0 notify-desc text-muted small">${notification.message}</p>
                                <p class="mb-0 notify-time text-muted small">${notification.time_ago || this.formatDate(notification.created_at)}</p>
                            </div>
                            ${isUnread ? '<div class="notify-indicator"><span class="badge bg-primary rounded-pill">New</span></div>' : ''}
                            <div class="notify-close position-absolute end-0 me-3">
                                <i class="material-icons-outlined fs-6">close</i>
                            </div>
                        </div>
                    </a>
                </div>
            `;
        });
        
        $('#notificationList').html(html);
    }

    getNotificationIcon(type) {
        const icons = {
            'success': 'bi bi-check-circle',
            'error': 'bi bi-x-circle',
            'warning': 'bi bi-exclamation-triangle',
            'info': 'bi bi-info-circle'
        };
        return icons[type] || 'bi bi-bell';
    }

    getNotificationColor(type) {
        const colors = {
            'success': 'text-success',
            'error': 'text-danger',
            'warning': 'text-warning',
            'info': 'text-info'
        };
        return colors[type] || 'text-primary';
    }

    formatDate(dateString) {
        try {
            const date = new Date(dateString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);

            // Less than a minute ago
            if (diffInSeconds < 60) {
                return 'Just now';
            }

            // Less than an hour ago
            if (diffInSeconds < 3600) {
                const minutes = Math.floor(diffInSeconds / 60);
                return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
            }

            // Less than a day ago
            if (diffInSeconds < 86400) {
                const hours = Math.floor(diffInSeconds / 3600);
                return `${hours} hour${hours > 1 ? 's' : ''} ago`;
            }

            // Less than a week ago
            if (diffInSeconds < 604800) {
                const days = Math.floor(diffInSeconds / 86400);
                return `${days} day${days > 1 ? 's' : ''} ago`;
            }

            // Format as readable date for older notifications
            const options = {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            return date.toLocaleDateString('en-US', options);

        } catch (error) {
            console.error('Date formatting error:', error);
            return dateString; // Fallback to original string
        }
    }

    updateBadge(count) {
        const badge = $('#notificationBadge');
        if (count > 0) {
            badge.text(count).show();
        } else {
            badge.hide();
        }
    }

    markAsRead(notificationId) {
        $.ajax({
            url: `/notifications/${notificationId}/mark-read`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                if (response.success) {
                    this.updateNotificationCount();
                    // Remove the "New" badge from the notification
                    $(`.notification-item[data-id="${notificationId}"]`)
                        .removeClass('bg-light')
                        .find('.notify-indicator').remove();
                }
            },
            error: (xhr, status, error) => {
                console.error('Mark as read error:', error);
            }
        });
    }

    markAllAsRead() {
        $.ajax({
            url: '/notifications/mark-all-read',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                if (response.success) {
                    this.loadNotifications();
                    this.updateBadge(0);
                    this.showToast('All notifications marked as read', 'success');
                }
            },
            error: (xhr, status, error) => {
                console.error('Mark all as read error:', error);
                this.showToast('Failed to mark notifications as read', 'error');
            }
        });
    }

    deleteNotification(notificationId) {
        $.ajax({
            url: `/notifications/${notificationId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                if (response.success) {
                    $(`.notification-item[data-id="${notificationId}"]`).fadeOut(300, function() {
                        $(this).remove();
                        // Check if there are any notifications left
                        if ($('.notification-item').length === 0) {
                            $('#noNotifications').show();
                        }
                    });
                    this.updateNotificationCount();
                }
            },
            error: (xhr, status, error) => {
                console.error('Delete notification error:', error);
            }
        });
    }

    updateNotificationCount() {
        $.ajax({
            url: '/notifications/unread-count',
            method: 'GET',
            success: (response) => {
                if (response.success) {
                    this.updateBadge(response.unread_count);
                }
            },
            error: (xhr, status, error) => {
                console.error('Update count error:', error);
            }
        });
    }

    showLoading() {
        $('#loadingNotifications').show();
        $('#noNotifications').hide();
    }

    hideLoading() {
        $('#loadingNotifications').hide();
    }

    showNoNotifications() {
        $('#noNotifications').show();
        $('#notificationList').html('');
    }

    showError(message) {
        $('#notificationList').html(`
            <div class="text-center py-4">
                <i class="material-icons-outlined fs-1 text-danger">error</i>
                <p class="text-danger mb-0">${message}</p>
            </div>
        `);
    }

    showToast(message, type = 'info') {
        // Use existing notification system if available
        if (typeof Lobibox !== 'undefined') {
            Lobibox.notify(type, {
                pauseDelayOnHover: true,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                msg: message
            });
        } else {
            // Fallback to console log
            console.log(`${type.toUpperCase()}: ${message}`);
        }
    }

    startAutoRefresh() {
        this.intervalId = setInterval(() => {
            this.updateNotificationCount();
        }, this.refreshInterval);
    }

    pauseAutoRefresh() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
        }
    }

    resumeAutoRefresh() {
        if (!this.intervalId) {
            this.startAutoRefresh();
        }
    }

    destroy() {
        this.pauseAutoRefresh();
        $(document).off('click', '#notificationDropdown');
        $(document).off('click', '#markAllRead');
        $(document).off('click', '.notification-item a');
        $(document).off('click', '.notify-close');
        $(document).off('mouseenter', '.dropdown-notify');
        $(document).off('mouseleave', '.dropdown-notify');
    }
}

// Initialize notification manager when document is ready
$(document).ready(function() {
    window.notificationManager = new NotificationManager();
});

// Clean up on page unload
$(window).on('beforeunload', function() {
    if (window.notificationManager) {
        window.notificationManager.destroy();
    }
});
