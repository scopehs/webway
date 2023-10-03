<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EffectList extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function effects()
    {
        return $this->belongsTo(SystemEffect::class);
    }
}
