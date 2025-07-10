<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('miembros', function (Blueprint $table) {
            if (!Schema::hasColumn('miembros', 'acceso_habilitado')) {
                $table->boolean('acceso_habilitado')->default(true)->after('plantilla_huella'); // Ajusta 'after' si es necesario
            }
        });
    }
    public function down(): void
    {
        Schema::table('miembros', function (Blueprint $table) {
            if (Schema::hasColumn('miembros', 'acceso_habilitado')) {
                $table->dropColumn('acceso_habilitado');
            }
        });
    }
};