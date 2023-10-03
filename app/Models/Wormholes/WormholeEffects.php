<?php

namespace App\Models\Wormholes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WormholeEffects extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'wormhole_effects';

    /**
     * @var bool
     */
    public $timestamps = false;
}
