<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminUnidad extends Model
{
    use HasFactory;

    protected $table = 'admin_unidades';

    protected $fillable = [
        'admin_id',
        'unidad_id',
        'fecha_asignacion',
        'activo'
    ];

    protected $casts = [
        'fecha_asignacion' => 'datetime',
        'activo' => 'boolean'
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
}
