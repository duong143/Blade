<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flights', function (Blueprint $table) {
            if (!Schema::hasColumn('flights', 'seat_layout')) {
                $table->string('seat_layout')->nullable()->after('aircraft');
            }

            if (!Schema::hasColumn('flights', 'seat_pitch')) {
                $table->string('seat_pitch')->nullable()->after('seat_layout');
            }

            if (!Schema::hasColumn('flights', 'fare_points')) {
                $table->string('fare_points')->nullable()->after('other_benefits');
            }

            if (!Schema::hasColumn('flights', 'display_order')) {
                $table->integer('display_order')->default(0)->after('fare_points');
            }
        });
    }

    public function down(): void
    {
        Schema::table('flights', function (Blueprint $table) {
            $dropColumns = [];

            foreach (['seat_layout', 'seat_pitch', 'fare_points', 'display_order'] as $column) {
                if (Schema::hasColumn('flights', $column)) {
                    $dropColumns[] = $column;
                }
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
