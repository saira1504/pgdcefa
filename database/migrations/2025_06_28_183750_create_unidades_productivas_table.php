<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('unidades_productivas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('tipo')->nullable(); // avicola, ganaderia, agricultura, etc.
            $table->text('proyecto')->nullable();
            $table->text('objetivos')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->enum('estado', ['planificacion', 'iniciando', 'en_proceso', 'pausado', 'completado'])->default('planificacion');
            $table->integer('progreso')->default(0); // 0-100
            $table->json('metadatos')->nullable(); // Para información adicional
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['estado', 'activo']);
            $table->index('tipo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('unidades_productivas');
    }
};
