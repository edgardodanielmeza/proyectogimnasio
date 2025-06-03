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
            $table->foreignId('sucursal_id')->constrained('sucursales')->onDelete('cascade');
            $table->string('nombre'); // ej: "Puerta Principal", "Torno 1"
            $table->string('tipo'); // teclado_numerico, biometrico_huella, biometrico_facial
            $table->string('identificador_dispositivo')->unique(); // ID o serial del dispositivo físico
            $table->string('estado'); // conectado, desconectado, error
            $table->ipAddress('ip_address')->nullable();
            $table->integer('puerto')->nullable();
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
