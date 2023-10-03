<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedRouteUser extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function user()
    {
        return  $this->belongsTo(User::class);
    }

    public function SavedRoute()
    {
        return  $this->belongsTo(SavedRoute::class);
    }
}