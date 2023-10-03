<?php

namespace App\Models\Connections;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitanBridges extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * @var string
     */
    protected $table = 'titan_bridges_static';

    /**
     * @var bool
     */
    public $timestamps = false;
}
