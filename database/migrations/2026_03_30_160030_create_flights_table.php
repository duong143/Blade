<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();

            $table->foreignId('airline_id')->constrained('airlines')->cascadeOnDelete();
            $table->foreignId('departure_airport_id')->constrained('airports')->cascadeOnDelete();
            $table->foreignId('arrival_airport_id')->constrained('airports')->cascadeOnDelete();

            $table->string('flight_number', 30);
            $table->string('aircraft')->nullable();

            $table->date('departure_date');
            $table->time('departure_time');
            $table->date('arrival_date');
            $table->time('arrival_time');

            $table->integer('duration_minutes')->default(0);

            $table->string('seat_class')->default('Phổ thông');
            $table->integer('adult_price')->default(0);
            $table->integer('child_price')->default(0);
            $table->integer('infant_price')->default(0);
            $table->integer('tax_fee')->default(0);

            $table->string('carry_on_baggage')->nullable();
            $table->string('checked_baggage')->nullable();
            $table->string('other_benefits')->nullable();

            $table->integer('total_seats')->default(0);
            $table->integer('available_seats')->default(0);

            $table->boolean('is_direct')->default(true);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
