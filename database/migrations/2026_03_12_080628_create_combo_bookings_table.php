<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('combo_bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('combo_id')->constrained('combos')->cascadeOnDelete();
            $table->foreignId('departure_id')->constrained('combo_departures')->cascadeOnDelete();

            $table->unsignedInteger('adult')->default(1);
            $table->unsignedInteger('child')->default(0);
            $table->unsignedInteger('infant')->default(0);
            $table->unsignedInteger('total_passengers')->default(1);

            $table->unsignedBigInteger('adult_final_price')->default(0);
            $table->unsignedBigInteger('child_final_price')->default(0);
            $table->unsignedBigInteger('infant_final_price')->default(0);
            $table->unsignedInteger('sale_percent')->default(0);
            $table->unsignedBigInteger('total_amount')->default(0);

            $table->string('contact_name');
            $table->string('contact_phone');
            $table->string('contact_email')->nullable();

            $table->boolean('invoice_required')->default(false);
            $table->string('invoice_tax')->nullable();
            $table->string('invoice_company')->nullable();
            $table->string('invoice_address')->nullable();
            $table->string('invoice_email')->nullable();

            $table->string('booking_code')->nullable()->unique();
            $table->string('payment_status')->default('pending');
            $table->string('booking_status')->default('draft');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('combo_bookings');
    }
};
