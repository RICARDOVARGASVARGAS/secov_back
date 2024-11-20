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
        Schema::create('drivers', function (Blueprint $table) {
            // Datos del usuario
            $table->id();
            $table->enum('document_type', ['pasaporte', 'dni'])->default('dni');
            $table->string('document_number')->unique();
            $table->string('name');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');
            $table->string('image')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number', 15)->nullable();
            $table->string('address', 255)->nullable();
            $table->enum('gender', ['masculino', 'femenino'])->default('masculino');
            // Datos del documentos de manejo
            $table->string('license_number')->nullable();
            $table->string('license_expiration_date')->nullable();
            $table->string('license_issue_date')->nullable();
            $table->string('license_class')->nullable();
            $table->string('license_category')->nullable();
            $table->string('image_license')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
