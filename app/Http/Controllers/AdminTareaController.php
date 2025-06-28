<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Entrega;
use App\Models\UnidadProductiva;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminTareaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $admin = Auth::user();
        
        $tareas = Tarea::delAdmin($admin->id)
            ->with(['unidad', 'aprendiz', 'entregas'])
            ->latest()
            ->paginate(15);

        $estadisticas = [
            'total' => Tarea::delAdmin($admin->id)->count(),
            'pendientes' => Tarea::delAdmin($admin->id)->pendientes()->count(),
            'vencidas' => Tarea::delAdmin($admin->id)->vencidas()->count(),
            'completadas' => Tarea::delAdmin($admin->id)->where('estado', 'aprobado')->count(),
        ];

        return view('admin.tareas.index', compact('tareas', 'estadisticas'));
    }

    public function create()
    {
        $admin = Auth::user();
        $misUnidades = $admin->unidadesAsignadas;
        
        return view('admin.tareas.create', compact('misUnidades'));
    }

    public function store(Request $request)
    {
        $admin = Auth::user();
        
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'unidad_id' => 'required|exists:unidades_productivas,id',
            'aprendiz_id' => 'nullable|exists:users,id',
            'tipo' => 'required|in:entrega,lectura,practica,evaluacion',
            'prioridad' => 'required|in:normal,alta,urgente',
            'fecha_limite' => 'nullable|date|after:today',
            'instrucciones' => 'nullable|string',
            'archivos.*' => 'nullable|file|max:10240', // 10MB max
        ]);

        // Verificar que el admin tiene acceso a la unidad
        if (!$admin->esAdminDe($request->unidad_id)) {
            abort(403, 'No tienes permisos para crear tareas en esta unidad.');
        }

        $archivosAdjuntos = [];
        if ($request->hasFile('archivos')) {
            foreach ($request->file('archivos') as $archivo) {
                $path = $archivo->store('tareas/adjuntos', 'public');
                $archivosAdjuntos[] = [
                    'nombre' => $archivo->getClientOriginalName(),
                    'path' => $path,
                    'tamaño' => $archivo->getSize(),
                ];
            }
        }

        $tarea = Tarea::create([
            'admin_id' => $admin->id,
            'unidad_id' => $request->unidad_id,
            'aprendiz_id' => $request->aprendiz_id,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'tipo' => $request->tipo,
            'prioridad' => $request->prioridad,
            'fecha_limite' => $request->fecha_limite,
            'instrucciones' => $request->instrucciones,
            'archivos_adjuntos' => $archivosAdjuntos,
        ]);

        // Si es para un aprendiz específico, notificar
        if ($tarea->aprendiz_id) {
            // Aquí puedes agregar lógica de notificación
        }

        return redirect()->route('admin.tareas.index')
            ->with('success', 'Tarea creada exitosamente.');
    }

    public function show(Tarea $tarea)
    {
        $this->authorize('view', $tarea);
        
        $tarea->load(['unidad', 'aprendiz', 'entregas.aprendiz']);
        
        return view('admin.tareas.show', compact('tarea'));
    }

    public function edit(Tarea $tarea)
    {
        $this->authorize('update', $tarea);
        
        $admin = Auth::user();
        $misUnidades = $admin->unidadesAsignadas;
        
        return view('admin.tareas.edit', compact('tarea', 'misUnidades'));
    }

    public function update(Request $request, Tarea $tarea)
    {
        $this->authorize('update', $tarea);
        
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:entrega,lectura,practica,evaluacion',
            'prioridad' => 'required|in:normal,alta,urgente',
            'fecha_limite' => 'nullable|date',
            'instrucciones' => 'nullable|string',
            'estado' => 'required|in:pendiente,entregado,en_revision,aprobado,rechazado',
        ]);

        $tarea->update($request->only([
            'titulo', 'descripcion', 'tipo', 'prioridad', 
            'fecha_limite', 'instrucciones', 'estado'
        ]));

        return redirect()->route('admin.tareas.show', $tarea)
            ->with('success', 'Tarea actualizada exitosamente.');
    }

    public function destroy(Tarea $tarea)
    {
        $this->authorize('delete', $tarea);
        
        // Eliminar archivos adjuntos
        if ($tarea->archivos_adjuntos) {
            foreach ($tarea->archivos_adjuntos as $archivo) {
                Storage::disk('public')->delete($archivo['path']);
            }
        }

        $tarea->delete();

        return redirect()->route('admin.tareas.index')
            ->with('success', 'Tarea eliminada exitosamente.');
    }

    public function revisarEntrega(Entrega $entrega)
    {
        $this->authorize('view', $entrega->tarea);
        
        return view('admin.entregas.revisar', compact('entrega'));
    }

    public function calificarEntrega(Request $request, Entrega $entrega)
    {
        $this->authorize('update', $entrega->tarea);
        
        $request->validate([
            'calificacion' => 'required|numeric|min:0|max:5',
            'retroalimentacion' => 'required|string',
            'estado' => 'required|in:aprobado,rechazado,reentrega',
        ]);

        $entrega->update([
            'calificacion' => $request->calificacion,
            'retroalimentacion' => $request->retroalimentacion,
            'estado' => $request->estado,
            'fecha_revision' => now(),
        ]);

        // Actualizar estado de la tarea
        $entrega->tarea->update([
            'estado' => $request->estado === 'aprobado' ? 'aprobado' : 'en_revision'
        ]);

        return redirect()->route('admin.tareas.show', $entrega->tarea)
            ->with('success', 'Entrega calificada exitosamente.');
    }
}
