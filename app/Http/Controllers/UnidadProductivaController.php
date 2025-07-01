<?php

namespace App\Http\Controllers;

use App\Models\UnidadProductiva;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnidadProductivaController extends Controller
{
    public function index()
    {
        // Cargar unidades con el admin principal y los conteos de aprendices y documentos
        $unidadesProductivas = UnidadProductiva::with('adminPrincipal')
                                                ->withCount(['aprendices', 'documentos'])
                                                ->get();
        $gestores = User::where('role', 'admin')->get(); // Obtener solo usuarios con rol 'admin'
        
        // Definir categorías de documentos
        $categoriasDocumento = [
            'Documento',
            'Informe',
            'Cronograma',
            'Manual',
            'Reglamento',
            'Contrato',
            'Anexo',
            'Otro',
        ];

        // Calcular estadísticas rápidas
        $totalUnidades = $unidadesProductivas->count();
        $totalAprendices = User::where('role', 'aprendiz')->count(); // Contar todos los usuarios con rol 'aprendiz'
        $totalDocumentos = $unidadesProductivas->sum('documentos_count');
        $progresoPromedio = $unidadesProductivas->avg('progreso');

        return view('superadmin.unidades-productivas.index', compact(
            'unidadesProductivas',
            'gestores',
            'totalUnidades',
            'totalAprendices',
            'totalDocumentos',
            'progresoPromedio',
            'categoriasDocumento'
        ));
    }

    public function show(UnidadProductiva $unidad)
    {
        // Cargar la unidad con todas sus relaciones para la vista de detalles
        $unidad->load(['adminPrincipal', 'aprendices', 'tareas', 'documentos']);

        return view('superadmin.unidades-productivas.show', compact('unidad'));
    }

    public function edit(UnidadProductiva $unidad)
    {
        $gestores = User::where('role', 'admin')->get();
        return view('superadmin.unidades-productivas.edit', compact('unidad', 'gestores'));
    }

    public function update(Request $request, UnidadProductiva $unidad)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[a-zA-Z\s]*$/u', $value)) {
                        $fail('El campo ' . $attribute . ' solo puede contener letras y espacios. No se permiten números ni caracteres especiales.');
                    }
                },
            ],
            'tipo' => 'required|string|max:50',
            'proyecto' => 'required|string',
            'gestor_id' => 'required|exists:users,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => 'required|string',
            'objetivos' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $unidad) {
            $unidad->update([
                'nombre' => $request->nombre,
                'tipo' => $request->tipo,
                'proyecto' => $request->proyecto,
                'objetivos' => $request->objetivos,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'estado' => $request->estado,
                'admin_principal_id' => $request->gestor_id,
            ]);

            // Sincronizar la relación en la tabla pivote si es necesario
            // Si solo hay un admin principal, el syncWithoutDetaching ya maneja esto.
            // Si se pudiera asignar múltiples admins, sería más complejo.
        });

        return redirect()->route('superadmin.unidades-productivas.index')
            ->with('success', 'Unidad productiva actualizada correctamente.');
    }

    public function destroy(UnidadProductiva $unidad)
    {
        DB::transaction(function () use ($unidad) {
            // Opcional: Desvincular aprendices y tareas antes de eliminar si tienen restricciones FOREIGN KEY
            // $unidad->aprendices()->sync([]);
            // $unidad->tareas()->delete();
            
            $unidad->delete();
        });

        return redirect()->route('superadmin.unidades-productivas.index')
            ->with('success', 'Unidad productiva eliminada correctamente.');
    }

    // Método para crear una nueva unidad productiva y asignar admin principal
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[a-zA-Z\s]*$/u', $value)) {
                        $fail('El campo ' . $attribute . ' solo puede contener letras y espacios. No se permiten números ni caracteres especiales.');
                    }
                },
            ],
            'tipo' => 'required|string|max:50',
            'proyecto' => 'required|string',
            'gestor_id' => 'required|exists:users,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => 'required|string',
            'objetivos' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            // Crear la unidad productiva
            $unidad = UnidadProductiva::create([
                'nombre' => $request->nombre,
                'tipo' => $request->tipo,
                'proyecto' => $request->proyecto,
                'objetivos' => $request->objetivos,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'estado' => $request->estado,
                'admin_principal_id' => $request->gestor_id,
                'activo' => true,
            ]);

            // Asignar el admin a la tabla pivote admin_unidades
            $unidad->asignarAdmin($request->gestor_id);
        });

        return redirect()->route('superadmin.unidades-productivas.index')
            ->with('success', 'Unidad productiva creada y admin asignado correctamente.');
    }
} 