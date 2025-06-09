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
        Schema::create('eventos_acceso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('miembro_id')->nullable()->constrained('miembros')->onDelete('set null'); // Nullable if access is denied and member is not identified
            $table->foreignId('dispositivo_control_acceso_id')->constrained('dispositivos_control_acceso')->onDelete('cascade');
            $table->foreignId('sucursal_id')->constrained('sucursales')->onDelete('cascade'); // Denormalized for easier queries
            $table->dateTime('fecha_hora');
            $table->string('tipo_acceso_intentado'); // codigo, huella, facial, qr
            $table->string('resultado'); // permitido, denegado_membresia_inactiva, denegado_codigo_incorrecto, denegado_no_reconocido
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos_acceso');
    }
};
