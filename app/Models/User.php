<?php

namespace App\Models;

use App\Models\Connections\Connections;
use App\Models\EVE\ESITokens;
use App\Models\Scanning\Signature;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;

class User extends Authenticatable
{
    use LaravelPermissionToVueJS;
    use Notifiable;
    use HasRoles;
    use HasPermissions;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use LogsActivity;
    use CausesActivity;

    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName == 'updated') {
            $onlineNew = $activity->properties['attributes']['online'];
            $onlineOld = $activity->properties['old']['online'];

            if ($onlineNew != $onlineOld) {
                if ($onlineNew == 0) {
                    $activity->description = 'Logged Off';
                } else {
                    $activity->description = 'Logged In';
                }
            }
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'name',
                'online',
                'updated_at',
            ])
            ->useLogName('User')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
                'token',
                'history',
                'pri_grp',
                'system_id',
                'character_id',
                'remember_token',
                'main_character_id',
            ]);
        // Chain fluent methods for configuration options
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    // ''
    // protected $appends = ['totalisk', 'totaliskhistory'];

    /**
     * The attributes that should be cast to native types HI THERE.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pri_grp' => 'integer',
    ];

    public function getTotalIskAttribute()
    {
        $pull = SiteSetting::where('id', 1)->first();
        $pricePerGas = $pull->settings['pay_gas_amount'];
        $addGas = $pull->settings['pay_gas'];
        $data = $this->statsCurrent;
        $connection = $data->stats['connectionsDone'];
        $connectionTotal = floor($connection / 25) * 100000000;
        if ($connectionTotal > 4000000000) {
            $connectionTotal = 4000000000;
        }
        if ($addGas) {
            $gas = $data->stats['sigsDoneGas'];
            $gasTotal = $gas * $pricePerGas;
            $total = $connectionTotal + $gasTotal;
        } else {
            $total = $connectionTotal;
        }

        return $total;
    }

    public function getTotalIskHistoryAttribute()
    {
        $pull = SiteSetting::where('id', 1)->first();
        $pricePerGas = $pull->settings['pay_gas_amount'];
        $addGas = $pull->settings['pay_gas'];
        $data = $this->statsLastMonth;
        $connection = $data->stats['connectionsDone'];
        $connectionTotal = floor($connection / 25) * 100000000;
        if ($connectionTotal > 4000000000) {
            $connectionTotal = 4000000000;
        }
        if ($addGas) {
            $gas = $data->stats['sigsDoneGas'];
            $gasTotal = $gas * $pricePerGas;
            $total = $connectionTotal + $gasTotal;
        } else {
            $total = $connectionTotal;
        }

        return $total;
    }

    public function feedback()
    {
        return $this->hasMany(FeedBack::class);
    }

    public function statsStart()
    {
        return $this->hasMany(UserStat::class);
    }

    public function statsCurrent()
    {
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;

        return $this->hasOne(UserStat::class)->ofMany(['id' => 'max'], function ($query) use ($month, $year) {
            $query->where('year', $year)->where('month', $month);
        });
    }

    public function statsLastMonth()
    {
        $now = Carbon::now()->subMonth();
        $month = $now->month;
        $year = $now->year;

        return $this->hasOne(UserStat::class)->ofMany(['id' => 'max'], function ($query) use ($month, $year) {
            $query->where('year', $year)->where('month', $month);
        });
    }

    public function esi_tokens()
    {
        return $this->hasMany(ESITokens::class);
    }

    public function ratingMade()
    {
        return $this->hasMany(ConnectionRating::class);
    }

    public function ratingAbout()
    {
        return $this->hasManyThrough(ConnectionRating::class, Connections::class, 'completed_user_id', 'connection_id', 'id', 'id');
    }

    public function connectionsMade()
    {
        return $this->hasManyThrough(Connections::class, Signature::class, 'created_by_id', 'target_sig_id', 'id', 'id');
    }

    public function systemNotes()
    {
        return $this->hasMany(SystemNote::class);
    }

    public function sigNotes()
    {
        return $this->hasMany(Signature::class);
    }

    public function connectionsMadeHistory()
    {
        return $this->hasManyThrough(ConnectionHistory::class, SignatureHistory::class, 'created_by_id', 'target_sig_id', 'id', 'id');
    }

    public function sigsAll()
    {
        return $this->hasMany(Signature::class, 'created_by_id');
    }

    public function sigsAllCompleted()
    {
        return $this->hasMany(Signature::class, 'completed_by_id');
    }

    public function sigsAllHistory()
    {
        return $this->hasMany(SignatureHistory::class, 'created_by_id');
    }

    public function sigsAllHistoryCompleted()
    {
        return $this->hasMany(SignatureHistory::class, 'completed_by_id');
    }

    public function connectionsCompleted()
    {
        return $this->hasMany(Connections::class, 'completed_user_id');
    }

    public function connectionHistoriesCompleted()
    {
        return $this->hasMany(ConnectionHistory::class, 'completed_user_id');
    }

    public function savedRoutes()
    {
        return $this->belongsToMany(SavedRoute::class, 'saved_route_users');
    }

    public function logs()
    {
        return $this->morphMany(ActivityLog::class, 'causer');
    }

    public function getAllPermissionsAttribute()
    {
        $permissions = [];
        foreach (Permission::all() as $permission) {

            //   dd($name);
            if (Auth::user()->can($permission->name)) {
                $permissions[] = $permission->name;
            }
        }

        return $permissions;
    }

    public function getAllRolesAttribute()
    {
        $roles = [];
        foreach (Role::all() as $role) {

            //   dd($name);
            if (Auth::user()->can($role->name)) {
                $roles[] = $role->name;
            }
        }

        return $roles;
    }

    public function getCheckPermissions()
    {
        return checkPermissions();
    }
}
