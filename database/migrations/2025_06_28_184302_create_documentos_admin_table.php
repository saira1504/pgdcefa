<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documentos_admin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('unidad_id')->constrained('unidades_productivas')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('tipo_documento'); // guia, manual, plantilla, evaluacion, recurso
            $table->string('categoria'); // teoria, practica, evaluacion, proyecto
            $table->string('archivo_path');
            $table->string('archivo_original');
            $table->string('mime_type');
            $table->bigInteger('tamaño_archivo'); // en bytes
            $table->enum('prioridad', ['normal', 'alta', 'urgente'])->default('normal');
            $table->boolean('requiere_entrega')->default(false);
            $table->boolean('notificar_aprendices')->default(true);
            $table->date('fecha_limite')->nullable();
            $table->boolean('activo')->default(true);
            $table->integer('descargas')->default(0);
            $table->timestamps();
            
            $table->index(['admin_id', 'activo']);
            $table->index(['unidad_id', 'activo']);
            $table->index(['tipo_documento', 'categoria']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('documentos_admin');
    }
};
