<?php

namespace App\Models;

use App\Models\SDE\Constellation;
use App\Models\SDE\Region;
use App\Models\SDE\SolarSystem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class HotArea extends Model
{
    use HasFactory;use LogsActivity;

    public $timestamps = false;

    protected $guarded = [];

    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName == 'updated') {

            // if ($activity->properties['attributes']['log_helper'] == 20) {
            //     $activity->description = "Update Sig";
            // }
        }

        if ($eventName == 'created') {
            if ($activity->properties['attributes']['log_helper'] == 29) {
                $activity->description = 'Added Hot Area';
            }
        }

        if ($eventName == 'deleted') {
            if ($activity->properties['old']['log_helper'] == 30) {
                $activity->description = 'Removed Hot Area';
            }
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('HotArea');

        // Chain fluent methods for configuration options
    }

    public function system()
    {
        return $this->belongsTo(SolarSystem::class, 'system_id', 'system_id');
    }

    public function constellation()
    {
        return $this->belongsTo(Constellation::class, 'constellation_id', 'constellation_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }
}
