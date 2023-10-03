<?php

namespace App\Models\RoutingStatic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutingStaticData extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'routing_static';

    /**
     * @var bool
     */
    public $timestamps = false;

    // Because i'm lazy and cba with $fillable
    protected $guarded = [];
}
