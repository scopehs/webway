<?php

namespace App\Models\EVE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TranquiltyStatus extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'eve_status';

    // Because i'm lazy and cba with $fillable
    protected $guarded = [];
}
