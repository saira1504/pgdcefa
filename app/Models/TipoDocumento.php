<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    use HasFactory;

    protected $table = 'tipos_documento';

    protected $fillable = [
        'nombre',
        'descripcion',
        'obligatorio',
        'categoria',
        'activo'
    ];

    protected $casts = [
        'obligatorio' => 'boolean',
        'activo' => 'boolean'
    ];

    // Relaciones
    public function documentosAprendiz()
    {
        return $this->hasMany(DocumentoAprendiz::class, 'tipo_documento_id');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeObligatorios($query)
    {
        return $query->where('obligatorio', true);
    }

    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }
} 