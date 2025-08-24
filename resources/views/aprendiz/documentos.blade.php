@extends('layouts.aprendiz')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 px-0">
            @include('partials.sidebar_aprendiz')
        </div>
        
        <div class="col-md-9 col-lg-10 p-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('aprendiz.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Gestión de Documentos</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Gestión de Documentos</h2>
                    <p class="text-muted mb-0">Sube y gestiona tus documentos para {{ $unidadAsignada->nombre }}</p>
                </div>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#subirDocumentoModal">
                    <i class="fas fa-plus me-2"></i>Subir Documento
                </button>
            </div>

            <!-- Alertas -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Documentos pendientes -->
            @if(count($documentosPendientes) > 0)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Documentos Pendientes ({{ count($documentosPendientes) }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($documentosPendientes as $doc)
                        <div class="col-md-6 mb-3">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $doc->nombre }}</h6>
                                    <p class="card-text small text-muted">{{ $doc->descripcion ?? 'Sin descripción' }}</p>
                                    @if($doc->fecha_limite)
                                        <p class="text-danger small mb-2">
                                            <i class="fas fa-calendar me-1"></i>
                                            Vence: {{ \Carbon\Carbon::parse($doc->fecha_limite)->format('d/m/Y') }}
                                        </p>
                                    @endif
                                    <button class="btn btn-warning btn-sm" onclick="seleccionarTipoDocumento({{ $doc->id }}, '{{ $doc->nombre }}')">
                                        <i class="fas fa-upload me-1"></i>Subir ahora
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Mis documentos -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-folder me-2"></i>Mis Documentos Subidos
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($misDocumentos) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Documento</th>
                                        <th>Archivo</th>
                                        <th>Estado</th>
                                        <th>Fecha Subida</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($misDocumentos as $doc)
                                    <tr>
                                                                            <td>
                                        <strong>{{ $doc->tipoDocumento->nombre ?? 'N/A' }}</strong>
                                        @if($doc->descripcion)
                                            <br><small class="text-muted">{{ $doc->descripcion }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="fas fa-file-pdf text-danger me-1"></i>
                                        {{ $doc->nombre_archivo }}
                                    </td>
                                        <td>
                                            @if($doc->estado == 'aprobado')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Aprobado
                                                </span>
                                            @elseif($doc->estado == 'rechazado')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-1"></i>Rechazado
                                                </span>
                                                @if($doc->comentarios_rechazo)
                                                    <br><small class="text-danger">{{ $doc->comentarios_rechazo }}</small>
                                                @endif
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i>En revisión
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $doc->fecha_subida->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ Storage::url($doc->ruta_archivo) }}" target="_blank" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ Storage::url($doc->ruta_archivo) }}" download class="btn btn-outline-success">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                @if($doc->estado == 'en_revision' || $doc->estado == 'pendiente')
                                                    <button class="btn btn-outline-warning" onclick="editarDocumento({{ $doc->id }}, '{{ $doc->tipoDocumento->nombre ?? 'N/A' }}', '{{ $doc->descripcion ?? '' }}')" data-bs-toggle="modal" data-bs-target="#editarDocumentoModal">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @endif
                                                @if($doc->estado == 'rechazado')
                                                    <button class="btn btn-outline-warning" onclick="resubirDocumento({{ $doc->id }})">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No has subido documentos aún</h5>
                            <p class="text-muted">Comienza subiendo tu primer documento</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#subirDocumentoModal">
                                <i class="fas fa-upload me-2"></i>Subir mi primer documento
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para subir documento -->
<div class="modal fade" id="subirDocumentoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-upload me-2"></i>Subir Documento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('aprendiz.documentos.subir') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipo de Documento *</label>
                        <select name="tipo_documento_id" class="form-select" required id="tipoDocumentoSelect">
                            <option value="">Selecciona el tipo de documento</option>
                            @foreach($documentosPendientes as $doc)
                                <option value="{{ $doc->id }}">{{ $doc->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Archivo *</label>
                        <input type="file" name="documento" class="form-control" accept=".pdf,.doc,.docx" required>
                        <div class="form-text">
                            Formatos permitidos: PDF, DOC, DOCX. Tamaño máximo: 10MB
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción (Opcional)</label>
                        <textarea name="descripcion" class="form-control" rows="3" 
                                  placeholder="Agrega una descripción o comentarios sobre el documento"></textarea>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Importante:</strong> Una vez subido, el documento será revisado por tu gestor. 
                        Recibirás una notificación cuando sea aprobado o rechazado.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload me-2"></i>Subir Documento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar documento -->
<div class="modal fade" id="editarDocumentoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Editar Documento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditarDocumento" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="documento_id" id="editDocumentoId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipo de Documento</label>
                        <input type="text" class="form-control" id="editTipoDocumento" readonly>
                        <small class="text-muted">No se puede cambiar el tipo de documento</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Archivo Actual</label>
                        <input type="text" class="form-control" id="editArchivoActual" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nuevo Archivo (Opcional)</label>
                        <input type="file" name="documento" class="form-control" accept=".pdf,.doc,.docx">
                        <div class="form-text">
                            Deja vacío para mantener el archivo actual. Formatos permitidos: PDF, DOC, DOCX. Tamaño máximo: 10MB
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" id="editDescripcion"
                                  placeholder="Agrega una descripción o comentarios sobre el documento"></textarea>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Nota:</strong> Solo puedes editar documentos que estén en revisión o pendientes. 
                        Una vez aprobados o rechazados, no se pueden modificar.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function seleccionarTipoDocumento(id, nombre) {
    document.getElementById('tipoDocumentoSelect').value = id;
    const modal = new bootstrap.Modal(document.getElementById('subirDocumentoModal'));
    modal.show();
}

function resubirDocumento(docId) {
    // Lógica para resubir documento rechazado
    alert('Funcionalidad de resubir documento en desarrollo');
}

function editarDocumento(docId, tipoDocumento, descripcion) {
    // Actualizar la acción del formulario
    document.getElementById('formEditarDocumento').action = '{{ route("aprendiz.documentos.update", "") }}/' + docId;
    
    // Llenar los campos del modal
    document.getElementById('editDocumentoId').value = docId;
    document.getElementById('editTipoDocumento').value = tipoDocumento;
    document.getElementById('editDescripcion').value = descripcion;
    
    // Obtener el nombre del archivo actual desde la tabla
    const row = event.target.closest('tr');
    const archivoCell = row.querySelector('td:nth-child(2)');
    const nombreArchivo = archivoCell.textContent.trim();
    document.getElementById('editArchivoActual').value = nombreArchivo;
}
</script>
@endsection