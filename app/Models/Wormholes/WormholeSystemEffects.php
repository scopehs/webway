<?php

namespace App\Models\Wormholes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WormholeSystemEffects extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'system_effects';

    /**
     * @var bool
     */
    public $timestamps = false;
}
