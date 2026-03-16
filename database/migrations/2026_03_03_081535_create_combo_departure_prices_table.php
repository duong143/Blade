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
        Schema::create('combo_departure_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departure_id')->constrained('combo_departures')->cascadeOnDelete();

            $table->enum('passenger_type', ['adult', 'child', 'infant']);
            $table->unsignedInteger('base_price')->default(0);

            $table->timestamps();

            $table->unique(['departure_id', 'passenger_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_departure_prices');
    }
};
