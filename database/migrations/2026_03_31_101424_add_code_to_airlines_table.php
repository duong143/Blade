<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('airlines', function (Blueprint $table) {
            if (!Schema::hasColumn('airlines', 'name')) {
                $table->string('name')->nullable();
            }

            if (!Schema::hasColumn('airlines', 'code')) {
                $table->string('code', 10)->nullable();
            }

            if (!Schema::hasColumn('airlines', 'logo')) {
                $table->string('logo')->nullable();
            }

            if (!Schema::hasColumn('airlines', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('airlines', function (Blueprint $table) {
            if (Schema::hasColumn('airlines', 'is_active')) {
                $table->dropColumn('is_active');
            }

            if (Schema::hasColumn('airlines', 'logo')) {
                $table->dropColumn('logo');
            }

            if (Schema::hasColumn('airlines', 'code')) {
                $table->dropColumn('code');
            }

            if (Schema::hasColumn('airlines', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
