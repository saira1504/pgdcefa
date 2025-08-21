<?php

namespace App\Http\Controllers;

use App\Models\DocumentoAprendiz;
use App\Models\UnidadProductiva;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminDocumentoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,superadmin']);
    }

    /**
     * Mostrar todos los documentos de aprendices para revisión
     */
    public function index(Request $request)
    {
        $admin = Auth::user();
        
        // Obtener documentos según el rol del usuario
        $query = DocumentoAprendiz::with(['aprendiz', 'unidad', 'tipoDocumento']);
        
        if ($admin->role === 'admin') {
            // Admin solo ve documentos de sus unidades
            $query->whereHas('unidad', function($q) use ($admin) {
                $q->where('admin_principal_id', $admin->id)
                  ->orWhereHas('admins', function($q2) use ($admin) {
                      $q2->where('admin_id', $admin->id);
                  });
            });
        }
        // Superadmin ve todos los documentos

        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('unidad_id')) {
            $query->where('unidad_id', $request->unidad_id);
        }

        if ($request->filled('fase_id')) {
            $query->where('tipo_documento_id', $request->fase_id);
        }

        if ($request->filled('aprendiz_id')) {
            $query->where('aprendiz_id', $request->aprendiz_id);
        }

        // Ordenamiento
        $orden = $request->get('orden', 'fecha_subida');
        $direccion = $request->get('direccion', 'desc');
        
        switch ($orden) {
            case 'fecha_subida':
                $query->orderBy('fecha_subida', $direccion);
                break;
            case 'aprendiz':
                $query->orderBy('aprendiz_id', $direccion);
                break;
            case 'unidad':
                $query->orderBy('unidad_id', $direccion);
                break;
            case 'estado':
                $query->orderBy('estado', $direccion);
                break;
            default:
                $query->orderBy('fecha_subida', 'desc');
        }

        $documentos = $query->paginate(20);

        // Obtener unidades y aprendices para filtros según el rol
        if ($admin->role === 'admin') {
            $unidadesAdmin = $admin->unidadesAsignadas()->get();
            $aprendicesAdmin = User::aprendicesDeAdmin($admin->id)->get();
        } else {
            // Superadmin ve todas las unidades y aprendices
            $unidadesAdmin = UnidadProductiva::all();
            $aprendicesAdmin = User::where('role', 'aprendiz')->get();
        }

        // Estadísticas según el rol
        if ($admin->role === 'admin') {
            $estadisticas = [
                'total' => DocumentoAprendiz::whereHas('unidad', function($q) use ($admin) {
                    $q->where('admin_principal_id', $admin->id)
                      ->orWhereHas('admins', function($q2) use ($admin) {
                          $q2->where('admin_id', $admin->id);
                      });
                })->count(),
                'pendientes' => DocumentoAprendiz::whereHas('unidad', function($q) use ($admin) {
                    $q->where('admin_principal_id', $admin->id)
                      ->orWhereHas('admins', function($q2) use ($admin) {
                          $q2->where('admin_id', $admin->id);
                      });
                })->where('estado', 'pendiente')->count(),
                'en_revision' => DocumentoAprendiz::whereHas('unidad', function($q) use ($admin) {
                    $q->where('admin_principal_id', $admin->id)
                      ->orWhereHas('admins', function($q2) use ($admin) {
                          $q2->where('admin_id', $admin->id);
                      });
                })->where('estado', 'en_revision')->count(),
                'aprobados' => DocumentoAprendiz::whereHas('unidad', function($q) use ($admin) {
                    $q->where('admin_principal_id', $admin->id)
                      ->orWhereHas('admins', function($q2) use ($admin) {
                          $q2->where('admin_id', $admin->id);
                      });
                })->where('estado', 'aprobado')->count(),
                'rechazados' => DocumentoAprendiz::whereHas('unidad', function($q) use ($admin) {
                    $q->where('admin_principal_id', $admin->id)
                      ->orWhereHas('admins', function($q2) use ($admin) {
                          $q2->where('admin_id', $admin->id);
                      });
                })->where('estado', 'rechazado')->count(),
            ];
        } else {
            // Superadmin ve todas las estadísticas
        $estadisticas = [
                'total' => DocumentoAprendiz::count(),
                'pendientes' => DocumentoAprendiz::where('estado', 'pendiente')->count(),
                'en_revision' => DocumentoAprendiz::where('estado', 'en_revision')->count(),
                'aprobados' => DocumentoAprendiz::where('estado', 'aprobado')->count(),
                'rechazados' => DocumentoAprendiz::where('estado', 'rechazado')->count(),
            ];
        }

        // Determinar qué vista usar según el rol
        $view = $admin->role === 'superadmin' ? 'superadmin.documentos.index' : 'admin.documentos.index';
        
        return view($view, compact(
            'documentos',
            'unidadesAdmin',
            'aprendicesAdmin',
            'estadisticas'
        ));
    }

    /**
     * Mostrar un documento específico para revisión
     */
    public function show(DocumentoAprendiz $documento)
    {
        $admin = Auth::user();
        
        // Verificar permisos según el rol
        if ($admin->role === 'admin') {
            if (!$admin->esAdminDe($documento->unidad_id) && 
                $documento->unidad->admin_principal_id !== $admin->id) {
                abort(403, 'No tienes permisos para revisar este documento.');
            }
        }
        // Superadmin puede revisar cualquier documento

        // Cargar relaciones
        $documento->load(['aprendiz', 'unidad', 'tipoDocumento', 'revisor']);

        // Determinar qué vista usar según el rol
        $view = $admin->role === 'superadmin' ? 'superadmin.documentos.show' : 'admin.documentos.show';
        
        return view($view, compact('documento'));
    }

    /**
     * Aprobar un documento
     */
    public function aprobar(Request $request, DocumentoAprendiz $documento)
    {
        $admin = Auth::user();
        
        // Verificar permisos según el rol
        if ($admin->role === 'admin') {
            if (!$admin->esAdminDe($documento->unidad_id) && 
                $documento->unidad->admin_principal_id !== $admin->id) {
                abort(403, 'No tienes permisos para revisar este documento.');
            }
        }
        // Superadmin puede revisar cualquier documento
        
        $request->validate([
            'comentarios' => 'nullable|string|max:500'
        ]);

        DB::transaction(function () use ($documento, $admin, $request) {
            $documento->marcarComoRevisado(
                $admin->id,
                'aprobado',
                $request->comentarios
            );

            // Aquí podrías enviar una notificación al aprendiz
            // event(new DocumentoAprobado($documento));
        });

        return redirect()->back()->with('success', 'Documento aprobado correctamente.');
    }

    /**
     * Rechazar un documento
     */
    public function rechazar(Request $request, DocumentoAprendiz $documento)
    {
        $admin = Auth::user();
        
        // Verificar permisos según el rol
        if ($admin->role === 'admin') {
            if (!$admin->esAdminDe($documento->unidad_id) && 
                $documento->unidad->admin_principal_id !== $admin->id) {
                abort(403, 'No tienes permisos para revisar este documento.');
            }
        }
        // Superadmin puede revisar cualquier documento

        $request->validate([
            'comentarios_rechazo' => 'required|string|max:500'
        ]);

        DB::transaction(function () use ($documento, $admin, $request) {
            $documento->marcarComoRevisado(
                $admin->id,
                'rechazado',
                $request->comentarios_rechazo
            );

            // Aquí podrías enviar una notificación al aprendiz
            // event(new DocumentoRechazado($documento));
        });

        return redirect()->back()->with('success', 'Documento rechazado correctamente.');
    }

    /**
     * Marcar documento como en revisión
     */
    public function enRevision(DocumentoAprendiz $documento)
    {
        $admin = Auth::user();
        
        // Verificar permisos según el rol
        if ($admin->role === 'admin') {
            if (!$admin->esAdminDe($documento->unidad_id) && 
                $documento->unidad->admin_principal_id !== $admin->id) {
                abort(403, 'No tienes permisos para revisar este documento.');
            }
        }
        // Superadmin puede revisar cualquier documento

        $documento->marcarComoRevisado($admin->id, 'en_revision');

        return redirect()->back()->with('success', 'Documento marcado como en revisión.');
    }

    /**
     * Descargar un documento
     */
    public function descargar(DocumentoAprendiz $documento)
    {
        $admin = Auth::user();
        
        // Verificar permisos según el rol
        if ($admin->role === 'admin') {
            if (!$admin->esAdminDe($documento->unidad_id) && 
                $documento->unidad->admin_principal_id !== $admin->id) {
                abort(403, 'No tienes permisos para descargar este documento.');
            }
        }
        // Superadmin puede descargar cualquier documento

        if (!Storage::disk('public')->exists($documento->archivo_path)) {
            abort(404, 'El archivo no existe.');
        }

        return Storage::disk('public')->download($documento->archivo_path, $documento->archivo_original);
    }

    /**
     * Vista previa de un documento (si es PDF)
     */
    public function preview(DocumentoAprendiz $documento)
    {
        $admin = Auth::user();
        
        // Verificar permisos según el rol
        if ($admin->role === 'admin') {
            if (!$admin->esAdminDe($documento->unidad_id) && 
                $documento->unidad->admin_principal_id !== $admin->id) {
                abort(403, 'No tienes permisos para ver este documento.');
            }
        }
        // Superadmin puede ver cualquier documento

        if (!Storage::disk('public')->exists($documento->archivo_path)) {
            abort(404, 'El archivo no existe.');
        }

        // Solo permitir preview de PDFs
        if ($documento->mime_type !== 'application/pdf') {
            return redirect()->route('admin.documentos.descargar', $documento);
        }

        $url = Storage::disk('public')->url($documento->archivo_path);
        
        // Determinar qué vista usar según el rol
        $view = $admin->role === 'superadmin' ? 'superadmin.documentos.preview' : 'admin.documentos.preview';
        
        return view($view, compact('documento', 'url'));
    }

    /**
     * Obtener estadísticas para el dashboard
     */
    public function estadisticas()
    {
        $admin = Auth::user();
        
        // Estadísticas según el rol
        if ($admin->role === 'admin') {
            $estadisticas = [
                'documentos_pendientes' => DocumentoAprendiz::whereHas('unidad', function($q) use ($admin) {
                    $q->where('admin_principal_id', $admin->id)
                      ->orWhereHas('admins', function($q2) use ($admin) {
                          $q2->where('admin_id', $admin->id);
                      });
                })->where('estado', 'pendiente')->count(),
                
                'documentos_esta_semana' => DocumentoAprendiz::whereHas('unidad', function($q) use ($admin) {
                    $q->where('admin_principal_id', $admin->id)
                      ->orWhereHas('admins', function($q2) use ($admin) {
                          $q2->where('admin_id', $admin->id);
                      });
                })->whereBetween('fecha_subida', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
                
                'documentos_por_unidad' => DocumentoAprendiz::whereHas('unidad', function($q) use ($admin) {
                    $q->where('admin_principal_id', $admin->id)
                      ->orWhereHas('admins', function($q2) use ($admin) {
                          $q2->where('admin_id', $admin->id);
                      });
                })
                ->with('unidad')
                ->selectRaw('unidad_id, COUNT(*) as total')
                ->groupBy('unidad_id')
                ->get()
            ];
        } else {
            // Superadmin ve todas las estadísticas
            $estadisticas = [
                'documentos_pendientes' => DocumentoAprendiz::where('estado', 'pendiente')->count(),
                
                'documentos_esta_semana' => DocumentoAprendiz::whereBetween('fecha_subida', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
                
                'documentos_por_unidad' => DocumentoAprendiz::with('unidad')
                    ->selectRaw('unidad_id, COUNT(*) as total')
                    ->groupBy('unidad_id')
                    ->get()
            ];
        }

        return response()->json($estadisticas);
    }
}
