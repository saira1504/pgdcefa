<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('unidad_id')->constrained('unidades_productivas')->onDelete('cascade');
            $table->foreignId('aprendiz_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('tipo')->default('entrega'); // entrega, lectura, practica, evaluacion
            $table->string('prioridad')->default('normal'); // normal, alta, urgente
            $table->date('fecha_limite')->nullable();
            $table->enum('estado', ['pendiente', 'entregado', 'en_revision', 'aprobado', 'rechazado'])->default('pendiente');
            $table->json('archivos_adjuntos')->nullable(); // Para múltiples archivos
            $table->text('instrucciones')->nullable();
            $table->boolean('notificado')->default(false);
            $table->timestamps();
            
            $table->index(['admin_id', 'estado']);
            $table->index(['unidad_id', 'estado']);
            $table->index(['aprendiz_id', 'estado']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tareas');
    }
};
