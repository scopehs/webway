<?php

use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogSnapShotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_log_snap_shots', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('1')->nullable();
            $table->smallInteger('2')->nullable();
            $table->smallInteger('3')->nullable();
            $table->smallInteger('4')->nullable();
            $table->smallInteger('5')->nullable();
            $table->smallInteger('6')->nullable();
            $table->smallInteger('7')->nullable();
            $table->smallInteger('8')->nullable();
            $table->smallInteger('9')->nullable();
            $table->smallInteger('10')->nullable();
            $table->smallInteger('11')->nullable();
            $table->smallInteger('12')->nullable();
            $table->smallInteger('13')->nullable();
            $table->smallInteger('14')->nullable();
            $table->smallInteger('15')->nullable();
            $table->smallInteger('16')->nullable();
            $table->smallInteger('17')->nullable();
            $table->smallInteger('18')->nullable();
            $table->smallInteger('19')->nullable();
            $table->smallInteger('20')->nullable();
            $table->smallInteger('21')->nullable();
            $table->smallInteger('22')->nullable();
            $table->smallInteger('23')->nullable();
            $table->smallInteger('24')->nullable();
            $table->smallInteger('25')->nullable();
            $table->smallInteger('26')->nullable();
            $table->smallInteger('27')->nullable();
            $table->smallInteger('28')->nullable();
            $table->smallInteger('29')->nullable();
            $table->smallInteger('30')->nullable();
            $table->smallInteger('31')->nullable();
            $table->smallInteger('32')->nullable();
            $table->smallInteger('33')->nullable();
            $table->smallInteger('34')->nullable();
            $table->smallInteger('35')->nullable();
            $table->smallInteger('36')->nullable();
            $table->smallInteger('37')->nullable();
            $table->smallInteger('38')->nullable();
            $table->timestamps();
        });

        // $firstRow = ActivityLog::first();
        // $firstDate = $firstRow->created_at ?? now();
        // $start = Carbon::parse($firstDate);
        // $start->floorMinute()->format('Y-m-d H:i:s');
        // $startSub = Carbon::parse($start);
        // $startSub->subMinute()->format('Y-m-d H:i:s');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_log_snap_shots');
    }
}
