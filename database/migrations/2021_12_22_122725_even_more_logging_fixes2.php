<?php

use App\Models\ActivityLog;
use App\Models\ActiviyDescriptionTypes;
use App\Models\Connections\Connections;
use App\Models\Scanning\Signature;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EvenMoreLoggingFixes2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $logs = ActivityLog::get();

        // foreach ($logs as $log) {
        //     if ($log->subject_type_old == 1) {
        //         $log->delete();
        //     }

        //     if ($log->subject_type_old == 2) {
        //         $log->update(['subject_type' => "App\Models\Scanning\Signature"]);
        //     }

        //     if ($log->subject_type_old == 3) {
        //         $log->update(['subject_type' => "App\Models\EVE\Characters"]);
        //     }

        //     if ($log->subject_type_old == 4) {
        //         $log->update(['subject_type' => "App\Models\Connections\Connections"]);
        //     }

        //     if ($log->subject_type_old == 5) {
        //         $log->update(['subject_type' => "App\Models\JoveSystems"]);
        //     }

        //     if ($log->subject_type_old == 6) {
        //         $log->update(['subject_type' => "App\Models\Connections\Connections"]);
        //     }

        //     if ($log->subject_type_old == 7) {
        //         $log->update(['subject_type' => "App\Models\SavedRoute"]);
        //     }

        //     if ($log->subject_type_old == 8) {
        //         $log->update(['subject_type' => "App\Models\Scanning\Signature"]);
        //     }

        //     if ($log->subject_type_old == 9) {
        //         $log->delete();
        //     }

        //     if ($log->subject_type_old == 10) {
        //         $log->delete();
        //     }

        //     if ($log->subject_type_old == 11) {
        //         $log->update(['subject_type' => "App\Models\HotArea"]);
        //     }

        //     if ($log->subject_type_old == 12) {
        //         $log->delete();
        //     }
        // }

        // $sigs = ActivityLog::whereIn('subject_type_old', [8, 2, 4, 6])->get();
        // foreach ($sigs as $sig) {
        //     if ($sig->subject_type_old == 6 || $sig->subject_type_old == 4) {
        //         if (! Connections::where('id', $sig->subject_id)->first()) {
        //             $sig->update(['subject_type' => 'App\Models\ConnectionHistory']);
        //         }
        //     }

        //     if ($sig->subject_type_old == 8 || $sig->subject_type_old == 2) {
        //         if (! Signature::where('id', $sig->subject_id)->first()) {
        //             $sig->update(['subject_type' => 'App\Models\SignatureHistory']);
        //         }
        //     }
        // }
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn('subject_type_old');
        });
        Schema::drop('activiy_subject_types');

        Schema::table('activity_log_snap_shots', function (Blueprint $table) {
            $table->dropColumn('7');
            $table->dropColumn('11');
            $table->dropColumn('26');
            $table->dropColumn('14');
            $table->dropColumn('17');
            $table->dropColumn('12');
        });

        ActiviyDescriptionTypes::whereIn('id', [7, 11, 26, 14, 17, 12])->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
