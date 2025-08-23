<?php

namespace App\Http\Controllers;

use App\Models\UnidadProductiva;
use App\Models\User;
use App\Models\DocumentoAprendiz;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnidadProductivaController extends Controller
{
    public function index()
    {
        // Cargar unidades con el admin principal y conteos
        $unidadesProductivas = UnidadProductiva::with('adminPrincipal')
            ->withCount([
                'aprendices',
                'documentos',
                'documentosAprendiz',
                'documentosAprendiz as documentos_aprobados_count' => function ($q) {
                    $q->where('estado', 'aprobado');
                },
                'documentosAprendiz as documentos_pendientes_count' => function ($q) {
                    $q->where('estado', 'pendiente');
                },
                'documentosAprendiz as aprendices_con_documentos_count' => function ($q) {
                    $q->select(DB::raw('COUNT(DISTINCT aprendiz_id)'));
                },
            ])
            ->get();

        // Calcular progreso por unidad basado en documentos de aprendices aprobados
        foreach ($unidadesProductivas as $unidad) {
            $totalDocs = $unidad->documentos_aprendiz_count ?? 0;
            $aprobados = $unidad->documentos_aprobados_count ?? 0;
            $unidad->progreso = $totalDocs > 0 ? (int) round(($aprobados / $totalDocs) * 100) : (int) ($unidad->progreso ?? 0);
        }
        
        $gestores = User::where('role', 'admin')->get();
        $areas = Area::where('activo', true)->get();
        
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

        // 📊 ESTADÍSTICAS CORREGIDAS
        $totalUnidades = $unidadesProductivas->count();
        
        // 🎯 TOTAL DE APRENDICES GENERAL - TODOS LOS APRENDICES DEL SISTEMA
        $totalAprendices = User::where('role', 'aprendiz')->count();
        
        // 📄 TOTAL DE DOCUMENTOS - Solo documentos subidos por aprendices
        $totalDocumentos = DocumentoAprendiz::count();
        
        // 📈 PROGRESO PROMEDIO - Basado en las unidades
        $progresoPromedio = (int) round($unidadesProductivas->avg('progreso') ?? 0);

        return view('superadmin.unidades-productivas.index', compact(
            'unidadesProductivas',
            'gestores',
            'areas',
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

        $aprendicesConDocumentosAprobados = User::where('role', 'aprendiz')
            ->whereIn('id', function($query) use ($unidad) {
                $query->select('aprendiz_id')
                      ->from('documentos_aprendiz')
                      ->where('unidad_id', $unidad->id)
                      ->where('estado', '=', 'aprobado')
                      ->whereNotIn('estado', ['rechazado', 'pendiente'])
                      ->distinct();
            })
            ->get();

        foreach ($aprendicesConDocumentosAprobados as $aprendiz) {
            $aprendiz->documentos_aprobados_count = DocumentoAprendiz::where('aprendiz_id', $aprendiz->id)
                ->where('unidad_id', $unidad->id)
                ->where('estado', '=', 'aprobado')
                ->whereNotIn('estado', ['rechazado', 'pendiente'])
                ->count();
        }

        return view('superadmin.unidades-productivas.show', compact('unidad', 'aprendicesConDocumentosAprobados'));
    }

    public function edit(UnidadProductiva $unidad)
    {
        $gestores = User::where('role', 'admin')->get();
        $areas = Area::where('activo', true)->get();
        return view('superadmin.unidades-productivas.edit', compact('unidad', 'gestores', 'areas'));
    }

    public function update(Request $request, UnidadProductiva $unidad)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[a-zA-Z\p{L}\s]*$/u', $value)) {
                        $fail('El campo ' . $attribute . ' solo puede contener letras y espacios. No se permiten números ni caracteres especiales.');
                    }
                },
            ],
            'tipo' => 'required|string|max:50',
            'proyecto' => 'required|string',
            'gestor_id' => 'required|exists:users,id',
            'instructor_encargado' => 'required|string|max:100',
            'estado' => 'required|string',
            'objetivos' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $unidad) {
            $unidad->update([
                'nombre' => $request->nombre,
                'tipo' => $request->tipo,
                'proyecto' => $request->proyecto,
                'objetivos' => $request->objetivos,
                'estado' => $request->estado,
                'admin_principal_id' => $request->gestor_id,
                'instructor_encargado' => $request->instructor_encargado,
            ]);
        });

        return redirect()->route('superadmin.unidades-productivas.index')
            ->with('success', 'Unidad productiva actualizada correctamente.');
    }

    public function destroy(UnidadProductiva $unidad)
    {
        DB::transaction(function () use ($unidad) {
            $unidad->delete();
        });

        return redirect()->route('superadmin.unidades-productivas.index')
            ->with('success', 'Unidad productiva eliminada correctamente.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:20',
                'unique:unidades_productivas,nombre',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[a-zA-Z\p{L}\s]*$/u', $value)) {
                        $fail('El campo ' . $attribute . ' solo puede contener letras y espacios. No se permiten números ni caracteres especiales.');
                    }
                },
            ],
            'tipo' => 'required|string|max:50',
            'proyecto' => 'required|string',
            'gestor_id' => 'required|exists:users,id',
            'instructor_encargado' => 'required|string|max:100',
            'estado' => 'required|string',
            'objetivos' => 'nullable|string',
        ], [
            'nombre.unique' => 'Ya existe una unidad con este nombre. Por favor, elige un nombre diferente.',
        ]);

        $unidadCreada = null;

        DB::transaction(function () use ($request, &$unidadCreada) {
            // Crear la unidad productiva
            $unidadCreada = UnidadProductiva::create([
                'nombre' => $request->nombre,
                'tipo' => $request->tipo,
                'proyecto' => $request->proyecto,
                'objetivos' => $request->objetivos,
                'estado' => $request->estado,
                'admin_principal_id' => $request->gestor_id,
                'instructor_encargado' => $request->instructor_encargado,
                'activo' => true,
            ]);

            // Asignar el admin a la tabla pivote admin_unidades
            $unidadCreada->asignarAdmin($request->gestor_id);
        });

        if ($request->ajax()) {
            return response()->json([
                'ok' => true,
                'unidad' => [
                    'id' => $unidadCreada->id,
                    'nombre' => $unidadCreada->nombre,
                    'tipo' => $unidadCreada->tipo,
                ],
            ]);
        }

        return redirect()->route('superadmin.unidades-productivas.index')
            ->with('success', 'Unidad productiva creada y admin asignado correctamente.');
    }
}