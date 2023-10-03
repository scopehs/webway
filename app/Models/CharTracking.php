<?php

namespace App\Models;

use App\Models\EVE\Characters;
use App\Models\SDE\InvType;
use App\Models\SDE\SolarSystem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharTracking extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function character()
    {
        return $this->belongsTo(Characters::class);
    }

    public function currentSystem()
    {
        return $this->belongsTo(SolarSystem::class, 'current_system_id', 'system_id');
    }

    public function lastSystem()
    {
        return $this->belongsTo(SolarSystem::class, 'last_system_id', 'system_id');
    }

    public function currentSystemType()
    {
        return $this->hasManyThrough(SystemType::class, 'system_system_types', 'system_type_id', 'current_system_id', 'id');
    }

    public function lastSystemType()
    {
        return $this->hasManyThrough(SystemType::class, 'system_system_types', 'system_type_id', 'last_system_id', 'id');
    }

    public function ship()
    {
        return $this->belongsTo(InvType::class, 'ship_type_id', 'typeID');
    }
}
