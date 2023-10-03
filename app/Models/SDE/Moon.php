<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class Moon extends Model
{
    public const UBIQUITOUS = 2396;

    public const COMMON = 2397;

    public const UNCOMMON = 2398;

    public const RARE = 2400;

    public const EXCEPTIONAL = 2401;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $primaryKey = 'moon_id';

    /**
     * @var string
     */
    protected $table = 'moons';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function constellation()
    {
        return $this->belongsTo(Constellation::class, 'constellation_id', 'constellation_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function planet()
    {
        return $this->belongsTo(Planet::class, 'planet_id', 'planet_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function solar_system()
    {
        return $this->belongsTo(SolarSystem::class, 'system_id', 'system_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function star()
    {
        return $this->belongsTo(Star::class, 'system_id', 'system_id');
    }
}
