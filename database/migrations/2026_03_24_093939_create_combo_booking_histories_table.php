<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('combo_booking_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('combo_booking_id')
                ->constrained('combo_bookings')
                ->cascadeOnDelete();

            $table->string('action', 100);
            $table->string('field_name')->nullable();

            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();

            $table->string('changed_by_type', 50)->nullable(); // customer, admin, system
            $table->unsignedBigInteger('changed_by_id')->nullable();
            $table->string('changed_by_name')->nullable();

            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('combo_booking_histories');
    }
};
