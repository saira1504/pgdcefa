<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_id')->constrained('tareas')->onDelete('cascade');
            $table->foreignId('aprendiz_id')->constrained('users')->onDelete('cascade');
            $table->string('archivo_path')->nullable();
            $table->string('archivo_original')->nullable();
            $table->text('comentarios')->nullable();
            $table->decimal('calificacion', 3, 2)->nullable(); // 0.00 a 5.00
            $table->text('retroalimentacion')->nullable();
            $table->enum('estado', ['entregado', 'en_revision', 'aprobado', 'rechazado', 'reentrega'])->default('entregado');
            $table->timestamp('fecha_entrega')->useCurrent();
            $table->timestamp('fecha_revision')->nullable();
            $table->timestamps();
            
            $table->unique(['tarea_id', 'aprendiz_id']); // Una entrega por aprendiz por tarea
            $table->index(['aprendiz_id', 'estado']);
            $table->index(['tarea_id', 'estado']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('entregas');
    }
};
