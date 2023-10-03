<?php

namespace App\Models;

use App\Models\SDE\SolarSystem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function system()
    {
        return $this->belongsToMany(SolarSystem::class, 'SystemSystemType', 'system_type_id', 'system_id');
    }
}
