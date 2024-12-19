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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('plate')->unique();
            $table->string('chassis')->unique()->nullable();
            $table->string('motor')->unique()->nullable();
            $table->string('file_car')->nullable();
            $table->foreignId('brand_id')->constrained();
            $table->foreignId('type_car_id')->constrained();
            $table->foreignId('group_id')->constrained();
            $table->foreignId('year_id')->constrained();
            $table->foreignId('color_id')->constrained();
            $table->foreignId('example_id')->constrained();
            $table->foreignId('driver_id')->constrained();
            $table->integer('number_of_seats'); // Número de asientos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
