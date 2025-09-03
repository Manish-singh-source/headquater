<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    /**
     * Create a new notification
     */
    public static function create(array $data)
    {
        return Notification::create([
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'] ?? 'info',
            'module' => $data['module'],
            'module_id' => $data['module_id'] ?? null,
            'icon' => $data['icon'] ?? self::getDefaultIcon($data['type'] ?? 'info'),
            'url' => $data['url'] ?? null,
            'user_id' => $data['user_id'] ?? null,
        ]);
    }

    /**
     * Create a Sales Order notification
     */
    public static function salesOrderCreated($salesOrder)
    {
        return self::create([
            'title' => 'New Sales Order Created',
            'message' => "Sales Order #{$salesOrder->id} has been created for {$salesOrder->customerGroup->name}",
            'type' => 'success',
            'module' => 'sales_order',
            'module_id' => $salesOrder->id,
            'icon' => 'bi bi-cart-plus',
            'url' => route('order.index'),
        ]);
    }

    /**
     * Create a Purchase Order notification
     */
    public static function purchaseOrderCreated($purchaseOrder)
    {
        return self::create([
            'title' => 'New Purchase Order Created',
            'message' => "Purchase Order #{$purchaseOrder->id} has been created",
            'type' => 'info',
            'module' => 'purchase_order',
            'module_id' => $purchaseOrder->id,
            'icon' => 'bi bi-bag-plus',
            'url' => route('purchase.order.view', $purchaseOrder->id),
        ]);
    }

    /**
     * Create a Product received notification
     */
    public static function productsReceived($count, $orderId = null)
    {
        return self::create([
            'title' => 'Products Received',
            'message' => "{$count} products have been received and stock updated",
            'type' => 'success',
            'module' => 'product',
            'module_id' => $orderId,
            'icon' => 'bi bi-box-seam',
            'url' => route('products.index'),
        ]);
    }

    /**
     * Create a Packaging List notification
     */
    public static function packagingListGenerated($salesOrder)
    {
        return self::create([
            'title' => 'Packaging List Generated',
            'message' => "Packaging list for Sales Order #{$salesOrder->id} is ready",
            'type' => 'warning',
            'module' => 'packaging',
            'module_id' => $salesOrder->id,
            'icon' => 'bi bi-box',
            'url' => route('packaging.list.index'),
        ]);
    }

    /**
     * Create a Ready to Ship notification
     */
    public static function readyToShip($salesOrder)
    {
        return self::create([
            'title' => 'Order Ready to Ship',
            'message' => "Sales Order #{$salesOrder->id} is ready for shipment",
            'type' => 'success',
            'module' => 'ready_to_ship',
            'module_id' => $salesOrder->id,
            'icon' => 'bi bi-truck',
            'url' => route('readyToShip.index'),
        ]);
    }

    /**
     * Get recent notifications
     */
    public static function getRecent($limit = 10, $userId = null)
    {
        return Notification::forUser($userId)
            ->recent($limit)
            ->get();
    }

    /**
     * Get unread count
     */
    public static function getUnreadCount($userId = null)
    {
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
     * Mark all notifications as read
     */
    public static function markAllAsRead($userId = null)
    {
        return Notification::markAllAsRead($userId);
    }

    /**
     * Get default icon based on type
     */
    private static function getDefaultIcon($type)
    {
        return match($type) {
            'success' => 'bi bi-check-circle',
            'error' => 'bi bi-x-circle',
            'warning' => 'bi bi-exclamation-triangle',
            'info' => 'bi bi-info-circle',
            default => 'bi bi-bell',
        };
    }
}
