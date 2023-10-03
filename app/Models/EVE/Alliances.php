<?php

namespace App\Models\EVE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alliances extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'alliances';

    // Because i'm lazy and cba with $fillable
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function corporations()
    {
        return $this->hasMany(Corporations::class);
    }
}
