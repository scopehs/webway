<?php

namespace App\Models;

use App\Models\Connections\Connections;
use App\Models\Scanning\Signature;
use App\Models\SDE\SolarSystem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class SigNote extends Model
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
            if ($activity->properties['attributes']['log_helper'] == 42) {
                $activity->description = 'Added Sig Notes';
            }
        }

        if ($eventName == 'deleted') {
            if ($activity->properties['old']['log_helper'] == 51) {
                $activity->description = 'Deleted Sig Notes';
            }
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('SigNote')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
            ])
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    public function sig()
    {
        return $this->belongsTo(Signature::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function connection()
    {
        return $this->belongsTo(Connections::class);
    }

    public function system()
    {
        return $this->belongsTo(SolarSystem::class, 'system_id', 'system_id');
    }
}
