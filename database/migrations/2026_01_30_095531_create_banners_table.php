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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();

            // phân loại banner
            $table->string('type')->default('main');
            // main = banner lớn
            // small = banner nhỏ (làm sau)

            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();

            $table->string('image');       // ảnh banner
            $table->string('link')->nullable();

            $table->integer('position')->default(0); // thứ tự
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
