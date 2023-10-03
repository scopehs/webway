<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

class FixPermissionName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::where('name', 'user_reserved_connection')->update(['name' => 'use_reserved_connection']);
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
