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
        Schema::create('miembros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->unique();
            $table->date('fecha_nacimiento');
            $table->string('foto_path')->nullable();
            $table->string('codigo_acceso_numerico')->nullable(); // Should be hashed in the model
            $table->binary('plantilla_huella')->nullable(); // Consider security and storage implications
            $table->foreignId('sucursal_id')->nullable()->constrained('sucursales')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('miembros');
    }
};
