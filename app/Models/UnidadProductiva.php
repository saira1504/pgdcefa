<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadProductiva extends Model
{
    use HasFactory;

    protected $table = 'unidades_productivas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo',
        'proyecto',
        'objetivos',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'progreso',
        'metadatos',
        'admin_principal_id',
        'activo'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'progreso' => 'integer',
        'metadatos' => 'array',
        'activo' => 'boolean'
    ];

    // Relaciones
    public function adminPrincipal()
    {
        return $this->belongsTo(User::class, 'admin_principal_id');
    }

    public function admins()
    {
        return $this->belongsToMany(User::class, 'admin_unidades', 'unidad_id', 'admin_id')
                    ->withPivot('fecha_asignacion', 'activo')
                    ->wherePivot('activo', true);
    }

    public function aprendices()
    {
        return $this->belongsToMany(User::class, 'aprendiz_unidad', 'unidad_id', 'aprendiz_id')
                    ->withPivot('fecha_asignacion', 'activo')
                    ->wherePivot('activo', true);
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'unidad_id');
    }

    public function documentos()
    {
        return $this->hasMany(DocumentoAdmin::class, 'unidad_id');
    }

    public function documentosAprendiz()
    {
        return $this->hasMany(DocumentoAprendiz::class, 'unidad_id');
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    public function scopeDelAdmin($query, $adminId)
    {
        return $query->where('admin_principal_id', $adminId)
                    ->orWhereHas('admins', function($q) use ($adminId) {
                        $q->where('admin_id', $adminId);
                    });
    }

    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    // Accessors
    public function getAprendicesCountAttribute()
    {
        return $this->aprendices()->count();
    }

    public function getDocumentosCountAttribute()
    {
        return $this->documentos()->activos()->count();
    }

    public function getTareasPendientesCountAttribute()
    {
        return $this->tareas()->pendientes()->count();
    }

    public function getEstadoColorAttribute()
    {
        return match($this->estado) {
            'planificacion' => 'secondary',
            'iniciando' => 'warning',
            'en_proceso' => 'success',
            'pausado' => 'danger',
            'completado' => 'primary',
            default => 'secondary'
        };
    }

    // Métodos
    public function asignarAdmin($adminId)
    {
        return $this->admins()->syncWithoutDetaching([
            $adminId => ['fecha_asignacion' => now(), 'activo' => true]
        ]);
    }

    public function asignarAprendiz($aprendizId)
    {
        return $this->aprendices()->syncWithoutDetaching([
            $aprendizId => ['fecha_asignacion' => now(), 'activo' => true]
        ]);
    }

    public function actualizarProgreso()
    {
        $totalTareas = $this->tareas()->count();
        if ($totalTareas === 0) {
            $this->update(['progreso' => 0]);
            return;
        }

        $tareasCompletadas = $this->tareas()->where('estado', 'aprobado')->count();
        $progreso = round(($tareasCompletadas / $totalTareas) * 100);
        
        $this->update(['progreso' => $progreso]);
    }
}