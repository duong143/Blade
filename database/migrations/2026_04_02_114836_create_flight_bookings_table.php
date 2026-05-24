<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('flight_id')->constrained('flights')->cascadeOnDelete();

            $table->string('booking_code')->nullable()->unique();

            $table->unsignedInteger('adult')->default(1);
            $table->unsignedInteger('child')->default(0);
            $table->unsignedInteger('infant')->default(0);
            $table->unsignedInteger('total_passengers')->default(1);

            $table->integer('adult_price')->default(0);
            $table->integer('child_price')->default(0);
            $table->integer('infant_price')->default(0);
            $table->integer('tax_fee')->default(0);
            $table->integer('total_amount')->default(0);
            $table->integer('final_amount')->default(0);

            $table->string('contact_name');
            $table->string('contact_phone', 50);
            $table->string('contact_email')->nullable();

            $table->string('payment_method', 50)->nullable();
            $table->string('payment_status', 50)->default('pending');
            $table->string('booking_status', 50)->default('draft');

            $table->timestamp('payment_expired_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->text('admin_note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_bookings');
    }
};
