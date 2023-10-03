<?php

namespace App\Models\EVE;

use App\Models\SDE\InvType;
use App\Models\SDE\SolarSystem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Characters extends Model
{
    use HasFactory;use LogsActivity;

    public $incrementing = false;

    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName == 'created') {
            $activity->description = 'Added ESI';
        }

        if ($eventName == 'updated') {
            $trackingNew = $activity->properties['attributes']['tracking'];
            $trackingOld = $activity->properties['old']['tracking'];

            if ($trackingNew != $trackingOld) {
                if ($trackingNew == 1) {
                    $activity->description = 'Tracking On';
                } else {
                    $activity->description = 'Tracking Off';
                }
            }
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'name',
                'user_id',
                'tracking',
                'updated_at',
                'user.name',
                'user.id',
            ])
            ->useLogName('Character')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
                'last_system_id',
                'current_system_id',
                'corporation_id',
                'alliace_id',
                'online',
            ]);
        // Chain fluent methods for configuration options
    }

    /**
     * @var string
     */
    protected $table = 'characters';

    // Because i'm lazy and cba with $fillable
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function corporation()
    {
        return $this->hasOne(Corporations::class, 'id', 'corporation_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function alliance()
    {
        return $this->hasOne(Alliances::class, 'id', 'alliance_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currentSystem()
    {
        return $this->belongsTo(SolarSystem::class, 'current_system_id', 'system_id');
    }

    public function lastSystem()
    {
        return $this->belongsTo(SolarSystem::class, 'last_system_id', 'system_id');
    }

    public function chars()
    {
        return $this->hasMany(ESITokens::class);
    }

    public function shipType()
    {
        return $this->belongsTo(InvType::class, 'ship_type_id', 'typeID');
    }

    public function esiChar()
    {
        return $this->hasOne(ESITokens::class, 'character_id', 'id');
    }
}
