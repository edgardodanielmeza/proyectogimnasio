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
        Schema::table('miembros', function (Blueprint $table) {
            // Las columnas 'codigo_acceso_numerico' y 'plantilla_huella' ya existen
            // en la migración original '2025_06_03_154214_create_miembros_table.php'.
            // Solo se añade 'acceso_habilitado'.
            // Si 'plantilla_huella' necesita ser text y no binary, se debería modificar la migración original.
            // Por ahora, se asume que binary está bien y solo añadimos el campo faltante.
            $table->boolean('acceso_habilitado')->default(true)->after('plantilla_huella');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('miembros', function (Blueprint $table) {
            $table->dropColumn(['acceso_habilitado']);
        });
    }
};
