<?php

namespace App\Models;

use App\Models\SDE\SolarSystem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class SystemNote extends Model
{
    use HasFactory;use LogsActivity;

    protected $guarded = [];

    public function tapActivity(Activity $activity, string $eventName)
    {
        // dd($activity->properties);
        if ($eventName == 'updated') {
        }

        if ($eventName == 'created') {
            if ($activity->properties['attributes']['log_helper'] == 40) {
                $activity->description = 'Added System Note';
            }
        }

        if ($eventName == 'deleted') {
            if ($activity->properties['old']['log_helper'] == 41) {
                $activity->description = 'Deleted System Note';
            }
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('SystemNote')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
            ])
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    public function system()
    {
        return $this->belongsTo(SolarSystem::class, 'system_id', 'system_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
