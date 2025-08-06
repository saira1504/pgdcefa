<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DocumentoAprendiz extends Model
{
    use HasFactory;

    protected $table = 'documentos_aprendiz';

    protected $fillable = [
        'aprendiz_id',
        'unidad_id',
        'tipo_documento_id',
        'titulo',
        'descripcion',
        'archivo_path',
        'archivo_original',
        'mime_type',
        'tamaño_archivo',
        'estado', // pendiente, en_revision, aprobado, rechazado
        'comentarios_rechazo',
        'fecha_subida',
        'fecha_revision',
        'revisado_por'
    ];

    protected $casts = [
        'fecha_subida' => 'datetime',
        'fecha_revision' => 'datetime',
        'tamaño_archivo' => 'integer'
    ];

    // Relaciones
    public function aprendiz()
    {
        return $this->belongsTo(User::class, 'aprendiz_id');
    }

    public function unidad()
    {
        return $this->belongsTo(UnidadProductiva::class, 'unidad_id');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }

    public function revisor()
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    // Scopes
    public function scopeDelAprendiz($query, $aprendizId)
    {
        return $query->where('aprendiz_id', $aprendizId);
    }

    public function scopePorUnidad($query, $unidadId)
    {
        return $query->where('unidad_id', $unidadId);
    }

    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopeAprobados($query)
    {
        return $query->where('estado', 'aprobado');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeEnRevision($query)
    {
        return $query->where('estado', 'en_revision');
    }

    public function scopeRechazados($query)
    {
        return $query->where('estado', 'rechazado');
    }

    // Accessors
    public function getTamañoHumanoAttribute()
    {
        $bytes = $this->tamaño_archivo;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getUrlDescargaAttribute()
    {
        return Storage::url($this->archivo_path);
    }

    public function getIconoAttribute()
    {
        return match($this->mime_type) {
            'application/pdf' => 'fas fa-file-pdf text-danger',
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'fas fa-file-word text-primary',
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'fas fa-file-excel text-success',
            'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'fas fa-file-powerpoint text-warning',
            default => 'fas fa-file text-secondary'
        };
    }

    public function getEstadoColorAttribute()
    {
        return match($this->estado) {
            'pendiente' => 'secondary',
            'en_revision' => 'warning',
            'aprobado' => 'success',
            'rechazado' => 'danger',
            default => 'secondary'
        };
    }

    public function getEstadoTextoAttribute()
    {
        return match($this->estado) {
            'pendiente' => 'Pendiente',
            'en_revision' => 'En Revisión',
            'aprobado' => 'Aprobado',
            'rechazado' => 'Rechazado',
            default => 'Desconocido'
        };
    }

    // Métodos
    public function eliminarArchivo()
    {
        if ($this->archivo_path && Storage::exists($this->archivo_path)) {
            Storage::delete($this->archivo_path);
        }
    }

    public function marcarComoRevisado($revisorId, $estado, $comentarios = null)
    {
        $this->update([
            'estado' => $estado,
            'fecha_revision' => now(),
            'revisado_por' => $revisorId,
            'comentarios_rechazo' => $estado === 'rechazado' ? $comentarios : null
        ]);
    }
} 