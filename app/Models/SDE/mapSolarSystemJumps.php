<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mapSolarSystemJumps extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'mapSolarSystemJumps';

    /**
     * @var bool
     */
    public $incrementing = false;
}
