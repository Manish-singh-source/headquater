<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CustomerGroupMember extends Model
{
    //
    use LogsActivity;
    
    /**
     * The function logs the activity of the user.
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Customer Group Member has been {$eventName}";
    }

    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function customerGroup()
    {
        return $this->hasOne(CustomerGroup::class, 'id', 'customer_group_id');
    }
}
