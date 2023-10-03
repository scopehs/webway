<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mapDenormalize extends Model
{
    use HasFactory;

    public const BELT = 9;

    public const CONSTELLATION = 4;

    public const MOON = 8;

    public const PLANET = 7;

    public const REGION = 3;

    public const STATION = 15;

    public const SUN = 6;

    public const SYSTEM = 5;

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
    protected $table = 'mapDenormalize';

    /**
     * @var string
     */
    protected $primaryKey = 'itemID';

    /**
     * @return int
     */
    public function getStructureIdAttribute()
    {
        return $this->structure_id;
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->itemName;
    }

    /**
     * @return bool
     */
    public function isConstellation(): bool
    {
        return $this->groupID === self::CONSTELLATION;
    }

    /**
     * @return bool
     */
    public function isRegion(): bool
    {
        return $this->groupID === self::REGION;
    }

    /**
     * @return bool
     */
    public function isSystem(): bool
    {
        return $this->groupID === self::SYSTEM;
    }

    /**
     * @return bool
     */
    public function isSun(): bool
    {
        return $this->groupID === self::SUN;
    }

    /**
     * @return bool
     */
    public function isPlanet(): bool
    {
        return $this->groupID === self::PLANET;
    }

    /**
     * @return bool
     */
    public function isMoon(): bool
    {
        return $this->groupID === self::MOON;
    }
}
