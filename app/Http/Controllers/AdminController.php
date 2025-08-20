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

        // Obtener aprendices que no están asignados a ninguna unidad
        $aprendicesSinAsignar = User::where('role', 'aprendiz')
            ->doesntHave('unidadAsignada') // Asegura que no tenga ninguna asignación activa
            ->get();

        return view('admin.unidades-productivas', compact(
            'unidadesAsignadas',
            'misUnidades',
            'misAprendices',
            'misDocumentos',
            'miProgreso',
            'aprendicesSinAsignar' // Pasar la variable a la vista
        ));
    }

    public function asignarAprendiz(Request $request)
    {
        $request->validate([
            'unidad_id' => ['required', 'exists:unidades_productivas,id'],
            'aprendiz_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    // Verificar que el aprendiz no esté ya asignado a una unidad
                    $aprendiz = User::find($value);
                    if ($aprendiz && $aprendiz->unidadAsignada()->exists()) {
                        $fail('El aprendiz ya está asignado a una unidad.');
                    }
                },
            ],
        ]);

        $admin = Auth::user();
        $unidad = UnidadProductiva::findOrFail($request->unidad_id);

        // Asegurarse de que el admin actual es gestor de esta unidad
        if (!$admin->esAdminDe($unidad->id) && $unidad->admin_principal_id !== $admin->id) {
            abort(403, 'No tienes permiso para asignar aprendices a esta unidad.');
        }

        $unidad->asignarAprendiz($request->aprendiz_id);

        return redirect()->back()->with('success', 'Aprendiz asignado correctamente a la unidad.');
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
