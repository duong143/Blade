<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('combo_bookings', function (Blueprint $table) {
            $table->date('travel_start_date')->nullable()->after('departure_id');
            $table->date('travel_end_date')->nullable()->after('travel_start_date');
        });
    }

    public function down(): void
    {
        Schema::table('combo_bookings', function (Blueprint $table) {
            $table->dropColumn(['travel_start_date', 'travel_end_date']);
        });
    }
};
