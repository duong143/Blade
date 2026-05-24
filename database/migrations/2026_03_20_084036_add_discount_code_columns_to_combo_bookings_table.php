<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('combo_bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('discount_code_id')->nullable()->after('departure_id');
            $table->string('discount_code')->nullable()->after('discount_code_id');
            $table->unsignedInteger('discount_code_percent')->default(0)->after('sale_percent');
            $table->unsignedBigInteger('discount_code_amount')->default(0)->after('discount_code_percent');
        });
    }

    public function down(): void
    {
        Schema::table('combo_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'discount_code_id',
                'discount_code',
                'discount_code_percent',
                'discount_code_amount',
            ]);
        });
    }
};
