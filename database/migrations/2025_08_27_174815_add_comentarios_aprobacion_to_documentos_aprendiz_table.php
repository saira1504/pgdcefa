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
        Schema::table('documentos_aprendiz', function (Blueprint $table) {
            $table->text('comentarios_aprobacion')->nullable()->after('comentarios_rechazo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentos_aprendiz', function (Blueprint $table) {
            $table->dropColumn('comentarios_aprobacion');
        });
    }
};
