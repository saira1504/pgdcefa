<?php

namespace App\Http\Controllers;

use App\Models\DocumentoAdmin;
use App\Models\UnidadProductiva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminDocumentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $admin = Auth::user();
        
        $documentos = DocumentoAdmin::delAdmin($admin->id)
            ->with(['unidad'])
            ->latest()
            ->paginate(15);

        $estadisticas = [
            'total' => DocumentoAdmin::delAdmin($admin->id)->count(),
            'activos' => DocumentoAdmin::delAdmin($admin->id)->activos()->count(),
            'descargas_total' => DocumentoAdmin::delAdmin($admin->id)->sum('descargas'),
            'tamaño_total' => DocumentoAdmin::delAdmin($admin->id)->sum('tamaño_archivo'),
        ];

        return view('admin.documentos.index', compact('documentos', 'estadisticas'));
    }

    public function create()
    {
        $admin = Auth::user();
        $misUnidades = $admin->unidadesAsignadas;
        
        return view('admin.documentos.create', compact('misUnidades'));
    }

    public function store(Request $request)
    {
        $admin = Auth::user();
        
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo_documento' => 'required|in:guia,manual,plantilla,evaluacion,recurso',
            'categoria' => 'required|in:teoria,practica,evaluacion,proyecto',
            'archivo' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx',
            'destino' => 'required|array',
            'destino.*' => 'exists:unidades_productivas,id',
            'prioridad' => 'required|in:normal,alta,urgente',
            'requiere_entrega' => 'boolean',
            'notificar_aprendices' => 'boolean',
            'fecha_limite' => 'nullable|date|after:today',
        ]);

        // Verificar que el admin tiene acceso a todas las unidades seleccionadas
        foreach ($request->destino as $unidadId) {
            if (!$admin->esAdminDe($unidadId)) {
                abort(403, 'No tienes permisos para subir documentos a una de las unidades seleccionadas.');
            }
        }

        $archivo = $request->file('archivo');
        $path = $archivo->store('documentos/admin', 'public');

        // Crear documento para cada unidad seleccionada
        foreach ($request->destino as $unidadId) {
            DocumentoAdmin::create([
                'admin_id' => $admin->id,
                'unidad_id' => $unidadId,
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'tipo_documento' => $request->tipo_documento,
                'categoria' => $request->categoria,
                'archivo_path' => $path,
                'archivo_original' => $archivo->getClientOriginalName(),
                'mime_type' => $archivo->getMimeType(),
                'tamaño_archivo' => $archivo->getSize(),
                'prioridad' => $request->prioridad,
                'requiere_entrega' => $request->boolean('requiere_entrega'),
                'notificar_aprendices' => $request->boolean('notificar_aprendices'),
                'fecha_limite' => $request->fecha_limite,
            ]);
        }

        return redirect()->route('admin.documentos.index')
            ->with('success', 'Documento subido exitosamente a ' . count($request->destino) . ' unidad(es).');
    }

    public function show(DocumentoAdmin $documento)
    {
        $this->authorize('view', $documento);
        
        return view('admin.documentos.show', compact('documento'));
    }

    public function edit(DocumentoAdmin $documento)
    {
        $this->authorize('update', $documento);
        
        $admin = Auth::user();
        $misUnidades = $admin->unidadesAsignadas;
        
        return view('admin.documentos.edit', compact('documento', 'misUnidades'));
    }

    public function update(Request $request, DocumentoAdmin $documento)
    {
        $this->authorize('update', $documento);
        
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo_documento' => 'required|in:guia,manual,plantilla,evaluacion,recurso',
            'categoria' => 'required|in:teoria,practica,evaluacion,proyecto',
            'prioridad' => 'required|in:normal,alta,urgente',
            'requiere_entrega' => 'boolean',
            'fecha_limite' => 'nullable|date',
            'activo' => 'boolean',
        ]);

        $documento->update($request->only([
            'titulo', 'descripcion', 'tipo_documento', 'categoria',
            'prioridad', 'requiere_entrega', 'fecha_limite', 'activo'
        ]));

        return redirect()->route('admin.documentos.show', $documento)
            ->with('success', 'Documento actualizado exitosamente.');
    }

    public function destroy(DocumentoAdmin $documento)
    {
        $this->authorize('delete', $documento);
        
        $documento->eliminarArchivo();
        $documento->delete();

        return redirect()->route('admin.documentos.index')
            ->with('success', 'Documento eliminado exitosamente.');
    }

    public function descargar(DocumentoAdmin $documento)
    {
        $this->authorize('view', $documento);
        
        $documento->incrementarDescargas();
        
        return Storage::disk('public')->download(
            $documento->archivo_path,
            $documento->archivo_original
        );
    }
}
