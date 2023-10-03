<?php

namespace App\Models;

use App\Models\SDE\SolarSystem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class ShortestPath extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $guarded = [];

    public function tapActivity(Activity $activity, string $eventName)
    {
        // dd($activity->properties);
        if ($eventName == 'updated') {
        }

        if ($eventName == 'created') {
            // if ($activity->properties['attributes']['log_helper'] == 16) {
            //     $activity->description = 'Requested Route';
            // }
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
            ->useLogName('ShortestRoute')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
            ])
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }


    /**
     * Get the user that owns the ShortestPath
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function startSystem(): BelongsTo
    {
        return $this->belongsTo(SolarSystem::class, 'start_system_id');
    }

    /**
     * Get the user that owns the ShortestPath
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function endSystem(): BelongsTo
    {
        return $this->belongsTo(SolarSystem::class, 'end_system_id');
    }

    /**
     * Get the user that owns the ShortestPath
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by_id');
    }
}
