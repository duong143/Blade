<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('combos', function (Blueprint $table) {
            $table->text('content_image')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('combos', function (Blueprint $table) {
            $table->string('content_image')->nullable()->change();
        });
    }
};
