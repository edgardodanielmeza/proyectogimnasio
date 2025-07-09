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
        Schema::table('tipos_membresia', function (Blueprint $table) {
            if (!Schema::hasColumn('tipos_membresia', 'acceso_multisucursal')) {
                // Añadir después de 'precio' o la última columna relevante
                $table->boolean('acceso_multisucursal')->default(false)->after('precio');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipos_membresia', function (Blueprint $table) {
            if (Schema::hasColumn('tipos_membresia', 'acceso_multisucursal')) {
                $table->dropColumn('acceso_multisucursal');
            }
        });
    }
};
