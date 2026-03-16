<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('combos', function (Blueprint $table) {
            if (!Schema::hasColumn('combos', 'image')) {
                $table->string('image')->nullable()->after('slug');
            }
        });
    }

    public function down(): void
    {
        Schema::table('combos', function (Blueprint $table) {
            if (Schema::hasColumn('combos', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};
