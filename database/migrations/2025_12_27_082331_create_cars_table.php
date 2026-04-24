<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::create('cars', function (Blueprint $table) {
    $table->id();

    $table->string('title');

    $table->foreignId('user_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('car_type_id')
        ->nullable()
        ->constrained('car_types')
        ->nullOnDelete();

    $table->string('brand');
    $table->string('model')->nullable(); // ✔️ إصلاح
    $table->year('year')->nullable();     // ✔️ إصلاح

    $table->string('color')->nullable();
    $table->string('plate_number')->nullable()->unique();

    $table->decimal('price_per_day', 10, 2)->nullable(); // ✔️ إصلاح

    $table->enum('status', ['available', 'booked', 'maintenance'])
        ->default('available');

    $table->text('description')->nullable();

    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
