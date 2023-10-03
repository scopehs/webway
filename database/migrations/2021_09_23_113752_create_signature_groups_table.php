<?php

use App\Models\Scanning\Signature;
use App\Models\SignatureGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignatureGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signature_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
        });

        SignatureGroup::create(['id' => 1, 'name' => 'Wormhole']);
        SignatureGroup::create(['id' => 2, 'name' => 'Relic Site']);
        SignatureGroup::create(['id' => 3, 'name' => 'Data Site']);
        SignatureGroup::create(['id' => 4, 'name' => 'Gas Site']);
        SignatureGroup::create(['id' => 5, 'name' => 'Combat Site']);
        SignatureGroup::create(['id' => 6, 'name' => 'Ore Site']);
        SignatureGroup::create(['id' => 7, 'name' => 'Unknowen']);

        Schema::table('signatures', function (Blueprint $table) {
            $table->foreignId('signature_group_id')->after('group')->nullable();
        });

        Signature::where('group', 'Wormhole')->update(['signature_group_id' => 1]);
        Signature::where('group', 'Relic Site')->update(['signature_group_id' => 2]);
        Signature::where('group', 'Data Site')->update(['signature_group_id' => 3]);
        Signature::where('group', 'Gas Site')->update(['signature_group_id' => 4]);
        Signature::where('group', 'Combat Site')->update(['signature_group_id' => 5]);
        Signature::where('group', 'Ore Site')->update(['signature_group_id' => 6]);
        Signature::where('group', '')->update(['signature_group_id' => 7]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('signature_groups');
    }
}
