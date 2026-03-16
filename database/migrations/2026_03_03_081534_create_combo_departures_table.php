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
        Schema::create('combo_departures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('combo_id')->constrained('combos')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();

            $table->unsignedInteger('capacity')->default(0);
            $table->unsignedInteger('sold')->default(0);

            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique(['combo_id', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_departures');
    }
};
