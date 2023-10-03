<?php

namespace App\Models;

use App\Models\Connections\Connections;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class ConnectionRating extends Model
{
    use HasFactory;use LogsActivity;

    protected $guarded = [];

    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName == 'updated') {

            // if ($activity->properties['attributes']['log_helper'] == 20) {
            //     $activity->description = "Update Sig";
            // }
        }

        if ($eventName == 'created') {
            if ($activity->properties['attributes']['log_helper'] == 44) {
                $activity->description = 'Added Connection Note';
            }
        }

        if ($eventName == 'deleted') {
            if ($activity->properties['old']['log_helper'] == 45) {
                $activity->description = 'Delete Connection Note';
            }
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->useLogName('ConnectionRating')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
            ]);
        // Chain fluent methods for configuration options
    }

    public function userMadeby()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function connection()
    {
        return $this->belongsTo(Connections::class);
    }

    public function userAbout()
    {
        return $this->hasOneThrough(User::class, Connections::class, 'completed_user_id', 'connection_id', 'id', 'id');
    }
}
