<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_admin',
                'admin_news',
                'admin_banner',
                'admin_footer',
                'role_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable();
            $table->boolean('is_admin')->default(0);
            $table->boolean('admin_news')->default(0);
            $table->boolean('admin_banner')->default(0);
            $table->boolean('admin_footer')->default(0);
        });
    }
};
