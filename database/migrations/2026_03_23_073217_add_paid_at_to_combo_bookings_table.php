<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('combo_bookings', function (Blueprint $table) {
            $table->timestamp('paid_at')->nullable()->after('payment_expired_at');
        });
    }

    public function down(): void
    {
        Schema::table('combo_bookings', function (Blueprint $table) {
            $table->dropColumn('paid_at');
        });
    }
};
