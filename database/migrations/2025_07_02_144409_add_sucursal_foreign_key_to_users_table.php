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
            if (Schema::hasColumn('users', 'sucursal_id')) {
                // Asegura que la columna es del tipo correcto antes de añadir la FK
                // Esto es una precaución, usualmente no es necesario si la migración original la creó como unsignedBigInteger.
                // $table->unsignedBigInteger('sucursal_id')->nullable()->change();

                $table->foreign('sucursal_id')
                      ->references('id')
                      ->on('sucursales')
                      ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'sucursal_id')) {
                // Intentar obtener el nombre de la FK dinámicamente es complejo y varía por BD.
                // Laravel por defecto nombra las FKs como: table_column_foreign
                // Ejemplo: users_sucursal_id_foreign
                // Si esta forma de dropear no funciona, se puede necesitar el nombre exacto de la constraint.
                try {
                    $table->dropForeign(['sucursal_id']);
                } catch (\Exception $e) {
                    // Log o manejar el error si la FK no existe o tiene un nombre diferente
                    // Por ejemplo, si se ejecuta 'down' dos veces.
                    if (app()->environment('local')) {
                        logger()->warning('Intento de eliminar FK sucursal_id en users falló o ya no existía: ' . $e->getMessage());
                    }
                }
            }
        });
    }
};
