<?php

namespace App\Models\Connections;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectionTypes extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'connection_types';

    /**
     * @var bool
     */
    public $timestamps = false;

    // Because i'm lazy and cba with $fillable
    protected $guarded = [];
}
