<?php

namespace App\Http\Controllers;

use App\Models\ListadoMaestro;
use App\Models\User;
use App\Notifications\DocumentoSubidoNotification;
use App\Notifications\DocumentoProcesadoNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Models\Area;

class MaestroController extends Controller
{
    // Mostrar listado de documentos según el rol
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'superadmin') {
            // Superadmin ve solo documentos pendientes
            $documentos = ListadoMaestro::where('estado', 'pendiente')->get();
            return view('superadmin.listado_maestro.index', compact('documentos'));
        } elseif ($user->role === 'admin') {
            // Admin ve todos los documentos
            $documentos = ListadoMaestro::all();
            return view('admin.listado_maestro.index', compact('documentos'));
        } else {
            // Otros roles no tienen acceso
            abort(403, 'Acceso denegado');
        }
    }

    // Método específico para admin
    public function adminIndex()
    {
        $this->authorize('viewAny', ListadoMaestro::class);
        $areas = Area::where('activo', true)->orderBy('nombre')->get();
        $areaId = request('area');
        $query = ListadoMaestro::query();
        if (!empty($areaId)) {
            $query->where('area_id', $areaId);
        }
        $documentos = $query->get();
        return view('admin.listado_maestro.index', compact('documentos', 'areas', 'areaId'));
    }

    // Método específico para superadmin
    public function superadminIndex()
    {
        $this->authorize('viewAny', ListadoMaestro::class);
        
        $areas = Area::where('activo', true)->orderBy('nombre')->get();
        $areaId = request('area');
        $query = ListadoMaestro::query();
        
        if (!empty($areaId)) {
            $query->where('area_id', $areaId);
        }
        
        $documentos = $query->get();
        return view('superadmin.listado_maestro.index', compact('documentos', 'areas', 'areaId'));
    }

    // Guardar nuevo documento (solo admin)
    public function store(Request $request)
    {
        // Verificar que solo los admins puedan subir documentos
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Solo los administradores pueden subir documentos');
        }

        $validated = $request->validate([
            'tipo_proceso' => ['required', 'string', 'max:255'],
            'nombre_proceso' => ['required', 'string', 'max:255'],
            'subproceso_sig_subsistema' => ['nullable', 'string', 'max:255'],
            'documentos' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:20480'], // 20MB
            'numero_doc' => ['nullable', 'string', 'max:255'],
            'responsable' => ['required', 'string', 'max:255'],
            'tipo_documento' => ['nullable', 'string', 'max:50'],
            'nombre_documento' => ['nullable', 'string', 'max:255'],
            'codigo' => ['nullable', 'string', 'max:255'],
            'version' => ['nullable', 'string', 'max:50'],
            'fecha_creacion' => ['required', 'date'],
            'area_id' => ['required', 'exists:areas,id'],
        ]);

        $data = $validated;
        $data['creado_por'] = Auth::id();
        $data['estado'] = 'pendiente'; // Siempre pendiente al subir

        // Manejar la subida del archivo
        if ($request->hasFile('documentos')) {
            $file = $request->file('documentos');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $data['documentos'] = $fileName;

            // Completar metadatos si no llegaron del cliente
            if (empty($data['nombre_documento'])) {
                $data['nombre_documento'] = $file->getClientOriginalName();
            }
            if (empty($data['tipo_documento'])) {
                $extLower = strtolower($file->getClientOriginalExtension());
                $data['tipo_documento'] = in_array($extLower, ['doc', 'docx']) ? 'Word' : ($extLower === 'pdf' ? 'PDF' : strtoupper($extLower));
            }
        }

        // Crear el documento
        $documento = ListadoMaestro::create($data);

        // Enviar notificación a todos los superadmins
        $superadmins = User::where('role', 'superadmin')->get();
        Notification::send($superadmins, new DocumentoSubidoNotification($documento));

        return redirect()->back()->with('success', 'Documento agregado correctamente y enviado para revisión.');
    }

    // Aprobar documento (solo superadmin)
    public function aprobar($id)
    {
        $this->authorize('aprobar', ListadoMaestro::class);
        
        $doc = ListadoMaestro::findOrFail($id);
        $doc->estado = 'aprobado';
        $doc->aprobacion_fecha = now();
        $doc->aprobacion_cargo = Auth::user()->role;
        $doc->aprobacion_firma = Auth::user()->name;
        $doc->save();

        // Enviar notificación al admin que creó el documento
        $admin = User::find($doc->creado_por);
        if ($admin) {
            $admin->notify(new DocumentoProcesadoNotification($doc, 'aprobado', Auth::user()));
        }

        return redirect()->back()->with('success', 'Documento aprobado correctamente. Se ha notificado al administrador.');
    }

    // Rechazar documento (solo superadmin)
    public function rechazar($id)
    {
        $this->authorize('rechazar', ListadoMaestro::class);
        
        $doc = ListadoMaestro::findOrFail($id);
        $doc->estado = 'rechazado';
        $doc->save();

        // Enviar notificación al admin que creó el documento
        $admin = User::find($doc->creado_por);
        if ($admin) {
            $admin->notify(new DocumentoProcesadoNotification($doc, 'rechazado', Auth::user()));
        }

        return redirect()->back()->with('success', 'Documento rechazado correctamente. Se ha notificado al administrador.');
    }

    // Actualizar documento
    public function update(Request $request, $id)
    {
        $documento = ListadoMaestro::findOrFail($id);
        
        // Verificar permisos: solo el admin que creó el documento o superadmin puede editarlo
        if (Auth::user()->role === 'admin' && $documento->creado_por !== Auth::id()) {
            abort(403, 'Solo puedes editar documentos que hayas creado');
        }

        $validated = $request->validate([
            'tipo_proceso' => ['required', 'string', 'max:255'],
            'nombre_proceso' => ['required', 'string', 'max:255'],
            'subproceso_sig_subsistema' => ['nullable', 'string', 'max:255'],
            'documentos' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:20480'], // 20MB
            'numero_doc' => ['nullable', 'string', 'max:255'],
            'responsable' => ['required', 'string', 'max:255'],
            'tipo_documento' => ['nullable', 'string', 'max:50'],
            'nombre_documento' => ['nullable', 'string', 'max:255'],
            'codigo' => ['nullable', 'string', 'max:255'],
            'version' => ['nullable', 'string', 'max:50'],
            'fecha_creacion' => ['required', 'date'],
            'area_id' => ['required', 'exists:areas,id'],
        ]);

        $data = $validated;

        // Manejar la subida del archivo si se proporciona uno nuevo
        if ($request->hasFile('documentos')) {
            $file = $request->file('documentos');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $data['documentos'] = $fileName;

            // Completar metadatos si no llegaron del cliente
            if (empty($data['nombre_documento'])) {
                $data['nombre_documento'] = $file->getClientOriginalName();
            }
            if (empty($data['tipo_documento'])) {
                $extLower = strtolower($file->getClientOriginalExtension());
                $data['tipo_documento'] = in_array($extLower, ['doc', 'docx']) ? 'Word' : ($extLower === 'pdf' ? 'PDF' : strtoupper($extLower));
            }
        }

        // Actualizar el documento
        $documento->update($data);

        return redirect()->back()->with('success', 'Documento actualizado correctamente.');
    }

    // Eliminar documento
    public function destroy($id)
    {
        $documento = ListadoMaestro::findOrFail($id);
        
        // Verificar permisos: solo el admin que creó el documento o superadmin puede eliminarlo
        if (Auth::user()->role === 'admin' && $documento->creado_por !== Auth::id()) {
            abort(403, 'Solo puedes eliminar documentos que hayas creado');
        }

        // Eliminar el archivo físico si existe
        if ($documento->documentos && file_exists(public_path('uploads/' . $documento->documentos))) {
            unlink(public_path('uploads/' . $documento->documentos));
        }

        // Eliminar el documento de la base de datos
        $documento->delete();

        return redirect()->back()->with('success', 'Documento eliminado correctamente.');
    }
}