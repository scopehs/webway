<?php

namespace App\Jobs;

use App\Models\ActivityLogSnapShotNew;
use App\Models\ActivityLogSnapShotOld;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MoveOldSnapToNewSnapJob implements ShouldQueue
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
        $stat = ActivityLogSnapShotOld::where('id', $this->id)->first();
        $json = collect([]);

        $loop = 1;
        while ($loop <= 51) {
            $num = $stat[$loop];
            if ($num > 0) {
                switch ($loop) {
                    case 1:
                        $text = 'Added Drifter Hole';
                        break;
                    case 2:
                        $text = 'Added Leads to Sig';
                        break;
                    case 3:
                        $text = 'Added Leads to System';
                        break;

                    case 4:
                        $text = 'Added Sig';
                        break;

                    case 5:
                        $text = 'Added Sig ID';
                        break;

                    case 9:
                        $text = 'Created Connection';
                        break;

                    case 13:
                        $text = 'Logged In';
                        break;

                    case 16:
                        $text = 'Requested Route';
                        break;

                    case 18:
                        $text = 'Soft Deleted Sig';
                        break;

                    case 20:
                        $text = 'Update Sig';
                        break;

                    case 21:
                        $text = 'Updated Jump Bridges';
                        break;

                    case 22:
                        $text = 'Updated Jove System Info';
                        break;

                    case 24:
                        $text = 'Updated Sig Info';
                        break;

                    case 27:
                        $text = 'Added ESI';
                        break;

                    case 28:
                        $text = 'Reserved Connection';
                        break;

                    case 29:
                        $text = 'Added Hot Area';
                        break;

                    case 30:
                        $text = 'Removed Hot Area';
                        break;

                    case 31:
                        $text = 'Cleared Whale Sig';
                        break;

                    case 32:
                        $text = 'Deleted Whale Connection';
                        break;

                    case 33:
                        $text = 'Added Whale Sig';
                        break;

                    case 34:
                        $text = 'Added Whale Connection';
                        break;

                    case 35:
                        $text = 'Reported Sig Gone';
                        break;

                    case 36:
                        $text = 'Archived Connection';
                        break;

                    case 37:
                        $text = 'Archived Sig';
                        break;

                    case 39:
                        $text = 'Remove Reserved from Connection';
                        break;

                    case 40:
                        $text = 'Added System Note';
                        break;

                    case 41:
                        $text = 'Deleted System Note';
                        break;

                    case 42:
                        $text = 'Added Sig Notes';
                        break;

                    case 43:
                        $text = 'Deleted Sig Notes';
                        break;

                    case 44:
                        $text = 'Added Connection Note';
                        break;

                    case 45:
                        $text = 'Delete Connection Note';
                        break;

                    case 46:
                        $text = 'Archived Connection';
                        break;

                    case 47:
                        $text = 'Reported Connection as Gone';
                        break;

                    case 48:
                        $text = 'Reserved Sig';
                        break;

                    case 49:
                        $text = 'Un-Reserved Sig';
                        break;

                    case 50:
                        $text = 'Reserved Gas Site';
                        break;

                    case 51:
                        $text = 'Un-Reserved Gas Site';
                        break;
                }

                $new = $json->put($text, $num);
            }
            $loop++;
        }

        $newSnap = new ActivityLogSnapShotNew();
        $newSnap->id = $stat->id;
        $newSnap->timestamps = false;
        $newSnap->stats = $new ?? null;
        $newSnap->created_at = $stat->created_at;
        $newSnap->updated_at = $stat->updated_at;
        $newSnap->save();
    }
}
