<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    use HasFactory;

    protected $fillable = [
        'tarea_id',
        'aprendiz_id',
        'archivo_path',
        'archivo_original',
        'comentarios',
        'calificacion',
        'retroalimentacion',
        'estado',
        'fecha_entrega',
        'fecha_revision'
    ];

    protected $casts = [
        'calificacion' => 'decimal:2',
        'fecha_entrega' => 'datetime',
        'fecha_revision' => 'datetime'
    ];

    // Relaciones
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    public function aprendiz()
    {
        return $this->belongsTo(User::class, 'aprendiz_id');
    }

    // Scopes
    public function scopePorRevisar($query)
    {
        return $query->where('estado', 'entregado');
    }

    public function scopeAprobadas($query)
    {
        return $query->where('estado', 'aprobado');
    }

    public function scopeRechazadas($query)
    {
        return $query->where('estado', 'rechazado');
    }

    // Accessors
    public function getCalificacionTextoAttribute()
    {
        if (!$this->calificacion) return 'Sin calificar';
        
        return match(true) {
            $this->calificacion >= 4.5 => 'Excelente',
            $this->calificacion >= 4.0 => 'Sobresaliente',
            $this->calificacion >= 3.5 => 'Bueno',
            $this->calificacion >= 3.0 => 'Aceptable',
            default => 'Insuficiente'
        };
    }

    public function getEstadoColorAttribute()
    {
        return match($this->estado) {
            'entregado' => 'warning',
            'en_revision' => 'info',
            'aprobado' => 'success',
            'rechazado' => 'danger',
            'reentrega' => 'warning',
            default => 'secondary'
        };
    }
}
