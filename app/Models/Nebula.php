<?php

namespace App\Models;

use App\Models\Scanning\Signature;
use App\Models\SDE\Region;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nebula extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'damage_type',
    ];

    protected $casts = [
        'damage_type' => 'array',
    ];

    public function locations()
    {
        return $this->belongsToMany(Region::class, 'location_nebulas', 'nebula_id', 'location_id');
    }

    public function sigs()
    {
        return $this->hasMany(Signature::class, 'name', 'name')->where('delete', 0)->with(['solar_system']);
    }
}
