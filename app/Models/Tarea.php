<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tarea extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'unidad_id',
        'aprendiz_id',
        'titulo',
        'descripcion',
        'tipo',
        'prioridad',
        'fecha_limite',
        'estado',
        'archivos_adjuntos',
        'instrucciones',
        'notificado'
    ];

    protected $casts = [
        'fecha_limite' => 'date',
        'archivos_adjuntos' => 'array',
        'notificado' => 'boolean'
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

    public function aprendiz()
    {
        return $this->belongsTo(User::class, 'aprendiz_id');
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class);
    }

    // Scopes
    public function scopeDelAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeVencidas($query)
    {
        return $query->where('fecha_limite', '<', now())
                    ->where('estado', 'pendiente');
    }

    public function scopeProximasAVencer($query, $dias = 3)
    {
        return $query->whereBetween('fecha_limite', [now(), now()->addDays($dias)])
                    ->where('estado', 'pendiente');
    }

    // Accessors
    public function getEsVencidaAttribute()
    {
        return $this->fecha_limite && $this->fecha_limite->isPast() && $this->estado === 'pendiente';
    }

    public function getDiasRestantesAttribute()
    {
        if (!$this->fecha_limite) return null;
        
        $dias = now()->diffInDays($this->fecha_limite, false);
        return $dias >= 0 ? $dias : 0;
    }

    public function getEstadoColorAttribute()
    {
        return match($this->estado) {
            'pendiente' => $this->es_vencida ? 'danger' : 'warning',
            'entregado' => 'info',
            'en_revision' => 'primary',
            'aprobado' => 'success',
            'rechazado' => 'danger',
            default => 'secondary'
        };
    }
}
