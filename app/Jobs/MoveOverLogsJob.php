<?php

namespace App\Jobs;

use App\Models\ActivityLogOld;
use App\Models\ActiviyDescriptionTypes;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Activitylog\Models\Activity;

class MoveOverLogsJob implements ShouldQueue
{
    use Dispatchable;use InteractsWithQueue;use Queueable;use SerializesModels;

    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // created
        // deleted
        // updated
        //

        $oldLog = ActivityLogOld::where('id', $this->id)->first();
        $oldDis = ActiviyDescriptionTypes::where('id', $oldLog->description_id)->first();
        $oldDescriptionName = $oldDis->name;

        switch ($oldLog->description_id) {
            case 1:
                $event = 'created';
                break;
            case 2:
                $event = 'updated';
                break;
            case 3:
                $event = 'updated';
                break;
            case 4:
                $event = 'created';
                break;
            case 5:
                $event = 'updated';
                break;
            case 9:
                $event = 'created';
                break;
            case 13:
                $event = 'updated';
                break;
            case 16:
                $event = 'created';
                break;
            case 18:
                $event = 'deleted';
                break;
            case 20:
                $event = 'updated';
                break;
            case 21:
                $event = 'updated';
                break;
            case 22:
                $event = 'created';
                break;
            case 24:
                $event = 'updated';
                break;
            case 27:
                $event = 'created';
                break;
            case 28:
                $event = 'updated';
                break;
            case 29:
                $event = 'created';
                break;
            case 30:
                $event = 'deleted';
                break;
            case 31:
                $event = 'updated';
                break;
            case 32:
                $event = 'deleted';
                break;
            case 33:
                $event = 'created';
                break;
            case 34:
                $event = 'created';
                break;
            case 35:
                $event = 'updated';
                break;
            case 36:
                $event = 'deleted';
                break;
            case 37:
                $event = 'deleted';
                break;
            case 39:
                $event = 'deleted';
                break;
            case 40:
                $event = 'created';
                break;
            case 41:
                $event = 'deleted';
                break;
            case 42:
                $event = 'created';
                break;
            case 43:
                $event = 'deleted';
                break;
            case 44:
                $event = 'created';
                break;
            case 45:
                $event = 'deleted';
                break;
            case 46:
                $event = 'deleted';
                break;
            case 47:
                $event = 'updated';
                break;
            case 48:
                $event = 'updated';
                break;
            case 49:
                $event = 'updated';
                break;
            case 50:
                $event = 'updated';
                break;
            case 51:
                $event = 'updated';
                break;

            default:
                $event = null;
        }

        switch ($oldLog->subject_type) {
            case "App\Models\ConnectionHistory":
                $log_name = 'ConnectionHistory';
                break;
            case "App\Models\SavedRoute":
                $log_name = 'SavedRoute';
                break;
            case "App\Models\SignatureHistory":
                $log_name = 'SignatureHistory';
                break;
            case "App\Models\JoveSystems":
                $log_name = 'JoveSystems';
                break;
            case "App\Models\Connections\Connections":
                $log_name = 'Connections';
                break;
            case "App\Models\HotArea":
                $log_name = 'HotArea';
                break;
            case "App\Models\Scanning\Signature":
                $log_name = 'Signature';
                break;
            case "App\Models\User":
                $log_name = 'User';
                break;
            case "App\Models\EVE\Characters":
                $log_name = 'Characters';
                break;
            case "App\Models\SystemNote":
                $log_name = 'SystemNote';
                break;
            case "App\Models\SigNote":
                $log_name = 'SigNote';
                break;
            case "App\Models\ConnectionRating":
                $log_name = 'ConnectionRating';
                break;
            case "App\Models\ReserveSig":
                $log_name = 'ReserveSig';
                break;

            default:
                $log_name = null;
        }

        $newLog = new Activity();
        $newLog->id = $oldLog->id;
        $newLog->log_name = $log_name;
        $newLog->description = $oldDescriptionName;
        $newLog->subject_type = $oldLog->subject_type;
        $newLog->event = $event;
        $newLog->subject_id = $oldLog->subject_id;
        $newLog->causer_type = $oldLog->causer_type;
        $newLog->causer_id = $oldLog->causer_id;
        $newLog->created_at = $oldLog->created_at;
        $newLog->updated_at = $oldLog->updated_at;
        $newLog->save();
    }
}
