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
        Schema::table('sucursales', function (Blueprint $table) {
            if (!Schema::hasColumn('sucursales', 'horario_atencion')) {
                // Añadir después de 'telefono' o la última columna relevante si 'telefono' no existe
                $existing_columns = Schema::getColumnListing('sucursales');
                if (in_array('telefono', $existing_columns)) {
                    $table->string('horario_atencion')->nullable()->after('telefono');
                } else {
                    $table->string('horario_atencion')->nullable();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sucursales', function (Blueprint $table) {
            if (Schema::hasColumn('sucursales', 'horario_atencion')) {
                $table->dropColumn('horario_atencion');
            }
        });
    }
};
