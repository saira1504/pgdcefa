<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UnidadProductiva;
use App\Models\Tarea;
use App\Models\Entrega;
use App\Models\DocumentoAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function dashboard()
    {
        $admin = Auth::user();
        
        // Obtener unidades asignadas al admin
        $unidadesAsignadas = $admin->unidadesAsignadas()
            ->with(['aprendices', 'tareas'])
            ->get();

        // Métricas del admin
        $misUnidades = $unidadesAsignadas->count();
        $misAprendices = $unidadesAsignadas->sum(function($unidad) {
            return $unidad->aprendices->count();
        });
        
        $tareasPendientes = Tarea::delAdmin($admin->id)
            ->where('estado', 'pendiente')
            ->count();
            
        $documentosSubidos = DocumentoAdmin::delAdmin($admin->id)
            ->count();

        // Actividad reciente
        $actividadReciente = $this->getActividadReciente($admin->id);
        
        // Tareas urgentes
        $tareasUrgentes = Tarea::delAdmin($admin->id)
            ->proximasAVencer(3)
            ->with(['unidad', 'aprendiz'])
            ->limit(5)
            ->get();

        // Entregas de esta semana
        $entregasSemana = Entrega::whereHas('tarea', function($query) use ($admin) {
                $query->where('admin_id', $admin->id);
            })
            ->whereBetween('fecha_entrega', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->with(['aprendiz', 'tarea.unidad'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'unidadesAsignadas',
            'misUnidades',
            'misAprendices', 
            'tareasPendientes',
            'documentosSubidos',
            'actividadReciente',
            'tareasUrgentes',
            'entregasSemana'
        ));
    }

    public function unidadesProductivas()
    {
        $admin = Auth::user();
        
        $unidadesAsignadas = $admin->unidadesAsignadas()
            ->with(['aprendices', 'documentos', 'tareas'])
            ->get();

        // Estadísticas
        $misUnidades = $unidadesAsignadas->count();
        $misAprendices = $unidadesAsignadas->sum('aprendices_count');
        $misDocumentos = $unidadesAsignadas->sum('documentos_count');
        $miProgreso = $unidadesAsignadas->avg('progreso');

        return view('admin.unidades-productivas.index', compact(
            'unidadesAsignadas',
            'misUnidades',
            'misAprendices',
            'misDocumentos',
            'miProgreso'
        ));
    }

    public function lista()
    {
        $admin = Auth::user();
        
        // Solo aprendices de las unidades del admin
        $misAprendices = User::aprendicesDeAdmin($admin->id)
            ->with(['unidadAsignada', 'tareasAsignadas', 'entregas'])
            ->paginate(20);

        return view('admin.lista', compact('misAprendices'));
    }

    private function getActividadReciente($adminId)
    {
        $actividades = collect();

        // Entregas recientes
        $entregas = Entrega::whereHas('tarea', function($query) use ($adminId) {
                $query->where('admin_id', $adminId);
            })
            ->with(['aprendiz', 'tarea.unidad'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($entrega) {
                return [
                    'tipo' => 'entrega',
                    'descripcion' => "{$entrega->aprendiz->name} entregó '{$entrega->tarea->titulo}'",
                    'unidad' => $entrega->tarea->unidad->nombre,
                    'fecha' => $entrega->fecha_entrega,
                    'icono' => 'fas fa-upload',
                    'color' => 'success'
                ];
            });

        // Tareas creadas recientemente
        $tareas = Tarea::delAdmin($adminId)
            ->with(['unidad', 'aprendiz'])
            ->latest()
            ->limit(3)
            ->get()
            ->map(function($tarea) {
                return [
                    'tipo' => 'tarea',
                    'descripcion' => "Nueva tarea creada: '{$tarea->titulo}'",
                    'unidad' => $tarea->unidad->nombre,
                    'fecha' => $tarea->created_at,
                    'icono' => 'fas fa-tasks',
                    'color' => 'info'
                ];
            });

        return $actividades->merge($entregas)
            ->merge($tareas)
            ->sortByDesc('fecha')
            ->take(8);
    }
}
