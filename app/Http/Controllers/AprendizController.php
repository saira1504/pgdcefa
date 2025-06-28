<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AprendizController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:aprendiz']);
    }

    public function dashboard()
    {
        $aprendiz = Auth::user();
        
        // Datos simulados pero realistas basados en tus wireframes
        $unidadAsignada = $this->getUnidadAsignada($aprendiz->id);
        
        if (!$unidadAsignada) {
            return view('aprendiz.dashboard', [
                'unidadAsignada' => null,
                'documentosPendientes' => 0,
                'progresoReal' => 0,
                'proximasEntregas' => [],
                'documentosPendientesList' => []
            ]);
        }

        $documentosPendientes = $this->getDocumentosPendientes($aprendiz->id, $unidadAsignada->id);
        $progresoReal = $this->calcularProgresoReal($aprendiz->id, $unidadAsignada->id);
        $proximasEntregas = $this->getProximasEntregasReales($aprendiz->id, $unidadAsignada->id);

        return view('aprendiz.dashboard', [
            'unidadAsignada' => $unidadAsignada,
            'documentosPendientes' => count($documentosPendientes),
            'progresoReal' => $progresoReal,
            'proximasEntregas' => $proximasEntregas,
            'documentosPendientesList' => $documentosPendientes
        ]);
    }

    public function miUnidad()
    {
        $aprendiz = Auth::user();
        $unidadAsignada = $this->getUnidadAsignada($aprendiz->id);
        
        if (!$unidadAsignada) {
            return redirect()->route('aprendiz.dashboard')->with('error', 'No tienes una unidad asignada');
        }

        $documentosRequeridos = $this->getDocumentosRequeridos($unidadAsignada->id);
        $misDocumentos = $this->getMisDocumentos($aprendiz->id, $unidadAsignada->id);
        $progresoReal = $this->calcularProgresoReal($aprendiz->id, $unidadAsignada->id);

        return view('aprendiz.mi-unidad', [
            'unidadAsignada' => $unidadAsignada,
            'documentosRequeridos' => $documentosRequeridos,
            'misDocumentos' => $misDocumentos,
            'progresoReal' => $progresoReal,
            'documentosCompletados' => $misDocumentos->where('estado', 'aprobado')->count(),
            'totalDocumentos' => count($documentosRequeridos)
        ]);
    }

    public function documentos()
    {
        $aprendiz = Auth::user();
        $unidadAsignada = $this->getUnidadAsignada($aprendiz->id);
        
        if (!$unidadAsignada) {
            return redirect()->route('aprendiz.dashboard')->with('error', 'No tienes una unidad asignada');
        }

        $documentosPendientes = $this->getDocumentosPendientes($aprendiz->id, $unidadAsignada->id);
        $misDocumentos = $this->getMisDocumentos($aprendiz->id, $unidadAsignada->id);

        return view('aprendiz.documentos', [
            'unidadAsignada' => $unidadAsignada,
            'documentosPendientes' => $documentosPendientes,
            'misDocumentos' => $misDocumentos
        ]);
    }

    public function subirDocumento(Request $request)
    {
        $request->validate([
            'documento' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'tipo_documento_id' => 'required|integer',
            'descripcion' => 'nullable|string|max:500'
        ]);

        $aprendiz = Auth::user();
        $unidadAsignada = $this->getUnidadAsignada($aprendiz->id);

        if (!$unidadAsignada) {
            return back()->with('error', 'No tienes una unidad asignada');
        }

        try {
            // Subir archivo
            $file = $request->file('documento');
            $filename = time() . '_' . $aprendiz->id . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('documentos/aprendices/' . $aprendiz->id, $filename, 'public');

            // Aquí conectarías con tu base de datos real
            // Por ahora simularemos que se guardó correctamente

            return back()->with('success', 'Documento subido exitosamente. Está pendiente de revisión por tu gestor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al subir el documento. Inténtalo nuevamente.');
        }
    }

    public function progreso()
    {
        $aprendiz = Auth::user();
        $unidadAsignada = $this->getUnidadAsignada($aprendiz->id);
        
        if (!$unidadAsignada) {
            return redirect()->route('aprendiz.dashboard')->with('error', 'No tienes una unidad asignada');
        }

        $progresoDetallado = $this->getProgresoDetallado($aprendiz->id, $unidadAsignada->id);

        return view('aprendiz.progreso', [
            'unidadAsignada' => $unidadAsignada,
            'progreso' => $progresoDetallado
        ]);
    }

    // ===== MÉTODOS PRIVADOS CON DATOS SIMULADOS =====
    private function getUnidadAsignada($aprendizId)
    {
        // Simular datos de unidad asignada basados en tus wireframes
        return (object) [
            'id' => 1,
            'nombre' => 'Unidad 1 - Avícola',
            'descripcion' => 'Proyecto de producción y comercialización',
            'gestor_nombre' => 'María González',
            'estado' => 'Activa',
            'tipo' => 'Proyecto productivo',
            'total_aprendices' => 21,
            'total_documentos' => 11,
            'fecha_asignacion' => '2025-01-15'
        ];
    }

    private function getDocumentosPendientes($aprendizId, $unidadId)
    {
        // Simular documentos pendientes
        return collect([
            (object) ['id' => 1, 'nombre' => 'Informe de Avance', 'descripcion' => 'Informe mensual de progreso', 'fecha_limite' => '2025-04-25'],
            (object) ['id' => 2, 'nombre' => 'Cronograma de Actividades', 'descripcion' => 'Planificación de tareas', 'fecha_limite' => '2025-04-27'],
            (object) ['id' => 3, 'nombre' => 'Presupuesto del Proyecto', 'descripcion' => 'Análisis financiero', 'fecha_limite' => null]
        ]);
    }

    private function calcularProgresoReal($aprendizId, $unidadId)
    {
        // Simular cálculo de progreso
        return 75; // 75% completado
    }

    private function getProximasEntregasReales($aprendizId, $unidadId)
    {
        // Simular próximas entregas
        return collect([
            (object) ['id' => 1, 'documento' => 'Informe de Avance', 'fecha_limite' => '2025-04-25'],
            (object) ['id' => 2, 'documento' => 'Cronograma de Actividades', 'fecha_limite' => '2025-04-27']
        ]);
    }

    private function getDocumentosRequeridos($unidadId)
    {
        // Simular documentos requeridos
        return collect([
            (object) ['id' => 1, 'nombre' => 'Informe de Avance', 'obligatorio' => true, 'fecha_limite' => '2025-04-25'],
            (object) ['id' => 2, 'nombre' => 'Cronograma de Actividades', 'obligatorio' => true, 'fecha_limite' => '2025-04-27'],
            (object) ['id' => 3, 'nombre' => 'Presupuesto del Proyecto', 'obligatorio' => false, 'fecha_limite' => null],
            (object) ['id' => 4, 'nombre' => 'Análisis de Mercado', 'obligatorio' => true, 'fecha_limite' => '2025-05-15']
        ]);
    }

    private function getMisDocumentos($aprendizId, $unidadId)
    {
        // Simular documentos ya subidos
        return collect([
            (object) [
                'id' => 1,
                'tipo_documento_id' => 4,
                'nombre_archivo' => 'analisis_mercado.pdf',
                'ruta_archivo' => 'documentos/aprendices/1/analisis_mercado.pdf',
                'estado' => 'aprobado',
                'descripcion' => 'Análisis completo del mercado avícola',
                'fecha_subida' => now()->subDays(5),
                'tipoDocumento' => (object) ['nombre' => 'Análisis de Mercado']
            ]
        ]);
    }

    private function getProgresoDetallado($aprendizId, $unidadId)
    {
        return [
            'general' => 75,
            'documentos_aprobados' => 1,
            'documentos_pendientes' => 3,
            'documentos_en_revision' => 0,
            'documentos_rechazados' => 0
        ];
    }
}
