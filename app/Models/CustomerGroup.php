<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CustomerGroup extends Model
{
    use LogsActivity;

    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Activity log options
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'status'])
            ->logOnlyDirty();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Customer Group has been {$eventName}";
    }

    /**
     * Relationships
     */
    public function customerGroupMembers()
    {
        return $this->hasMany(CustomerGroupMember::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', '0');
    }
}
