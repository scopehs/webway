<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiviyNameTypes extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function logs()
    {
        return $this->hasMany(ActivityLog::class, 'name_id', 'id');
    }
}
