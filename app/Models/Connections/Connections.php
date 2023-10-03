<?php

namespace App\Models\Connections;

use App\Models\Scanning\Signature;
use App\Models\SDE\SolarSystem;
use App\Models\SigNote;
use App\Models\SystemSystemType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Connections extends Model
{
    use HasFactory;use LogsActivity;

    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName == 'updated') {
            $reservedNew = $activity->properties['attributes']['reserved'];
            $reservedOld = $activity->properties['old']['reserved'];

            $reportCountNew = $activity->properties['attributes']['report_count'];
            $reportCountOld = $activity->properties['old']['report_count'];

            if ($reservedNew != $reservedOld) {
                if ($reservedNew == 1) {
                    $activity->description = 'Reserved Connection';
                } else {
                    $activity->description = 'Remove Reserved from Connection';
                }
            }

            if ($reportCountNew != $reportCountOld) {
                $activity->description = 'Reported Connection as Gone';
            }
        }

        if ($eventName == 'created') {
            if ($activity->properties['attributes']['log_helper'] == 9) {
                $activity->description = 'Created Connection';
            }

            if ($activity->properties['attributes']['log_helper'] == 34) {
                $activity->description = 'Added Whale Connection';
            }

            if ($activity->properties['attributes']['log_helper'] == 21) {
                $activity->description = 'Updated Jump Bridges';
            }

            if ($activity->properties['attributes']['log_helper'] == 1) {
                $activity->description = 'Added Drifter Hole';
            }
        }

        if ($eventName == 'deleted') {
            $activity->description = 'Archived Connection';
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'source_sig_id',
                'target_sig_id',
                'source_system_id',
                'target_system_id',
                'type',
                'delete_flag',
                'completed_user_id',
                'reserved',
                'reserved_by_user_id',
                'report_count',
                'reservedBy.id',
                'reservedBy.name',
                'log_helper',
            ])
            ->useLogName('Connections')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
                'jabber_ping',
            ]);
        // Chain fluent methods for configuration options
    }

    protected $guarded = [];

    /**
     * @var string
     */
    protected $table = 'connections';

    public function sourceSig()
    {
        return $this->belongsTo(Signature::class, 'source_sig_id', 'id');
    }

    public function reservedBy()
    {
        return $this->belongsTo(User::class, 'reserved_by_user_id', 'id');
    }

    public function targetSig()
    {
        return $this->belongsTo(Signature::class, 'target_sig_id', 'id');
    }

    public function Notes()
    {
        return $this->hasMany(SigNote::class);
    }

    public function sourceSystem()
    {
        return $this->belongsTo(SolarSystem::class, 'source_system_id', 'system_id');
    }

    public function targetSystem()
    {
        return $this->belongsTo(SolarSystem::class, 'target_system_id', 'system_id');
    }

    public function type()
    {
        return $this->belongsTo(ConnectionTypes::class, 'type', 'id');
    }

    public function systemTypeTarget()
    {
        return $this->hasOne(SystemSystemType::class, 'system_id', 'target_system_id');
    }

    public function systemTypeSource()
    {
        return $this->hasOne(SystemSystemType::class, 'system_id', 'source_system_id');
    }

    // Connections (System_id) -> System System Types - > system_type_id
}
