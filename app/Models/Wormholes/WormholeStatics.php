<?php

namespace App\Models\Wormholes;

use App\Models\BrokenStaticClaim;
use App\Models\Scanning\Signature;
use App\Models\SDE\SolarSystem;
use App\Models\SystemSystemType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WormholeStatics extends Model
{
    use HasFactory;
    protected $guarded = [];


    /**
     * @var string
     */
    protected $table = 'wormhole_statics';

    /**
     * @var bool
     */
    public $timestamps = false;

    public function staticType()
    {
        return $this->belongsTo(WormholeType::class, 'wormhole_type_id');
    }

    // public function signature()
    // {
    //     return $this->hasOne(Signature::class, 'system_id', 'system_id')
    //         ->where('type', $this->wormhole_type_id)
    //         ->where('delete', 0);
    // }

    public function signature()
    {
        return $this->belongsTo(Signature::class);
    }

    public function system()
    {
        return $this->belongsTo(SolarSystem::class, 'system_id', 'system_id');
    }


    public function systemTypeCheck()
    {
        return $this->belongsTo(SystemSystemType::class, 'system_id', 'system_id');
    }

    public function claim()
    {
        return $this->hasOne(BrokenStaticClaim::class);
    }

    protected $casts = [
        'wormhole_type_id' => 'integer'
    ];
}
