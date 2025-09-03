<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get notifications for the dropdown
     */
    public function getNotifications(Request $request)
    {
        $limit = $request->get('limit', 10);
        $userId = Auth::check() ? Auth::id() : null;

        $notifications = NotificationService::getRecent($limit, $userId);
        $unreadCount = NotificationService::getUnreadCount($userId);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
            'success' => true
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $notification = NotificationService::markAsRead($id);

        if ($notification) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification not found'
        ], 404);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $userId = Auth::check() ? Auth::id() : null;
        $count = NotificationService::markAllAsRead($userId);

        return response()->json([
            'success' => true,
            'message' => "Marked {$count} notifications as read"
        ]);
    }

    /**
     * Get unread count
     */
    public function getUnreadCount()
    {
        $userId = Auth::check() ? Auth::id() : null;
        $count = NotificationService::getUnreadCount($userId);

        return response()->json([
            'unread_count' => $count,
            'success' => true
        ]);
    }

    /**
     * Delete a notification
     */
    public function delete($id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $notification->delete();
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification not found'
        ], 404);
    }

    /**
     * Show all notifications page
     */
    public function index()
    {
        $userId = Auth::check() ? Auth::id() : null;
        $notifications = Notification::forUser($userId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }
}
