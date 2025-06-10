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
        Schema::create('reglas_acceso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursal_id')->constrained('sucursales')->onDelete('cascade');
            $table->foreignId('tipo_membresia_id')->nullable()->constrained('tipos_membresia')->onDelete('cascade');
            $table->tinyInteger('dia_semana')->nullable()->comment('0=Domingo, 1=Lunes, ..., 6=Sabado. Consistent with Carbon dayOfWeek.');
            $table->time('hora_desde')->nullable();
            $table->time('hora_hasta')->nullable();
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reglas_acceso');
    }
};
