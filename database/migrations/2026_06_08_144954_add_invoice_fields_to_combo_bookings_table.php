<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('combo_bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('combo_bookings', 'customer_note')) {
                $table->text('customer_note')->nullable()->after('contact_email');
            }

            if (!Schema::hasColumn('combo_bookings', 'invoice_sent_at')) {
                $table->timestamp('invoice_sent_at')->nullable()->after('paid_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('combo_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('combo_bookings', 'customer_note')) {
                $table->dropColumn('customer_note');
            }

            if (Schema::hasColumn('combo_bookings', 'invoice_sent_at')) {
                $table->dropColumn('invoice_sent_at');
            }
        });
    }
};
