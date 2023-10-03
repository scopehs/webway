<?php

namespace App\Models;

use App\Models\Scanning\Signature;
use App\Models\SDE\SolarSystem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class JoveSystems extends Model
{
    use HasFactory;use LogsActivity;

    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName == 'updated') {
            if ($activity->properties['attributes']['log_helper'] == 52) {
                $activity->description = 'CheckedWhalersSystem';
            }
        }

        if ($eventName == 'created') {

            // if ($activity->properties['attributes']['log_helper'] == 29) {
            //     $activity->description = "Added Hot Area";
            // }

            if ($activity->properties['attributes']['log_helper'] == 22) {
                $activity->description = 'Updated Jove System Info';
            }
        }

        if ($eventName == 'deleted') {
            //     if ($activity->properties['attributes']['log_helper'] == 30) {
            //         $activity->description = "Removed Hot Area";
            //     }
            // }
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('JoveSystem');

        // Chain fluent methods for configuration options
    }

    /**
     * @var string
     */
    protected $table = 'jove_present_system';

    /**
     * @var bool
     */
    public $timestamps = false;

    public function system()
    {
        return $this->hasOne(SolarSystem::class, 'system_id', 'system_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function sigs()
    {
        return $this->hasMany(Signature::class, 'system_id', 'system_id');
    }

    // Because i'm lazy and cba with $fillable
    protected $guarded = [];
}
