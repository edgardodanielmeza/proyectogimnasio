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
            $table->foreignId('miembro_id')->nullable()->constrained('miembros')->onDelete('set null');
            $table->foreignId('dispositivo_control_acceso_id')->constrained('dispositivos_control_acceso')->onDelete('cascade'); // Se mantiene el nombre de la FK para consistencia con el modelo actual
            $table->foreignId('sucursal_id')->constrained('sucursales')->onDelete('cascade');
            $table->dateTime('fecha_hora'); // Se mantiene dateTime
            $table->enum('tipo_evento', [
                'entrada_permitida',
                'salida_permitida',
                'intento_denegado_membresia',
                'intento_denegado_codigo',
                'intento_denegado_desconocido',
                'intento_denegado_horario',
                'intento_denegado_otro',
                'entrada_manual_recepcion'
            ])->default('intento_denegado_otro');
            $table->enum('metodo_acceso_utilizado', [
                'codigo_numerico',
                'huella_digital',
                'facial',
                'qr_temporal',
                'manual_recepcion',
                'desconocido'
            ])->default('desconocido');
            $table->enum('resultado', ['permitido', 'denegado'])->default('denegado');
            $table->text('notas')->nullable();
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
