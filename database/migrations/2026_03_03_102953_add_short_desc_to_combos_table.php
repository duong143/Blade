<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('combos', function (Blueprint $table) {
            if (!Schema::hasColumn('combos', 'short_desc')) {
                $table->string('short_desc', 500)->nullable()->after('duration_nights');
            }
        });
    }

    public function down(): void
    {
        Schema::table('combos', function (Blueprint $table) {
            if (Schema::hasColumn('combos', 'short_desc')) {
                $table->dropColumn('short_desc');
            }
        });
    }
};
