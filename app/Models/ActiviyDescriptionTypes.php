<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiviyDescriptionTypes extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function logs()
    {
        return $this->hasMany(ActivityLog::class, 'description_id', 'id');
    }
}
