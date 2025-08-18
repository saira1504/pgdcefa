<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relaciones para Admin
    public function unidadesAsignadas()
    {
        return $this->belongsToMany(UnidadProductiva::class, 'admin_unidades', 'admin_id', 'unidad_id')
                    ->withPivot('fecha_asignacion', 'activo')
                    ->wherePivot('activo', true);
    }

    public function unidadesPrincipales()
    {
        return $this->hasMany(UnidadProductiva::class, 'admin_principal_id');
    }

    public function tareasCreadas()
    {
        return $this->hasMany(Tarea::class, 'admin_id');
    }

    public function documentosSubidos()
    {
        return $this->hasMany(DocumentoAdmin::class, 'admin_id');
    }

    // Relaciones para Aprendiz
    public function unidadAsignada()
    {
        return $this->belongsToMany(UnidadProductiva::class, 'aprendiz_unidad', 'aprendiz_id', 'unidad_id')
                    ->withPivot('fecha_asignacion', 'activo')
                    ->wherePivot('activo', true);
    }

    public function aprendizUnidad()
    {
        return $this->belongsToMany(UnidadProductiva::class, 'aprendiz_unidad', 'aprendiz_id', 'unidad_id')
                    ->withPivot('fecha_asignacion', 'activo')
                    ->wherePivot('activo', true);
    }

    public function tareasAsignadas()
    {
        return $this->hasMany(Tarea::class, 'aprendiz_id');
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class, 'aprendiz_id');
    }

    // Scopes
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeAprendices($query)
    {
        return $query->where('role', 'aprendiz');
    }

    public function scopeSuperadmins($query)
    {
        return $query->where('role', 'superadmin');
    }

    public function scopeAprendicesDeAdmin($query, $adminId)
    {
        return $query->where('role', 'aprendiz')
                    ->whereHas('aprendizUnidad', function($q) use ($adminId) {
                        $q->whereHas('admins', function($q2) use ($adminId) {
                            $q2->where('admin_id', $adminId);
                        });
                    });
    }

    // Métodos de autorización
    public function puedeAccederA($recurso, $accion = 'view')
    {
        return match($this->role) {
            'superadmin' => true,
            'admin' => $this->puedeAdminAccederA($recurso, $accion),
            'aprendiz' => $this->puedeAprendizAccederA($recurso, $accion),
            default => false
        };
    }

    private function puedeAdminAccederA($recurso, $accion)
    {
        // Lógica específica para admin
        if ($recurso instanceof UnidadProductiva) {
            return $this->esAdminDe($recurso->id);
        }

        if ($recurso instanceof Tarea) {
            return $recurso->admin_id === $this->id;
        }

        if ($recurso instanceof DocumentoAdmin) {
            return $recurso->admin_id === $this->id;
        }

        return false;
    }

    private function puedeAprendizAccederA($recurso, $accion)
    {
        // Lógica específica para aprendiz
        if ($recurso instanceof Tarea) {
            return $recurso->aprendiz_id === $this->id || 
                   ($recurso->aprendiz_id === null && $this->unidadAsignada && $this->unidadAsignada->id === $recurso->unidad_id);
        }

        if ($recurso instanceof DocumentoAdmin) {
            return $this->unidadAsignada && 
                   $this->unidadAsignada->id === $recurso->unidad_id && 
                   $recurso->activo;
        }

        return false;
    }

    // Verificar si es admin de una unidad específica
    public function esAdminDe($unidadId)
    {
        return $this->unidadesAsignadas()->where('unidad_id', $unidadId)->exists();
    }

    // Obtener todas las unidades donde tiene permisos
    public function getUnidadesConPermisoAttribute()
    {
        return match($this->role) {
            'superadmin' => UnidadProductiva::all(),
            'admin' => $this->unidadesAsignadas,
            'aprendiz' => $this->unidadAsignada ? collect([$this->unidadAsignada]) : collect(),
            default => collect()
        };
    }
}
