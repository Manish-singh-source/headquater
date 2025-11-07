<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Permission;

class PermissionGroup extends Model
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
            ->logOnly(['name', 'description', 'status'])
            ->logOnlyDirty();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Permission Group has been {$eventName}";
    }

    /**
     * Relationships
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'permission_group_id');
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

