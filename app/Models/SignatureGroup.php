<?php

namespace App\Models;

use App\Models\Scanning\Signature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignatureGroup extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function sigs()
    {
        return $this->hasMany(Signature::class);
    }
}
