<?php

namespace App\Models\Scanning;

use App\Models\ActivityLog;
use App\Models\BrokenConnectionClaim;
use App\Models\Connections\Connections;
use App\Models\ReserveSig;
use App\Models\SDE\SolarSystem;
use App\Models\SignatureGroup;
use App\Models\SigNote;
use App\Models\SystemSystemType;
use App\Models\WormholeInfoLeadsTo;
use App\Models\WormholeInfoMass;
use App\Models\WormholeInfoShipSize;
use App\Models\WormholeInfoTimeTillDeath;
use App\Models\Wormholes\WormholeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Signature extends Model
{
    use HasFactory;
    use LogsActivity;

    /**
     * @var string
     */
    protected $table = 'signatures';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName == 'updated') {
            $leadsToNew = $activity->properties['attributes']['leads_to'];
            $leadsToOld = $activity->properties['old']['leads_to'];

            if ($leadsToNew != $leadsToOld) {
                if ($leadsToNew != null) {
                    $activity->description = 'Added Leads to System';
                }
            }

            if ($activity->properties['attributes']['log_helper'] == 20) {
                $activity->description = 'Update Sig';
            }

            if ($activity->properties['attributes']['log_helper'] == 24) {
                $activity->description = 'Updated Sig Info';
            }

            if ($activity->properties['attributes']['log_helper'] == 5) {
                $activity->description = 'Added Sig ID';
            }

            if ($activity->properties['attributes']['log_helper'] == 35) {
                $activity->description = 'Reported Sig Gone';
            }

            if ($activity->properties['attributes']['log_helper'] == 18) {
                $activity->description = 'Soft Deleted Sig';
            }
        }

        if ($eventName == 'created') {
            if ($activity->properties['attributes']['log_helper'] == 2) {
                $activity->description = 'Added Leads to Sig';
            }

            if ($activity->properties['attributes']['log_helper'] == 4) {
                $activity->description = 'Added Sig';
            }

            if ($activity->properties['attributes']['log_helper'] == 33) {
                $activity->description = 'Added Whale Sig';
            }
        }

        if ($eventName == 'deleted') {
            if ($activity->properties['old']['log_helper'] == 31) {
                $activity->description = 'Cleared Whale Sig';
            }

            if ($activity->properties['old']['log_helper'] == 32) {
                $activity->description = 'Deleted Whale Connection';
            }

            if ($activity->properties['old']['log_helper'] == 37) {
                $activity->description = 'Archived Sig';
            }
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'signature_id',
                'name_id',
                'system_id',
                'type',
                'signature_group_id',
                'name',
                'leads_to',
                'connection_id',
                'signal_strength',
                'life_time',
                'life_left',
                'delete',
                'created_by_id',
                'created_by_name',
                'modified_by_id',
                'modified_by_name',
                'created_at',
                'updated_at',
                'report_count',
                'completed_by_id',
                'completed_by_name',
                'log_helper',
            ])
            ->useLogName('Signature')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
                'router_link',
                'route_link_p',
                'jumps',
                'jumps_p',
                'jabber_ping',
            ]);
        // Chain fluent methods for configuration options
    }

    public function solar_system()
    {
        return $this->belongsTo(SolarSystem::class, 'system_id', 'system_id');
    }

    public function reserve()
    {
        return $this->hasMany(ReserveSig::class);
    }

    public function linked_solar_system()
    {
        return $this->belongsTo(SolarSystem::class, 'leads_to', 'system_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wormhole_type()
    {
        return $this->belongsTo(WormholeType::class, 'type', 'id');
    }

    public function wormholeInfoLeadsTo()
    {
        return $this->belongsTo(WormholeInfoLeadsTo::class);
    }

    public function wormholeInfoMass()
    {
        return $this->belongsTo(WormholeInfoMass::class);
    }

    public function wormholeInfoShipSize()
    {
        return $this->belongsTo(WormholeInfoShipSize::class);
    }

    public function wormholeInfoTimeTillDeath()
    {
        return $this->belongsTo(WormholeInfoTimeTillDeath::class);
    }

    public function sourceConnection()
    {
        return $this->hasOne(Connections::class, 'source_sig_id', 'id');
    }

    public function targetConnection()
    {
        return $this->hasOne(Connections::class, 'target_sig_id', 'id');
    }

    public function notes()
    {
        return $this->hasMany(SigNote::class);
    }

    public function group()
    {
        return $this->belongsTo(SignatureGroup::class, 'signature_group_id', 'id');
    }

    public function logs()
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    public function brokenClaim()
    {
        return $this->hasOne(BrokenConnectionClaim::class);
    }

    public function systemTypeCheck()
    {
        return $this->belongsTo(SystemSystemType::class, 'system_id', 'system_id');
    }

    public function nextSystemSigs()
    {
        return $this->hasMany(Signature::class, 'system_id', 'leads_to');
    }

    // Because i'm lazy and cba with $fillable
    protected $guarded = [];
}
