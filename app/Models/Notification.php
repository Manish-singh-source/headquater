<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'icon',
        'data',
        'user_id',
        'is_read',
        'read_at',
        'action_url',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getIconClassAttribute()
    {
        $icons = [
            'success' => 'bi bi-check2-circle text-success',
            'error' => 'bi bi-x-circle text-danger',
            'warning' => 'bi bi-exclamation-triangle text-warning',
            'info' => 'bi bi-info-circle text-info',
            'order' => 'bi bi-cart-plus text-primary',
            'invoice' => 'bi bi-receipt text-info',
            'status' => 'bi bi-arrow-repeat text-warning',
        ];

        return $icons[$this->type] ?? 'bi bi-bell text-secondary';
    }
}
