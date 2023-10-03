<?php

namespace App\Models;

use App\Models\SDE\SolarSystem;
use App\Models\Wormholes\WormholeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignatureHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function solar_system()
    {
        return $this->belongsTo(SolarSystem::class, 'system_id', 'system_id');
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

    public function targetConnection()
    {
        return $this->hasOne(ConnectionHistory::class, 'target_sig_id', 'id');
    }

    public function sourceConnection()
    {
        return $this->hasOne(ConnectionHistory::class, 'source_sig_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(SignatureGroup::class, 'signature_group_id', 'id');
    }
}
