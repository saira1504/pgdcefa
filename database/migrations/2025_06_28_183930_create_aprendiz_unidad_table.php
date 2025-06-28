<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('aprendiz_unidad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aprendiz_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('unidad_id')->constrained('unidades_productivas')->onDelete('cascade');
            $table->date('fecha_asignacion')->default(now());
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->unique(['aprendiz_id', 'unidad_id']);
            $table->index(['unidad_id', 'activo']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('aprendiz_unidad');
    }
};
