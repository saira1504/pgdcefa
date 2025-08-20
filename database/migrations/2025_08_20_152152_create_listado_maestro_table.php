<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListadoMaestroTable extends Migration
{
    public function up()
    {
        Schema::create('listado_maestro', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_proceso');
            $table->string('nombre_proceso');
            $table->string('subproceso_sig_subsistema')->nullable();
            $table->string('documentos')->nullable(); // Almacena el nombre del archivo subido
            $table->string('numero_doc')->nullable();
            $table->string('responsable')->nullable();
            $table->string('tipo_documento')->nullable();
            $table->string('nombre_documento')->nullable();
            $table->string('codigo')->nullable();
            $table->string('version')->nullable();
            $table->date('fecha_creacion')->nullable();
            $table->date('revision_fecha')->nullable();
            $table->string('revision_cargo')->nullable();
            $table->string('revision_firma')->nullable();
            $table->date('aprobacion_fecha')->nullable();
            $table->string('aprobacion_cargo')->nullable();
            $table->string('aprobacion_firma')->nullable();
            $table->string('estado')->default('pendiente');
            $table->foreignId('creado_por')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('listado_maestro');
    }
}