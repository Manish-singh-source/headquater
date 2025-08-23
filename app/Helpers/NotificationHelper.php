<?php

use App\Services\NotificationService;

if (!function_exists('notify')) {
    /**
     * Create a notification
     */
    function notify(array $data)
    {
        return NotificationService::create($data);
    }
}

if (!function_exists('notifySalesOrder')) {
    /**
     * Create a sales order notification
     */
    function notifySalesOrder($salesOrder)
    {
        return NotificationService::salesOrderCreated($salesOrder);
    }
}

if (!function_exists('notifyPurchaseOrder')) {
    /**
     * Create a purchase order notification
     */
    function notifyPurchaseOrder($purchaseOrder)
    {
        return NotificationService::purchaseOrderCreated($purchaseOrder);
    }
}

if (!function_exists('notifyProductsReceived')) {
    /**
     * Create a products received notification
     */
    function notifyProductsReceived($count, $orderId = null)
    {
        return NotificationService::productsReceived($count, $orderId);
    }
}

if (!function_exists('notifyPackagingList')) {
    /**
     * Create a packaging list notification
     */
    function notifyPackagingList($salesOrder)
    {
        return NotificationService::packagingListGenerated($salesOrder);
    }
}

if (!function_exists('notifyReadyToShip')) {
    /**
     * Create a ready to ship notification
     */
    function notifyReadyToShip($salesOrder)
    {
        return NotificationService::readyToShip($salesOrder);
    }
}

if (!function_exists('getNotifications')) {
    /**
     * Get recent notifications
     */
    function getNotifications($limit = 10, $userId = null)
    {
        return NotificationService::getRecent($limit, $userId);
    }
}

if (!function_exists('getUnreadNotificationsCount')) {
    /**
     * Get unread notifications count
     */
    function getUnreadNotificationsCount($userId = null)
    {
        return NotificationService::getUnreadCount($userId);
    }
}
