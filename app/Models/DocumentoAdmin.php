<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DocumentoAdmin extends Model
{
    use HasFactory;

    protected $table = 'documentos_admin';

    protected $fillable = [
        'admin_id',
        'unidad_id',
        'titulo',
        'descripcion',
        'tipo_documento',
        'categoria',
        'archivo_path',
        'archivo_original',
        'mime_type',
        'tamaño_archivo',
        'prioridad',
        'requiere_entrega',
        'notificar_aprendices',
        'fecha_limite',
        'activo',
        'descargas'
    ];

    protected $casts = [
        'requiere_entrega' => 'boolean',
        'notificar_aprendices' => 'boolean',
        'fecha_limite' => 'date',
        'activo' => 'boolean',
        'tamaño_archivo' => 'integer',
        'descargas' => 'integer'
    ];

    // Relaciones
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function unidad()
    {
        return $this->belongsTo(UnidadProductiva::class, 'unidad_id');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeDelAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_documento', $tipo);
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
        return route('admin.documentos.descargar', $this->id);
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

    // Métodos
    public function incrementarDescargas()
    {
        $this->increment('descargas');
    }

    public function eliminarArchivo()
    {
        if ($this->archivo_path && Storage::exists($this->archivo_path)) {
            Storage::delete($this->archivo_path);
        }
    }
}
