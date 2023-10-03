<?php

namespace App\Models;

use App\Models\SDE\SolarSystem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class SavedRoute extends Model
{
    use HasFactory;use LogsActivity;

    protected $guarded = [];

    public function tapActivity(Activity $activity, string $eventName)
    {
        // dd($activity->properties);
        if ($eventName == 'updated') {
        }

        if ($eventName == 'created') {
            if ($activity->properties['attributes']['log_helper'] == 16) {
                $activity->description = 'Requested Route';
            }
        }

        if ($eventName == 'deleted') {
            // if ($activity->properties['old']['log_helper'] == 41) {
            //     $activity->description = "Deleted System Note";
            // }
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('SavedRoute')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
            ])
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    public function startSystem()
    {
        return $this->belongsTo(SolarSystem::class, 'start_system_id', 'system_id');
    }

    public function endSystem()
    {
        return $this->belongsTo(SolarSystem::class, 'end_system_id', 'system_id');
    }

    public function savedUsers()
    {
        return $this->belongsToMany(User::class, 'saved_route_users');
    }
}
