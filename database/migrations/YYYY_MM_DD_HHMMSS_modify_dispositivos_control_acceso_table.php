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
            // Solo intentar renombrar si 'tipo' existe Y 'tipo_dispositivo' NO existe
            if (Schema::hasColumn('dispositivos_control_acceso', 'tipo') && !Schema::hasColumn('dispositivos_control_acceso', 'tipo_dispositivo')) {
                $table->renameColumn('tipo', 'tipo_dispositivo');
            }
            // Si 'tipo' no existe pero 'tipo_dispositivo' tampoco, la creamos (escenario poco probable si la migración original corrió)
            // Aseguramos que se coloque después de 'nombre' si se crea aquí.
            elseif (!Schema::hasColumn('dispositivos_control_acceso', 'tipo') && !Schema::hasColumn('dispositivos_control_acceso', 'tipo_dispositivo')) {
                $existing_columns = Schema::getColumnListing('dispositivos_control_acceso');
                if (in_array('nombre', $existing_columns)) {
                    $table->string('tipo_dispositivo')->after('nombre')->comment('Ej: teclado_numerico, biometrico_huella');
                } else {
                    $table->string('tipo_dispositivo')->comment('Ej: teclado_numerico, biometrico_huella');
                }
            }

            // Añadir mac_address si no existe
            if (!Schema::hasColumn('dispositivos_control_acceso', 'mac_address')) {
                $existing_columns = Schema::getColumnListing('dispositivos_control_acceso');
                if (in_array('ip_address', $existing_columns)) {
                    $table->string('mac_address')->nullable()->unique()->after('ip_address');
                } else {
                    $table->string('mac_address')->nullable()->unique();
                }
            }

            // Añadir configuracion_adicional si no existe
            if (!Schema::hasColumn('dispositivos_control_acceso', 'configuracion_adicional')) {
                 $existing_columns = Schema::getColumnListing('dispositivos_control_acceso');
                if (in_array('puerto', $existing_columns)) {
                    $table->json('configuracion_adicional')->nullable()->after('puerto');
                } else {
                     $table->json('configuracion_adicional')->nullable();
                }
            }

            // Modificar 'estado' para asegurar que sea compatible con los nuevos estados definidos en el modelo.
            if (Schema::hasColumn('dispositivos_control_acceso', 'estado')) {
                 $table->string('estado')->comment('Ej: activo, inactivo, error, mantenimiento')->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dispositivos_control_acceso', function (Blueprint $table) {
            // Solo intentar renombrar si 'tipo_dispositivo' existe Y 'tipo' NO existe
            if (Schema::hasColumn('dispositivos_control_acceso', 'tipo_dispositivo') && !Schema::hasColumn('dispositivos_control_acceso', 'tipo')) {
                $table->renameColumn('tipo_dispositivo', 'tipo');
            }

            if (Schema::hasColumn('dispositivos_control_acceso', 'mac_address')) {
                // Para hacer el drop de una columna unique, a veces es necesario primero dropear el índice.
                // El nombre del índice podría ser: $table->dropUnique('dispositivos_control_acceso_mac_address_unique');
                // o ['mac_address'] si es el nombre por defecto.
                // Por seguridad, intentamos dropear el índice explícitamente si existe.
                // Esto puede variar según la base de datos. En MySQL, dropear la columna usualmente dropea el índice.
                // Si da problemas, se podría necesitar un if(DB::getDriverName() === 'mysql') y sintaxis específica.
                // $table->dropUnique(['mac_address']); // Comentado por ahora, Laravel suele manejarlo.
                $table->dropColumn('mac_address');
            }

            if (Schema::hasColumn('dispositivos_control_acceso', 'configuracion_adicional')) {
                $table->dropColumn('configuracion_adicional');
            }

            if (Schema::hasColumn('dispositivos_control_acceso', 'estado')) {
                 $table->string('estado')->comment('')->change(); // Remover comentario específico
            }
        });
    }
};
