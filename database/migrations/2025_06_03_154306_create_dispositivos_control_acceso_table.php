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
        Schema::create('dispositivos_control_acceso', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('tipo', ['teclado_numerico', 'biometrico_huella', 'biometrico_facial'])->default('teclado_numerico');
            $table->foreignId('sucursal_id')->constrained('sucursales')->onDelete('cascade');
            $table->ipAddress('ip_address')->nullable();
            $table->macAddress('mac_address')->nullable();
            $table->enum('estado', ['activo', 'inactivo', 'mantenimiento'])->default('activo');
            $table->timestamp('ultimo_heartbeat_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispositivos_control_acceso');
    }
};
