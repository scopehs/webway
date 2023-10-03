<?php

namespace App\Models\Connections;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlopsBridges extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * @var string
     */
    protected $table = 'blop_bridge_static';

    /**
     * @var bool
     */
    public $timestamps = false;
}
