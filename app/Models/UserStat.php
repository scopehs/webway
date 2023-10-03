<?php

namespace App\Models;

use App\Models\EVE\ESITokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStat extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'stats' => 'array',
    ];

    protected $attributes = [

        'stats' => '{
           "connectionsAll": 0,
           "connectionsDone": 0,
           "connectionsPart": 0,
           "sigsAll": 0,
           "sigsDoneAll": 0,
           "sigsPartAll": 0,
           "sigsAllWormholes": 0,
           "sigsDoneWormholes": 0,
           "sigsPartWormholes": 0,
           "sigsAllRelic": 0,
           "sigsDoneRelic": 0,
           "sigsPartRelic": 0,
           "sigsAllData": 0,
           "sigsDoneData": 0,
           "sigsPartData": 0,
           "sigsAllGas": 0,
           "sigsDoneGas": 0,
           "sigsPartGas": 0,
           "sigsAllCombat": 0,
           "sigsDoneCombat": 0,
           "sigsPartCombat": 0,
           "sigsAllOre": 0,
           "sigsDoneOre": 0,
           "sigsPartOre": 0,
           "sigsAllUnknown": 0


        }',
    ];

    public function esi_tokens()
    {
        return $this->hasMany(ESITokens::class, 'user_id', 'user_id');
    }
}
