<?php

namespace App\Models\EVE;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class ESITokens extends Model
{
    use HasFactory;use LogsActivity;

    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName == 'updated') {
            if ($activity->properties['attributes']['log_helper'] == 27) {
                $activity->description = 'Added ESI';
            }
        }

        if ($eventName == 'created') {
            if ($activity->properties['attributes']['log_helper'] == 27) {
                $activity->description = 'Added ESI';
            }
        }

        if ($eventName == 'deleted') {
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'character_id',
                'name',
                'log_helper',
            ])
            ->useLogName('Character')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
                'avatar',
                'token',
                'refresh_token',
                'owner_hash',
                'active',
                'tracking',
                'created_at',
                'token_refresh',
            ]);
        // Chain fluent methods for configuration options
    }

    /**
     * @var string
     */
    protected $table = 'esi_tokens';

    // Because i'm lazy and cba with $fillable
    protected $guarded = [];

    protected $hidden = [
        'token', 'refresh_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function character()
    {
        return $this->hasMany(Characters::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
