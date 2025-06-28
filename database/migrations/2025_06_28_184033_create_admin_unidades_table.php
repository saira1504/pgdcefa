<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admin_unidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('unidad_id')->constrained('unidades_productivas')->onDelete('cascade');
            $table->timestamp('fecha_asignacion')->useCurrent();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            // Un admin puede tener múltiples unidades, pero cada unidad solo un admin principal
            $table->unique(['admin_id', 'unidad_id']);
            $table->index(['admin_id', 'activo']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_unidades');
    }
};
