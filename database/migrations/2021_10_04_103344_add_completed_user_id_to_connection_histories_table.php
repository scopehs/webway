<?php

use App\Models\ConnectionHistory;
use App\Models\SignatureHistory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompletedUserIdToConnectionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('connection_histories', function (Blueprint $table) {
            $table->foreignId('completed_user_id')->after('delete_flag')->nullable();
        });

        $connections = ConnectionHistory::where('id', '>', 0)->get();
        foreach ($connections as $connection) {
            $valid_connection = ConnectionHistory::where('id', $connection->id)
                ->whereHas('targetSig', function (Builder $query) {
                    $query->where('delete', 0)
                        ->whereNotNull('signature_id')
                        ->whereNotNull('wormhole_info_ship_size_id')
                        ->whereNotNull('wormhole_info_leads_to_id')
                        ->whereNotNull('wormhole_info_mass_id')
                        ->whereNotNull('wormhole_info_time_till_death_id');
                })->count();

            if ($valid_connection > 0) {
                $user_id = SignatureHistory::where('id', $connection->target_system_id)->value('created_by_id');
                $connection->update(['completed_user_id' => $user_id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('connection_histories', function (Blueprint $table) {
            //
        });
    }
}
