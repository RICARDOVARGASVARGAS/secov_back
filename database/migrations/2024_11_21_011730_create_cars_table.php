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
            $table->uuid('uuid')->unique();
            $table->string('plate')->unique(); // Placa
            $table->string('chassis')->unique()->nullable(); // Chasis
            $table->string('motor')->unique()->nullable(); // Motor
            $table->string('image_car')->nullable(); // Imagen
            $table->foreignId('brand_id')->constrained(); // Marca
            $table->foreignId('type_car_id')->constrained(); // Tipo
            $table->foreignId('group_id')->constrained(); // Asociación
            $table->foreignId('year_id')->constrained(); // Año
            $table->foreignId('color_id')->constrained(); // Color
            $table->foreignId('example_id')->constrained(); // Modelo
            $table->foreignId('driver_id')->constrained(); // Conductor
            $table->string('group_number')->nullable(); // Número de grupo
            $table->integer('number_of_seats'); // Número de asientos
            $table->string('file_car')->nullable(); // Archivo
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
