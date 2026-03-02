<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('role_user', function (Blueprint $table) {
            $table->unique(['user_id', 'role_id']);
        });

        Schema::table('permission_role', function (Blueprint $table) {
            $table->unique(['role_id', 'permission_id']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pivot_tables', function (Blueprint $table) {
            //
        });
    }
};
