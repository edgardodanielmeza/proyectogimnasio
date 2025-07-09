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
            // Solo añadir la FK si la columna existe y la FK no existe ya
            if (Schema::hasColumn('users', 'sucursal_id')) {
                // Comprobar si la FK ya existe puede ser complejo y depende del SGBD.
                // Por simplicidad, asumimos que si llegamos aquí después de la migración de users
                // y antes no tenía la FK, entonces es seguro añadirla.
                // En una situación ideal, se verificaría el nombre de la constraint.

                // Primero, asegurar que la columna sucursal_id sea del tipo correcto si no lo es.
                // $table->unsignedBigInteger('sucursal_id')->nullable()->change(); // Esto podría ser necesario si se definió con otro tipo.

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
                // El nombre de la restricción por defecto es users_sucursal_id_foreign
                // Es importante que este nombre coincida con el que Laravel genera.
                $foreignKeys = Schema::getConnection()->getDoctrineSchemaManager()->listTableForeignKeys('users');
                $sucursalForeignKeyExists = false;
                foreach ($foreignKeys as $foreignKey) {
                    if ($foreignKey->getForeignTableName() == 'sucursales' && count($foreignKey->getLocalColumns()) == 1 && $foreignKey->getLocalColumns()[0] == 'sucursal_id') {
                        $sucursalForeignKeyExists = true;
                        break;
                    }
                }

                if ($sucursalForeignKeyExists) {
                    $table->dropForeign(['sucursal_id']);
                }
            }
        });
    }
};
