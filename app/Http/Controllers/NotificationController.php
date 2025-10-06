<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get notifications for current user
     */
    public function getNotifications(Request $request)
    {
        $notifications = NotificationService::getForUser(Auth::id(), 10);
        $unreadCount = NotificationService::getUnreadCount(Auth::id());

        $html = '';

        if ($notifications->isEmpty()) {
            $html = '<div class="text-center py-4">
                        <i class="material-icons-outlined fs-1 text-muted">notifications_none</i>
                        <p class="text-muted mb-0">No notifications yet</p>
                     </div>';
        } else {
            foreach ($notifications as $notification) {
                $isUnread = ! $notification->is_read ? 'bg-light' : '';
                $actionUrl = $notification->action_url ? "onclick=\"markAsReadAndRedirect({$notification->id}, '{$notification->action_url}')\"" : '';

                $html .= '<div id="notification-'.$notification->id.'">
                    <a class="dropdown-item border-bottom py-2 '.$isUnread.'" href="javascript:;" '.$actionUrl.'>
                        <div class="d-flex align-items-start gap-3">
                            <div class="flex-grow-1">
                                <h6 class="notify-title mb-1 fw-bold">'.$notification->title.'</h6>
                                <p class="mb-1 notify-desc" style="white-space: normal; word-wrap: break-word; line-height: 1.4; max-width: 280px;">'.$notification->message.'</p>
                                <p class="mb-0 notify-time small text-muted">'.$notification->time_ago.'</p>
                            </div>
                            <div class="notify-close">
                                <button class="btn btn-sm btn-outline-danger" onclick="removeNotification('.$notification->id.', event)" title="Remove notification">
                                    <i class="material-icons-outlined fs-6">close</i>
                                </button>
                            </div>
                        </div>
                    </a>
                </div>';
            }
        }

        return response()->json([
            'html' => $html,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = NotificationService::markAsRead($id);
        $unreadCount = NotificationService::getUnreadCount(Auth::id());

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Remove notification (permanently delete from database)
     */
    public function removeNotification(Request $request, $id)
    {
        $deleted = NotificationService::deleteNotification($id);
        $unreadCount = NotificationService::getUnreadCount(Auth::id());

        if ($deleted) {
            return response()->json([
                'success' => true,
                'unread_count' => $unreadCount,
                'removed' => true,
                'message' => 'Notification deleted successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found or already deleted',
            ], 404);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        NotificationService::markAllAsRead(Auth::id());

        return response()->json([
            'success' => true,
            'unread_count' => 0,
        ]);
    }

    /**
     * Get type color for notification
     */
    private function getTypeColor($type)
    {
        $colors = [
            'success' => 'success',
            'error' => 'danger',
            'warning' => 'warning',
            'info' => 'info',
            'order' => 'primary',
            'invoice' => 'info',
            'status' => 'warning',
        ];

        return $colors[$type] ?? 'secondary';
    }
}
