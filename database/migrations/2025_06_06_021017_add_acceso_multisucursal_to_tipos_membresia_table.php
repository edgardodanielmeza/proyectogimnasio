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
            // Añadir la nueva columna después de 'precio' o donde se prefiera
            $table->boolean('acceso_multisucursal')->default(false)->after('precio');
            // Default(false) significa que, por defecto, una membresía NO es multisucursal,
            // a menos que se indique lo contrario. Se puede cambiar a true si esa es la regla general.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipos_membresia', function (Blueprint $table) {
            $table->dropColumn('acceso_multisucursal');
        });
    }
};
