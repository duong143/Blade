<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('combos', function (Blueprint $table) {
            $table->text('hotel_amenities')->nullable()->after('short_desc');
            $table->string('content_image')->nullable()->after('image');
            $table->longText('itinerary_detail')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('combos', function (Blueprint $table) {
            $table->dropColumn([
                'hotel_amenities',
                'content_image',
                'itinerary_detail',
            ]);
        });
    }
};
