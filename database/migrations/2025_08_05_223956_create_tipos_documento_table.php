<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tipos_documento', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->boolean('obligatorio')->default(false);
            $table->string('categoria')->default('general'); // general, proyecto, evaluacion, etc.
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['activo', 'categoria']);
            $table->index('obligatorio');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tipos_documento');
    }
};
