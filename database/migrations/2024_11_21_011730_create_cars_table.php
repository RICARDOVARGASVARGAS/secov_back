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
            // Soat
            $table->string('number_soat')->nullable();
            $table->string('file_soat')->nullable();
            $table->date('date_soat_issue')->nullable();
            $table->date('date_soat_expiration')->nullable();
            $table->string('file_technical_review')->nullable();
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
