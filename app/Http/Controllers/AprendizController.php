<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User; // Added this import for User model

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
        $misDocumentos = \App\Models\DocumentoAprendiz::where('aprendiz_id', $aprendiz->id)
            ->where('unidad_id', $unidadAsignada->id)
            ->get();
        $progresoReal = $this->calcularProgresoReal($aprendiz->id, $unidadAsignada->id);
        $documentosCompletados = $misDocumentos->where('estado', 'aprobado')->count();
        $totalDocumentos = count($documentosRequeridos);
        return view('aprendiz.mi-unidad', [
            'unidadAsignada' => $unidadAsignada,
            'documentosRequeridos' => $documentosRequeridos,
            'misDocumentos' => $misDocumentos,
            'progresoReal' => $progresoReal,
            'documentosCompletados' => $documentosCompletados,
            'totalDocumentos' => $totalDocumentos
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
            $archivo = $request->file('documento');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $ruta = $archivo->storeAs('documentos/aprendiz/' . $aprendiz->id, $nombreArchivo, 'public');

            // Aquí guardarías en la base de datos
            // Por ahora simulamos el guardado

            return back()->with('success', 'Documento subido exitosamente');
        } catch (\Exception $e) {
            $mensajeError = 'Error al subir el documento: ' . $e->getMessage();
            
            // Si es un error de tabla no encontrada, dar instrucciones específicas
            if (str_contains($e->getMessage(), 'Base table or view not found') || 
                str_contains($e->getMessage(), 'doesn\'t exist')) {
                $mensajeError = 'Error: Las tablas de la base de datos no están creadas. ' .
                               'Por favor, ejecuta: php artisan migrate';
            }
            
            return back()->with('error', $mensajeError);
        }
    }

    public function documentosRequeridos()
    {
        $aprendiz = Auth::user();
        
        // El aprendiz puede subir documentos sin restricción de unidad
        // Si no tiene unidad asignada, se le permite crear documentos "sin asignar"
        $unidadAsignada = $this->getUnidadAsignada($aprendiz->id);
        
        // Obtener TODAS las unidades productivas disponibles para que el aprendiz pueda seleccionar
        $todasLasUnidades = \App\Models\UnidadProductiva::where('activo', true)->get();
        
        // Obtener documentos subidos reales del aprendiz
        $documentosRequeridos = \App\Models\DocumentoAprendiz::where('aprendiz_id', $aprendiz->id)->get();
        
        $fases = $this->getFasesDisponibles();
        $faseActual = $this->getFaseActual();
        $documentosEnviados = session('documentos_enviados', []);
        
        return view('aprendiz.documentos-requeridos', [
            'unidadAsignada' => $unidadAsignada,
            'todasLasUnidades' => $todasLasUnidades,
            'documentosRequeridos' => $documentosRequeridos,
            'fases' => $fases,
            'faseActual' => $faseActual,
            'documentosEnviados' => $documentosEnviados
        ]);
    }

    public function subirDocumentoRequerido(Request $request)
    {
        $request->validate([
            'fase_id' => 'required|integer',
            'unidad_id' => 'nullable|integer', // Ahora es opcional
            'descripcion' => 'required|string|max:255',
            'documento' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);
        
        $aprendiz = Auth::user();
        $unidadId = $request->input('unidad_id'); // Puede ser null
        $faseId = $request->input('fase_id');
        $descripcion = $request->input('descripcion');
        
        try {
            $archivo = $request->file('documento');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $ruta = $archivo->storeAs('documentos/aprendiz/' . $aprendiz->id, $nombreArchivo, 'public');
            
            // Verificar si la tabla tipos_documento existe y crear el tipo de documento
            $tipoDocumento = null;
            try {
                // Buscar si ya existe un tipo de documento para esta fase
                $tipoDocumento = \App\Models\TipoDocumento::where('nombre', 'Fase ' . $faseId)->first();
                
                if (!$tipoDocumento) {
                    // Crear un nuevo tipo de documento para esta fase
                    $tipoDocumento = new \App\Models\TipoDocumento();
                    $tipoDocumento->nombre = 'Fase ' . $faseId;
                    $tipoDocumento->descripcion = 'Documento de la Fase ' . $faseId;
                    $tipoDocumento->obligatorio = true;
                    $tipoDocumento->categoria = 'proyecto';
                    $tipoDocumento->activo = true;
                    $tipoDocumento->save();
                }
            } catch (\Exception $e) {
                // Si hay error con la tabla tipos_documento, usar un ID por defecto
                $tipoDocumento = (object) ['id' => 1];
            }
            
            $documento = new \App\Models\DocumentoAprendiz();
            $documento->aprendiz_id = $aprendiz->id;
            $documento->unidad_id = $unidadId; // Puede ser null si no hay unidad asignada
            $documento->tipo_documento_id = $tipoDocumento->id; // Usar el ID del tipo de documento creado
            
            $fase = \App\Models\Phase::find($faseId);
            $documento->titulo = 'Documento Fase ' . ($fase ? $fase->numero : $faseId);
            $documento->descripcion = $descripcion;
            $documento->archivo_path = $ruta;
            $documento->archivo_original = $archivo->getClientOriginalName();
            $documento->mime_type = $archivo->getClientMimeType();
            $documento->tamaño_archivo = $archivo->getSize();
            $documento->estado = 'pendiente';
            $documento->fecha_subida = now();
            $documento->save();
            
            // Guardar en la sesión para mostrar en Documentos Enviados
            $documentos = session('documentos_enviados', []);
            $documentos[] = [
                'fase_id' => $faseId,
                'unidad_id' => $unidadId,
                'descripcion' => $descripcion,
                'archivo_original' => $archivo->getClientOriginalName(),
                'fecha_subida' => now()->format('d/m/Y H:i'),
            ];
            session(['documentos_enviados' => $documentos]);
            
            return redirect()->route('aprendiz.documentos-requeridos')->with('success', 'Documento subido exitosamente. Será revisado por el superadministrador.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al subir el documento: ' . $e->getMessage());
        }
    }

    public function descargarDocumento($documentoId)
    {
        $aprendiz = Auth::user();
        
        // Aquí verificarías que el documento pertenece al aprendiz
        // Por ahora simulamos la descarga
        
        return response()->download(storage_path('app/public/documentos/aprendiz/' . $aprendiz->id . '/documento.pdf'));
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
        // Obtener la unidad asignada real desde la base de datos
        $aprendiz = User::find($aprendizId);
        
        if (!$aprendiz) {
            return $this->getUnidadTemporal();
        }
        
        // Obtener la unidad asignada usando la relación del modelo User
        $unidadAsignada = $aprendiz->unidadAsignada()->first();
        
        if (!$unidadAsignada) {
            return $this->getUnidadTemporal();
        }
        
        // Retornar la unidad con información adicional
        return (object) [
            'id' => $unidadAsignada->id,
            'nombre' => $unidadAsignada->nombre,
            'descripcion' => $unidadAsignada->descripcion ?? 'Proyecto productivo del SENA',
            'gestor_nombre' => $unidadAsignada->adminPrincipal ? $unidadAsignada->adminPrincipal->name : 'No asignado',
            'estado' => $unidadAsignada->estado ?? 'Activa',
            'tipo' => $unidadAsignada->tipo ?? 'Proyecto productivo',
            'total_aprendices' => $unidadAsignada->aprendices()->count(),
            'total_documentos' => $unidadAsignada->documentos()->count(),
            'fecha_asignacion' => $unidadAsignada->pivot ? $unidadAsignada->pivot->fecha_asignacion : now()->format('Y-m-d')
        ];
    }

    private function getUnidadTemporal()
    {
        // Retornar una unidad temporal para evitar errores en la vista
        return (object) [
            'id' => null,
            'nombre' => 'Sin Unidad Asignada',
            'descripcion' => 'No tienes una unidad productiva asignada actualmente',
            'gestor_nombre' => 'Por asignar',
            'estado' => 'Pendiente',
            'tipo' => 'Sin asignar',
            'total_aprendices' => 0,
            'total_documentos' => 0,
            'fecha_asignacion' => now()->format('Y-m-d')
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

    private function getDocumentosRequeridosConFases($unidadId)
    {
        // Obtener documentos requeridos desde la base de datos
        // Aquí deberías obtener los documentos requeridos vinculados a la unidad
        // Por ahora, simulamos algunos documentos pero con fases reales
        
        $fases = \App\Models\Phase::orderBy('numero', 'asc')->get();
        $documentos = collect();
        
        // Simular documentos requeridos pero usando las fases reales de la BD
        foreach ($fases as $index => $fase) {
            $documentos->push((object) [
                'id' => $index + 1,
                'nombre' => 'Documento Requerido ' . ($index + 1),
                'descripcion' => 'Documento requerido para la Fase ' . $fase->numero,
                'fase_id' => $fase->id,
                'fecha_limite' => $fase->fecha_fin ? $fase->fecha_fin->subDays(5) : now()->addDays(10),
                'estado' => $this->getEstadoAleatorio(),
                'archivo_subido' => $this->getArchivoAleatorio(),
                'fecha_subida' => $this->getFechaSubidaAleatoria(),
                'comentarios_rechazo' => $this->getComentariosRechazoAleatorio()
            ]);
        }
        
        return $documentos;
    }

    private function getEstadoAleatorio()
    {
        $estados = ['pendiente', 'subido', 'aprobado', 'rechazado'];
        return $estados[array_rand($estados)];
    }

    private function getArchivoAleatorio()
    {
        $archivos = [null, 'documento_requerido.pdf', 'informe_proyecto.pdf', 'analisis_mercado.pdf'];
        return $archivos[array_rand($archivos)];
    }

    private function getFechaSubidaAleatoria()
    {
        $fechas = [null, now()->subDays(2), now()->subDays(5), now()->subDays(8)];
        return $fechas[array_rand($fechas)];
    }

    private function getComentariosRechazoAleatorio()
    {
        $comentarios = [
            null,
            'El documento no cumple con los requisitos especificados.',
            'Faltan datos importantes en el análisis.',
            'Por favor, incluye información más detallada.'
        ];
        return $comentarios[array_rand($comentarios)];
    }

    private function getFasesDisponibles()
    {
        // Obtener todas las fases desde la base de datos
        $fases = \App\Models\Phase::orderBy('numero', 'asc')->get();
        
        return $fases->map(function($fase) {
            return (object) [
                'id' => $fase->id,
                'numero' => $fase->numero,
                'nombre' => 'Fase ' . $fase->numero, // Generar nombre basado en el número
                'descripcion' => 'Descripción de la fase ' . $fase->numero, // Generar descripción
                'fecha_inicio' => $fase->fecha_inicio,
                'fecha_fin' => $fase->fecha_fin,
                'estado' => $this->determinarEstadoFase($fase)
            ];
        });
    }

    private function getFaseActual()
    {
        // Obtener la fase actual desde la base de datos
        // Buscar la fase que está actualmente en progreso
        $faseActual = \App\Models\Phase::where(function($query) {
                $query->where('fecha_inicio', '<=', now())
                      ->where('fecha_fin', '>=', now());
            })
            ->orderBy('numero', 'asc')
            ->first();

        if (!$faseActual) {
            // Si no hay fase activa, buscar la última fase creada
            $faseActual = \App\Models\Phase::orderBy('numero', 'desc')->first();
        }

        if ($faseActual) {
            return (object) [
                'id' => $faseActual->id,
                'numero' => $faseActual->numero,
                'nombre' => 'Fase ' . $faseActual->numero,
                'descripcion' => 'Descripción de la fase ' . $faseActual->numero . '. Esta es la fase actual del proyecto.',
                'fecha_inicio' => $faseActual->fecha_inicio,
                'fecha_fin' => $faseActual->fecha_fin,
                'estado' => $this->determinarEstadoFase($faseActual)
            ];
        }

        // Si no hay fases en la base de datos, retornar null
        return null;
    }

    private function determinarEstadoFase($fase)
    {
        $now = now();
        
        if ($now < $fase->fecha_inicio) {
            return 'Pendiente';
        } elseif ($now > $fase->fecha_fin) {
            return 'Completada';
        } else {
            return 'En Progreso';
        }
    }

    public function actualizarDocumento(Request $request, $id)
    {
        $request->validate([
            'documento' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'descripcion' => 'nullable|string|max:500'
        ]);

        $aprendiz = Auth::user();
        
        // Buscar el documento del aprendiz
        $documento = \App\Models\DocumentoAprendiz::where('id', $id)
            ->where('aprendiz_id', $aprendiz->id)
            ->firstOrFail();

        // Verificar que el documento esté en un estado editable
        if (!in_array($documento->estado, ['en_revision', 'pendiente'])) {
            return redirect()->back()->with('error', 'Solo puedes editar documentos que estén en revisión o pendientes.');
        }

        $data = [];

        // Actualizar descripción si se proporciona
        if ($request->has('descripcion')) {
            $data['descripcion'] = $request->descripcion;
        }

        // Manejar nuevo archivo si se proporciona
        if ($request->hasFile('documento')) {
            $file = $request->file('documento');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Eliminar archivo anterior si existe
            if ($documento->ruta_archivo && Storage::exists($documento->ruta_archivo)) {
                Storage::delete($documento->ruta_archivo);
            }
            
            // Guardar nuevo archivo
            $rutaArchivo = $file->storeAs('documentos_aprendiz', $fileName, 'public');
            $data['ruta_archivo'] = $rutaArchivo;
            $data['nombre_archivo'] = $fileName;
            
            // Resetear estado a pendiente si se cambia el archivo
            $data['estado'] = 'pendiente';
        }

        // Actualizar el documento
        $documento->update($data);

        return redirect()->back()->with('success', 'Documento actualizado correctamente.');
    }

    public function actualizarDocumentoRequerido(Request $request, $id)
    {
        $request->validate([
            'archivo' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'descripcion' => 'nullable|string|max:500'
        ]);

        $aprendiz = Auth::user();
        
        // Buscar el documento requerido del aprendiz
        // Nota: Esto asume que tienes un modelo DocumentoRequerido o similar
        // Si usas un modelo diferente, ajusta según tu estructura
        $documento = \App\Models\DocumentoAprendiz::where('id', $id)
            ->where('aprendiz_id', $aprendiz->id)
            ->firstOrFail();

        // Verificar que el documento esté en un estado editable
        if (!in_array($documento->estado, ['pendiente', 'en_revision', 'subido'])) {
            return redirect()->back()->with('error', 'Solo puedes editar documentos que estén pendientes, en revisión o subidos.');
        }

        $data = [];

        // Actualizar descripción si se proporciona
        if ($request->has('descripcion')) {
            $data['descripcion'] = $request->descripcion;
        }

        // Manejar nuevo archivo si se proporciona
        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Eliminar archivo anterior si existe
            if ($documento->archivo_path && Storage::exists($documento->archivo_path)) {
                Storage::delete($documento->archivo_path);
            }
            
            // Guardar nuevo archivo
            $rutaArchivo = $file->storeAs('documentos_requeridos', $fileName, 'public');
            $data['archivo_path'] = $rutaArchivo;
            $data['archivo_original'] = $fileName;
            
            // Resetear estado a pendiente si se cambia el archivo
            $data['estado'] = 'pendiente';
        }

        // Actualizar el documento
        $documento->update($data);

        return redirect()->back()->with('success', 'Documento requerido actualizado correctamente.');
    }
}
