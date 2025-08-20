<?php

namespace App\Http\Controllers;

use App\Models\ListadoMaestro;
use App\Models\User;
use App\Notifications\DocumentoSubidoNotification;
use App\Notifications\DocumentoProcesadoNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

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
        
        $documentos = ListadoMaestro::all();
        return view('admin.listado_maestro.index', compact('documentos'));
    }

    // Método específico para superadmin
    public function superadminIndex()
    {
        $this->authorize('viewAny', ListadoMaestro::class);
        
        // Superadmin ve todos los documentos para poder revisar el historial
        $documentos = ListadoMaestro::all();
        return view('superadmin.listado_maestro.index', compact('documentos'));
    }

    // Guardar nuevo documento (solo admin)
    public function store(Request $request)
    {
        // Verificar que solo los admins puedan subir documentos
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Solo los administradores pueden subir documentos');
        }

        $data = $request->all();
        $data['creado_por'] = Auth::id();
        $data['estado'] = 'pendiente'; // Siempre pendiente al subir

        // Manejar la subida del archivo
        if ($request->hasFile('documentos')) {
            $file = $request->file('documentos');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $data['documentos'] = $fileName;
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
}