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
        Schema::create('combos', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->nullable()->unique();
            $table->string('title');
            $table->string('slug')->nullable()->unique();

            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();

            $table->unsignedInteger('duration_days')->default(0);
            $table->unsignedInteger('duration_nights')->default(0);

            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            $table->unsignedInteger('preorder_days')->default(0);
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combos');
    }
};
