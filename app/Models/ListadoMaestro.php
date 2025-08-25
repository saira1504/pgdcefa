<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Asegúrate de importar el modelo User
use App\Models\Area;

class ListadoMaestro extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'listado_maestro';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'tipo_proceso',
        'nombre_proceso',
        'subproceso_sig_subsistema',
        'documentos', // Nombre del archivo subido
        'numero_doc',
        'responsable',
        'tipo_documento',
        'nombre_documento',
        'codigo',
        'version',
        'fecha_creacion',
        'revision_fecha',
        'revision_cargo',
        'revision_firma',
        'aprobacion_fecha',
        'aprobacion_cargo',
        'aprobacion_firma',
        'estado',
        'creado_por',
        'area_id'
    ];

    // Relación con el usuario que creó el registro
    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    // Configurar el formato de fecha para fecha_creacion
    protected $casts = [
        'fecha_creacion' => 'date:Y-m-d',
        'revision_fecha' => 'datetime',
        'aprobacion_fecha' => 'datetime',
    ];
}