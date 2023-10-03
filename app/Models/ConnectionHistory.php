<?php

namespace App\Models;

use App\Models\Connections\ConnectionTypes;
use App\Models\SDE\SolarSystem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectionHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public $incrementing = false;

    public function sourceSig()
    {
        return $this->belongsTo(SignatureHistory::class, 'source_sig_id', 'id');
    }

    public function targetSig()
    {
        return $this->belongsTo(SignatureHistory::class, 'target_sig_id', 'id');
    }

    public function sourceSystem()
    {
        return $this->belongsTo(SolarSystem::class, 'source_system_id', 'system_id');
    }

    public function targetSystem()
    {
        return $this->belongsTo(SolarSystem::class, 'target_system_id', 'system_id');
    }

    public function type()
    {
        return $this->belongsTo(ConnectionTypes::class, 'type', 'id');
    }
}
