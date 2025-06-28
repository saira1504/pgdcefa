<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('unidades_productivas', function (Blueprint $table) {
            $table->foreignId('admin_principal_id')->nullable()->constrained('users')->onDelete('set null');
            $table->index(['admin_principal_id', 'estado']);
        });
    }

    public function down()
    {
        Schema::table('unidades_productivas', function (Blueprint $table) {
            $table->dropForeign(['admin_principal_id']);
            $table->dropColumn('admin_principal_id');
        });
    }
};
