<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogSnapShotNew extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'stats',
    ];

    protected $casts = [
        'stats' => 'array',
    ];
}
