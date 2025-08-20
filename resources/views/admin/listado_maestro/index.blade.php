@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">📋 Listado Maestro - Admin</h2>
    <p class="text-muted mb-4">Como administrador, puedes subir documentos que serán revisados por el super administrador.</p>

    <!-- Botón modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregar">
        ➕ Agregar Documento
    </button>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Notificaciones discretas -->
    @php
        $notificaciones = Auth::user()->notifications()->where('type', 'App\Notifications\DocumentoProcesadoNotification')->take(3)->get();
    @endphp
    
    @if($notificaciones->count() > 0)
        <div id="notificaciones-container" class="mb-3">
            @foreach($notificaciones as $index => $notif)
                @php
                    $data = $notif->data;
                    $color = $data['accion'] === 'aprobado' ? 'success' : 'danger';
                    $icono = $data['accion'] === 'aprobado' ? '✅' : '❌';
                @endphp
                <div class="notification-toast alert alert-{{ $color }} alert-dismissible fade show" 
                     style="font-size: 0.85rem; padding: 0.5rem 0.75rem; margin-bottom: 0.5rem; opacity: 0.9;"
                     data-delay="{{ 3000 + ($index * 1000) }}">
                    <div class="d-flex align-items-center">
                        <span class="me-2" style="font-size: 0.9rem;">{{ $icono }}</span>
                        <div class="flex-grow-1">
                            <small class="fw-bold">{{ $data['titulo'] }}</small>
                            <small class="text-muted ms-2">{{ \Carbon\Carbon::parse($data['fecha'])->diffForHumans() }}</small>
                        </div>
                        <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.7rem;"></button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Tabla -->
    <table id="tablaDocumentos" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Tipo Proceso</th>
                <th>Nombre Proceso</th>
                <th>Subproceso/SIG/Subsistema</th>
                <th>Documentos</th>
                <th>Nº</th>
                <th>Responsable</th>
                <th>Tipo Documento</th>
                <th>Nombre Documento</th>
                <th>Código</th>
                <th>Versión</th>
                <th>Fecha Creación</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documentos as $doc)
            <tr>
                <td>{{ $doc->tipo_proceso }}</td>
                <td>{{ $doc->nombre_proceso }}</td>
                <td>{{ $doc->subproceso_sig_subsistema }}</td>
                <td>
                    @if($doc->documentos)
                        <a href="{{ asset('uploads/' . $doc->documentos) }}" target="_blank">{{ $doc->documentos }}</a>
                    @else
                        No disponible
                    @endif
                </td>
                <td>{{ $doc->numero_doc }}</td>
                <td>{{ $doc->responsable }}</td>
                <td>{{ $doc->tipo_documento }}</td>
                <td>{{ $doc->nombre_documento }}</td>
                <td>{{ $doc->codigo }}</td>
                <td>{{ $doc->version }}</td>
                <td>{{ \Carbon\Carbon::parse($doc->fecha_creacion)->format('d-m-Y') }}</td>
                <td>
                    <span class="badge bg-{{ $doc->estado === 'pendiente' ? 'warning' : ($doc->estado === 'aprobado' ? 'success' : 'danger') }}">
                        @if($doc->estado === 'pendiente')
                            ⏳ Pendiente
                        @elseif($doc->estado === 'aprobado')
                            ✅ Aprobado
                        @else
                            ❌ Rechazado
                        @endif
                    </span>
                    @if($doc->estado === 'aprobado' && $doc->aprobacion_fecha)
                        <br><small class="text-muted">Aprobado el {{ \Carbon\Carbon::parse($doc->aprobacion_fecha)->format('d/m/Y') }}</small>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Agregar Documento -->
<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">📄 Agregar Nuevo Documento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('admin.listado_maestro.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                <strong>Nota:</strong> Al subir un documento, este será enviado automáticamente para revisión por el super administrador.
            </div>
            <div class="row">
                <div class="col-md-6"><label>Tipo de Proceso</label><input type="text" name="tipo_proceso" class="form-control" required></div>
                <div class="col-md-6"><label>Nombre del Proceso</label><input type="text" name="nombre_proceso" class="form-control" required></div>
                <div class="col-md-6"><label>Subproceso/SIG/ Subsistema</label><input type="text" name="subproceso_sig_subsistema" class="form-control"></div>
                <div class="col-md-6"><label>Documentos</label><input type="file" name="documentos" class="form-control" id="documentos" accept=".pdf,.doc,.docx" required></div>
                <div class="col-md-3"><label>Nº</label><input type="text" name="numero_doc" class="form-control"></div>
                <div class="col-md-3"><label>Responsable</label><input type="text" name="responsable" class="form-control" required></div>
                <div class="col-md-3"><label>Tipo Documento</label><input type="text" name="tipo_documento" class="form-control" id="tipoDocumento" readonly></div>
                <div class="col-md-3"><label>Nombre Documento</label><input type="text" name="nombre_documento" class="form-control" id="nombreDocumento" readonly></div>
                <div class="col-md-3"><label>Código</label><input type="text" name="codigo" class="form-control"></div>
                <div class="col-md-3"><label>Versión</label><input type="text" name="version" class="form-control"></div>
                <div class="col-md-3"><label>Fecha de Creación</label><input type="date" name="fecha_creacion" class="form-control" value="{{ date('Y-m-d') }}"></div>
                <div class="col-md-3">
                    <label>Estado</label>
                    <input type="text" class="form-control" value="Pendiente" readonly>
                    <small class="text-muted">Se establece automáticamente</small>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">📤 Enviar para Revisión</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts y estilos -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    $('#tablaDocumentos').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50]
    });

    // Auto-ocultar notificaciones después de unos segundos
    $('.notification-toast').each(function() {
        const $notification = $(this);
        const delay = parseInt($notification.data('delay')) || 3000;
        
        setTimeout(function() {
            $notification.fadeOut(500, function() {
                $(this).remove();
            });
        }, delay);
    });

    // Detectar cambio en el input de archivo
    $('#documentos').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Extraer el nombre del archivo
            $('#nombreDocumento').val(file.name);

            // Detectar el tipo de archivo (basado en la extensión)
            const extension = file.name.split('.').pop().toLowerCase();
            let tipoDocumento = '';
            switch (extension) {
                case 'pdf':
                    tipoDocumento = 'PDF';
                    break;
                case 'doc':
                case 'docx':
                    tipoDocumento = 'Word';
                    break;
                default:
                    tipoDocumento = 'Desconocido';
            }
            $('#tipoDocumento').val(tipoDocumento);
        }
    });
});
</script>
@endsection