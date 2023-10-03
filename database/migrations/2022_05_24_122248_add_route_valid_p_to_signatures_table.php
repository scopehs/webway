<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRouteValidPToSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signatures', function (Blueprint $table) {
            // $table->uuid('route_link')->nullable(1);
            $table->uuid('route_link_p')->nullable();
            $table->boolean('jumps_p')->default(0);
            $table->renameColumn('route_vaild', 'jumps');
        });

        Schema::table('signatures', function (Blueprint $table) {
            $table->boolean('jumps')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('signatures', function (Blueprint $table) {
            $table->dropColumn('route_link');
            $table->dropColumn('route_link_p');
            $table->dropColumn('jumps_p');
            $table->renameColumn('jumps', 'route_vaild');
        });
    }
}
