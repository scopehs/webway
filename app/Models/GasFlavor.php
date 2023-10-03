<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GasFlavor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function nebulas()
    {
        return $this->belongsToMany(Nebula::class, 'flavor_nebulas');
    }
}
