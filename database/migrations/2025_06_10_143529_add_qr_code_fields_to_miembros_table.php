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
            $table->string('codigo_qr_temporal')->nullable()->unique()->after('acceso_habilitado');
            $table->timestamp('codigo_qr_expira_at')->nullable()->after('codigo_qr_temporal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('miembros', function (Blueprint $table) {
            $table->dropColumn(['codigo_qr_temporal', 'codigo_qr_expira_at']);
        });
    }
};
