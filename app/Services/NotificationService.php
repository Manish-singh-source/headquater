<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    /**
     * Create a new notification
     */
    public static function create(array $data)
    {
        return Notification::create([
            'type' => $data['type'] ?? 'info',
            'title' => $data['title'],
            'message' => $data['message'],
            'icon' => $data['icon'] ?? null,
            'data' => $data['data'] ?? null,
            'user_id' => $data['user_id'] ?? Auth::id(),
            'action_url' => $data['action_url'] ?? null,
        ]);
    }

    /**
     * Create success notification
     */
    public static function success($title, $message, $data = null, $actionUrl = null, $userId = null)
    {
        return self::create([
            'type' => 'success',
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'action_url' => $actionUrl,
            'user_id' => $userId,
        ]);
    }

    /**
     * Create error notification
     */
    public static function error($title, $message, $data = null, $actionUrl = null, $userId = null)
    {
        return self::create([
            'type' => 'error',
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'action_url' => $actionUrl,
            'user_id' => $userId,
        ]);
    }

    /**
     * Create warning notification
     */
    public static function warning($title, $message, $data = null, $actionUrl = null, $userId = null)
    {
        return self::create([
            'type' => 'warning',
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'action_url' => $actionUrl,
            'user_id' => $userId,
        ]);
    }

    /**
     * Create info notification
     */
    public static function info($title, $message, $data = null, $actionUrl = null, $userId = null)
    {
        return self::create([
            'type' => 'info',
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'action_url' => $actionUrl,
            'user_id' => $userId,
        ]);
    }

    /**
     * Create order notification
     */
    public static function orderCreated($orderType, $orderId, $userId = null)
    {
        $title = ucfirst($orderType).' Order Created';
        $message = "New {$orderType} order #{$orderId} has been created successfully.";

        return self::create([
            'type' => 'order',
            'title' => $title,
            'message' => $message,
            'data' => ['order_id' => $orderId, 'order_type' => $orderType],
            'action_url' => $orderType === 'sales' ? route('sales.order.view', $orderId) : route('purchase.order.view', $orderId),
            'user_id' => $userId,
        ]);
    }

    /**
     * Create invoice notification
     */
    public static function invoiceGenerated($invoiceId, $orderId, $userId = null)
    {
        return self::create([
            'type' => 'invoice',
            'title' => 'Invoice Generated',
            'message' => "Invoice #{$invoiceId} has been generated for order #{$orderId}.",
            'data' => ['invoice_id' => $invoiceId, 'order_id' => $orderId],
            'action_url' => route('invoices'),
            'user_id' => $userId,
        ]);
    }

    /**
     * Create status change notification
     */
    public static function statusChanged($orderType, $orderId, $oldStatus, $newStatus, $userId = null)
    {
        $title = ucfirst($orderType).' Order Status Updated';
        $message = "Order #{$orderId} status changed from ".ucfirst(str_replace('_', ' ', $oldStatus)).' to '.ucfirst(str_replace('_', ' ', $newStatus));

        return self::create([
            'type' => 'status',
            'title' => $title,
            'message' => $message,
            'data' => ['order_id' => $orderId, 'order_type' => $orderType, 'old_status' => $oldStatus, 'new_status' => $newStatus],
            'action_url' => $orderType === 'sales' ? route('sales.order.view', $orderId) : route('purchase.order.view', $orderId),
            'user_id' => $userId,
        ]);
    }

    /**
     * Create warehouse product added notification
     */
    public static function warehouseProductAdded($productName, $quantity, $warehouseId = null, $userId = null)
    {
        $title = 'Product Added to Warehouse';
        $message = "Product '{$productName}' has been added to warehouse with quantity: {$quantity}.";

        return self::create([
            'type' => 'info',
            'title' => $title,
            'message' => $message,
            'data' => [
                'product_name' => $productName,
                'quantity' => $quantity,
                'warehouse_id' => $warehouseId,
            ],
            'action_url' => route('warehouse.index'),
            'user_id' => $userId,
        ]);
    }

    /**
     * Create received products notification
     */
    public static function productsReceived($orderType, $orderId, $productCount, $userId = null)
    {
        $title = 'Products Received';
        $message = "Received {$productCount} products for ".ucfirst($orderType)." Order #{$orderId}.";

        return self::create([
            'type' => 'success',
            'title' => $title,
            'message' => $message,
            'data' => [
                'order_id' => $orderId,
                'order_type' => $orderType,
                'product_count' => $productCount,
            ],
            'action_url' => route('received-products.index'),
            'user_id' => $userId,
        ]);
    }

    /**
     * Get notifications for user
     */
    public static function getForUser($userId = null, $limit = 10)
    {
        $userId = $userId ?? Auth::id();

        return Notification::forUser($userId)
            ->unread()
            ->recent($limit)
            ->get();
    }

    /**
     * Get unread count for user
     */
    public static function getUnreadCount($userId = null)
    {
        $userId = $userId ?? Auth::id();

        return Notification::forUser($userId)
            ->unread()
            ->count();
    }

    /**
     * Mark notification as read
     */
    public static function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }

        return $notification;
    }

    /**
     * Delete notification permanently
     */
    public static function deleteNotification($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->delete();

            return true;
        }

        return false;
    }

    /**
     * Mark all notifications as read for user
     */
    public static function markAllAsRead($userId = null)
    {
        $userId = $userId ?? Auth::id();

        return Notification::forUser($userId)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }
}
