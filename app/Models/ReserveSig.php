<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class ReserveSig extends Model
{
    use HasFactory;use LogsActivity;

    protected $guarded = [];

    public function tapActivity(Activity $activity, string $eventName)
    {
        // dd($activity->properties);
        if ($eventName == 'updated') {
        }

        if ($eventName == 'created') {
            if ($activity->properties['attributes']['log_helper'] == 50) {
                $activity->description = 'Reserved Gas Site';
            }

            if ($activity->properties['attributes']['log_helper'] == 48) {
                $activity->description = 'Reserved Sig';
            }
        }

        if ($eventName == 'deleted') {
            if ($activity->properties['old']['log_helper'] == 51) {
                $activity->description = 'Un-Reserved Gas Site';
            }

            if ($activity->properties['old']['log_helper'] == 49) {
                $activity->description = 'Un-Reserved Sig';
            }
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('ReserveSig')
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
