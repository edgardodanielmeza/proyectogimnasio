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
        Schema::table('dispositivos_control_acceso', function (Blueprint $table) {
            // Renombrar columna tipo a tipo_dispositivo
            if (Schema::hasColumn('dispositivos_control_acceso', 'tipo')) {
                $table->renameColumn('tipo', 'tipo_dispositivo');
            } elseif (!Schema::hasColumn('dispositivos_control_acceso', 'tipo_dispositivo')) {
                $table->string('tipo_dispositivo')->after('nombre'); // Si 'tipo' no existía, la creamos
            }

            // Añadir mac_address
            if (!Schema::hasColumn('dispositivos_control_acceso', 'mac_address')) {
                $table->string('mac_address')->nullable()->unique()->after('ip_address');
            }

            // Añadir configuracion_adicional
            if (!Schema::hasColumn('dispositivos_control_acceso', 'configuracion_adicional')) {
                $table->json('configuracion_adicional')->nullable()->after('puerto');
            }

            // Asegurar que 'estado' exista, si no, añadirlo.
            // Los estados podrían ser: 'activo', 'inactivo', 'error', 'mantenimiento'
            // La migración original ya crea 'estado' como string.
            // Se puede considerar cambiarlo a un enum si la BD lo soporta y se prefiere.
            // Por ahora, se asume que 'estado' ya existe como string.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dispositivos_control_acceso', function (Blueprint $table) {
            if (Schema::hasColumn('dispositivos_control_acceso', 'tipo_dispositivo') && !Schema::hasColumn('dispositivos_control_acceso', 'tipo')) {
                $table->renameColumn('tipo_dispositivo', 'tipo');
            }

            if (Schema::hasColumn('dispositivos_control_acceso', 'mac_address')) {
                $table->dropColumn('mac_address');
            }

            if (Schema::hasColumn('dispositivos_control_acceso', 'configuracion_adicional')) {
                $table->dropColumn('configuracion_adicional');
            }
        });
    }
};
