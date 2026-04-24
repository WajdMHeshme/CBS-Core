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
Schema::create('car_images', function (Blueprint $table) {
    $table->id();
    $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
    $table->string('path');
    $table->boolean('is_main')->default(false);
    $table->string('alt')->nullable();
    $table->timestamps();
    $table->softDeletes();

    $table->index('is_main');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_images');
    }
};
