<?php

namespace App\Models\SDE;

use App\Models\EffectList;
use App\Models\EVE\Characters;
use App\Models\JoveSystems;
use App\Models\Scanning\Signature;
use App\Models\SystemNote;
use App\Models\SystemType;
use App\Models\Wormholes\WormholeType;
use App\Models\WormholeShattered;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="SolarSystem",
 *     description="SolarSystem model",
 *     @OA\Xml(
 *         name="SolarSystem"
 *     )
 * )
 */
class SolarSystem extends Model
{
    /**
     * @var bool
     */
    public $incrementing = false;

    public $timestamps = false;

    /**
     * @var string
     */
    protected $primaryKey = 'system_id';

    /**
     * @var string
     */
    protected $table = 'solar_systems';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function constellation()
    {
        return $this->belongsTo(Constellation::class, 'constellation_id', 'constellation_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function moons()
    {
        return $this->hasMany(Moon::class, 'system_id', 'system_id');
    }

    public function characters()
    {
        return $this->hasMany(Characters::class, 'current_system_id', 'system_id');
    }

    public function notes()
    {
        return $this->hasMany(Characters::class, 'system_id', 'system_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function planets()
    {
        return $this->hasMany(Planet::class, 'system_id', 'system_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    //public function sovereignty()
    //{
    //
    //    return $this->hasOne(SoveddddreigntyMap::class, 'system_id', 'system_id')
    //        ->withDefault();
    //}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function star()
    {
        return $this->hasOne(Star::class, 'system_id', 'system_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function signatures()
    {
        return $this->hasMany(Signature::class, 'system_id', 'system_id');
    }

    public function statics()
    {
        return $this->belongsToMany(WormholeType::class, 'wormhole_statics', 'system_id', 'wormhole_type_id', 'system_id', 'id');
    }

    public function systemType()
    {
        return $this->belongsToMany(SystemType::class, 'system_system_types', 'system_id', 'system_type_id', 'system_id');
    }

    public function effect()
    {
        return $this->belongsToMany(EffectList::class, 'system_effects', 'system_id', 'system_type', 'system_id');
    }

    public function jove()
    {
        return $this->hasOne(JoveSystems::class, 'system_id', 'system_id');
    }

    public function systemNotes()
    {
        return $this->hasMany(SystemNote::class, 'system_id', 'system_id');
    }

    public function shattered()
    {
        return $this->hasOne(WormholeShattered::class, 'sola_system_id', 'system_id');
    }
}
