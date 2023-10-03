<?php

namespace App\Models\Wormholes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WormholeSpawns extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'wormhole_spawn_system_types';

    /**
     * @var bool
     */
    public $timestamps = false;
}
