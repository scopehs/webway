<?php

namespace App\Models\Wormholes;

use App\Models\Scanning\Signature;
use App\Models\SystemType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WormholeType extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'wormhole_types';

    protected $casts = [
        'life' => 'integer',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function signatures()
    {
        return $this->hasMany(Signature::class, 'id', 'type');
    }

    public function type()
    {
        return $this->belongsTo(SystemType::class, 'leads_to', 'id');
    }

    public function statics()
    {
        return $this->hasMany(WormholeStatics::class, 'wormhole_type_id');
    }
}
