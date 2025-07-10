<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('miembros', function (Blueprint $table) {
            if (!Schema::hasColumn('miembros', 'documento_identidad')) {
                // Lo coloco después de 'email' como ejemplo, puedes ajustarlo.
                $table->string('documento_identidad')->nullable()->unique()->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('miembros', function (Blueprint $table) {
            if (Schema::hasColumn('miembros', 'documento_identidad')) {
                // Al eliminar una columna con índice unique, es bueno eliminar el índice primero.
                // Laravel normalmente maneja esto, pero si da error, se puede hacer explícito.
                // $table->dropUnique(['documento_identidad']);
                $table->dropColumn('documento_identidad');
            }
        });
    }
};
