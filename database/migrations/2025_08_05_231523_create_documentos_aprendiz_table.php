<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documentos_aprendiz', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aprendiz_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('unidad_id')->constrained('unidades_productivas')->onDelete('cascade');
            $table->foreignId('tipo_documento_id')->constrained('tipos_documento')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('archivo_path');
            $table->string('archivo_original');
            $table->string('mime_type');
            $table->bigInteger('tamaño_archivo'); // en bytes
            $table->enum('estado', ['pendiente', 'en_revision', 'aprobado', 'rechazado'])->default('pendiente');
            $table->text('comentarios_rechazo')->nullable();
            $table->timestamp('fecha_subida')->useCurrent();
            $table->timestamp('fecha_revision')->nullable();
            $table->foreignId('revisado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['aprendiz_id', 'estado']);
            $table->index(['unidad_id', 'estado']);
            $table->index(['tipo_documento_id', 'estado']);
            $table->index('fecha_subida');
        });
    }

    public function down()
    {
        Schema::dropIfExists('documentos_aprendiz');
    }
};
