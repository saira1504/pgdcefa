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
        Schema::table('unidades_productivas', function (Blueprint $table) {
            $table->string('instructor_encargado')->nullable()->after('admin_principal_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unidades_productivas', function (Blueprint $table) {
            $table->dropColumn('instructor_encargado');
        });
    }
};
