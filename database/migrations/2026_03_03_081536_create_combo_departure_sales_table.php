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
        Schema::create('combo_departure_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departure_id')->constrained('combo_departures')->cascadeOnDelete();

            $table->date('active_date');
            $table->unsignedTinyInteger('sale_percent')->default(0); // 0..100
            $table->string('sale_label', 50)->nullable();

            $table->timestamps();

            $table->unique(['departure_id', 'active_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_departure_sales');
    }
};
