<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    /**
     * Get notifications for current user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications(Request $request)
    {
        try {
            $userId = Auth::id();

            if (! $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }

            $notifications = NotificationService::getForUser($userId, 10);
            $unreadCount = NotificationService::getUnreadCount($userId);

            $html = $this->buildNotificationsHtml($notifications);

            return response()->json([
                'success' => true,
                'html' => $html,
                'unread_count' => $unreadCount,
                'count' => $notifications->count(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving notifications: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mark notification as read
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request, $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid notification ID',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $notification = NotificationService::markAsRead($id);

            if (! $notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found',
                ], 404);
            }

            $unreadCount = NotificationService::getUnreadCount(Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read',
                'unread_count' => $unreadCount,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking notification as read: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove notification (permanently delete from database)
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeNotification(Request $request, $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid notification ID',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $deleted = NotificationService::deleteNotification($id);

            if (! $deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found or already deleted',
                ], 404);
            }

            $unreadCount = NotificationService::getUnreadCount(Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully',
                'unread_count' => $unreadCount,
                'removed' => true,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting notification: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead(Request $request)
    {
        try {
            $userId = Auth::id();

            if (! $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }

            NotificationService::markAllAsRead($userId);

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
                'unread_count' => 0,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking all notifications as read: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Build HTML for notifications
     *
     * @param  \Illuminate\Support\Collection  $notifications
     * @return string
     */
    private function buildNotificationsHtml($notifications)
    {
        if ($notifications->isEmpty()) {
            return '<div class="text-center py-4">
                        <i class="material-icons-outlined fs-1 text-muted">notifications_none</i>
                        <p class="text-muted mb-0">No notifications yet</p>
                     </div>';
        }

        $html = '';

        foreach ($notifications as $notification) {
            $html .= $this->buildNotificationItem($notification);
        }

        return $html;
    }

    /**
     * Build single notification item HTML
     *
     * @param  object  $notification
     * @return string
     */
    private function buildNotificationItem($notification)
    {
        $notificationId = htmlspecialchars($notification->id, ENT_QUOTES, 'UTF-8');
        $title = htmlspecialchars($notification->title ?? '', ENT_QUOTES, 'UTF-8');
        $message = htmlspecialchars($notification->message ?? '', ENT_QUOTES, 'UTF-8');
        $timeAgo = htmlspecialchars($notification->time_ago ?? '', ENT_QUOTES, 'UTF-8');
        $isUnread = (! $notification->is_read) ? 'bg-light' : '';

        $actionUrl = '';
        if ($notification->action_url) {
            $escapedUrl = htmlspecialchars($notification->action_url, ENT_QUOTES, 'UTF-8');
            $actionUrl = "onclick=\"markAsReadAndRedirect({$notificationId}, '{$escapedUrl}')\"";
        }

        return "<div id=\"notification-{$notificationId}\">
                    <a class=\"dropdown-item border-bottom py-2 {$isUnread}\" href=\"javascript:;\" {$actionUrl}>
                        <div class=\"d-flex align-items-start gap-3\">
                            <div class=\"flex-grow-1\">
                                <h6 class=\"notify-title mb-1 fw-bold\">{$title}</h6>
                                <p class=\"mb-1 notify-desc\" style=\"white-space: normal; word-wrap: break-word; line-height: 1.4; max-width: 280px;\">{$message}</p>
                                <p class=\"mb-0 notify-time small text-muted\">{$timeAgo}</p>
                            </div>
                            <div class=\"notify-close\">
                                <button class=\"btn btn-sm btn-outline-danger\" onclick=\"removeNotification({$notificationId}, event)\" title=\"Remove notification\">
                                    <i class=\"material-icons-outlined fs-6\">close</i>
                                </button>
                            </div>
                        </div>
                    </a>
                </div>";
    }

    /**
     * Get type color for notification
     *
     * @param  string  $type
     * @return string
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
