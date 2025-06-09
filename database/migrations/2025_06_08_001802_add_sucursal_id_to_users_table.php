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
        Schema::table('users', function (Blueprint $table) {
            // Añadir la columna después de 'password'
            // Es importante que la tabla 'sucursales' exista cuando esta migración se ejecute.
            $table->foreignId('sucursal_id')
                  ->nullable()
                  ->after('password')
                  ->constrained('sucursales')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Para eliminar una columna con foreign key constraint:
            // 1. Eliminar la restricción de clave foránea.
            // El nombre de la restricción es usualmente `<table>_<column>_foreign`.
            // Por ejemplo: `users_sucursal_id_foreign`
            // O si se usó ->constrained(), Laravel puede manejarlo con dropConstrainedForeignId en versiones recientes.
            // Para mayor compatibilidad y claridad:
            if (Schema::hasColumn('users', 'sucursal_id')) { // Verificar si la columna existe antes de intentar borrar FK
                // Intentar borrar la FK por el nombre convencional
                // En algunos casos, si el nombre de la FK es diferente, este método puede fallar.
                // Un método más robusto podría implicar consultar el schema para el nombre exacto de la FK.
                // $table->dropForeign('users_sucursal_id_foreign'); // Nombre explícito
                $table->dropForeign(['sucursal_id']); // Basado en la columna
                $table->dropColumn('sucursal_id');
            }
        });
    }
};
