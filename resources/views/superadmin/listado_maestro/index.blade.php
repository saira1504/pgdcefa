@extends('layouts.superadmin')

@section('title', 'Listado Maestro - Superadmin')

@section('content')
<!-- Header compacto -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1 text-success">
            <i class="fas fa-clipboard-list me-2"></i>Listado Maestro
        </h1>
        <p class="text-muted small mb-0">Revisa y aprueba los documentos enviados por los administradores</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregar">
            ➕ Agregar Documento
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- 📊 ESTADÍSTICAS HORIZONTALES -->
<div class="row mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="me-3 icon-container">
                    <div class="icon-circle bg-warning-light">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                </div>
                <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-0 small fw-semibold">Pendientes</h6>
                        <small class="text-muted">Por revisar</small>
                    </div>
                    <div class="text-end">
                        <h2 class="mb-0 fw-bold text-warning">{{ $documentos->where('estado', 'pendiente')->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="me-3 icon-container">
                    <div class="icon-circle bg-success-light">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                </div>
                <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-0 small fw-semibold">Aprobados</h6>
                        <small class="text-muted">Documentos</small>
                    </div>
                    <div class="text-end">
                        <h2 class="mb-0 fw-bold text-success">{{ $documentos->where('estado', 'aprobado')->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="me-3 icon-container">
                    <div class="icon-circle bg-danger-light">
                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                    </div>
                </div>
                <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-0 small fw-semibold">Rechazados</h6>
                        <small class="text-muted">Documentos</small>
                    </div>
                    <div class="text-end">
                        <h2 class="mb-0 fw-bold text-danger">{{ $documentos->where('estado', 'rechazado')->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="card border-0 shadow-sm stat-card">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="me-3 icon-container">
                    <div class="icon-circle bg-info-light">
                        <i class="fas fa-file-alt fa-2x text-info"></i>
                    </div>
                </div>
                <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-0 small fw-semibold">Total</h6>
                        <small class="text-muted">Documentos</small>
                    </div>
                    <div class="text-end">
                        <h2 class="mb-0 fw-bold text-info">{{ $documentos->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 📋 TABLA DE DOCUMENTOS -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-light border-0">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="fas fa-list me-2"></i>Documentos del Listado Maestro
            </h6>
            <div class="d-flex align-items-center">
                <form method="GET" action="{{ route('superadmin.listado_maestro.index') }}" class="d-flex align-items-center">
                    <label class="me-2 mb-0 text-muted">Filtrar por Área:</label>
                    <select name="area" class="form-select form-select-sm me-2" style="width: 200px;" onchange="this.form.submit()">
                        <option value="">Todas las áreas</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ $areaId == $area->id ? 'selected' : '' }}>
                                {{ $area->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @if($areaId)
                        <a href="{{ route('superadmin.listado_maestro.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times me-1"></i>Limpiar filtro
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="tablaDocumentos" class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tipo Proceso</th>
                        <th>Nombre Proceso</th>
                        <th>Subproceso/SIG/Subsistema</th>
                        <th>Área</th>
                        <th>Documentos</th>
                        <th>Nº</th>
                        <th>Responsable</th>
                        <th>Tipo Documento</th>
                        <th>Nombre Documento</th>
                        <th>Código</th>
                        <th>Versión</th>
                        <th>Fecha Creación</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documentos as $doc)
                    <tr>
                        <td>{{ $doc->tipo_proceso }}</td>
                        <td>{{ $doc->nombre_proceso }}</td>
                        <td>{{ $doc->subproceso_sig_subsistema }}</td>
                        <td>{{ $doc->area->nombre ?? 'N/A' }}</td>
                        <td>
                            @if($doc->documentos)
                                <div class="documento-btn-container">
                                    <a href="{{ asset('uploads/' . $doc->documentos) }}" target="_blank" class="btn btn-sm btn-outline-primary documento-btn" title="{{ $doc->documentos }}">
                                        <i class="fas fa-file-pdf me-1"></i>
                                        <span class="documento-text">{{ Str::limit($doc->documentos, 20) }}</span>
                                    </a>
                                </div>
                            @else
                                <span class="text-muted">No disponible</span>
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
                        <td>
                            <div class="actions-container">
                                <div class="btn-group-vertical w-100" role="group">
                                    <button class="btn btn-primary btn-sm action-btn" onclick="editarDocumento({{ $doc->id }})" data-bs-toggle="modal" data-bs-target="#modalEditar">
                                        <i class="fas fa-edit me-1"></i>Editar
                                    </button>
                                    <form action="{{ route('superadmin.listado_maestro.destroy', $doc->id) }}" method="POST" class="d-inline w-100" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este documento?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm action-btn w-100" type="submit">
                                            <i class="fas fa-trash me-1"></i>Eliminar
                                        </button>
                                    </form>
                                    @if($doc->estado === 'pendiente')
                                        <form action="{{ route('superadmin.listado_maestro.aprobar', $doc->id) }}" method="POST" class="d-inline w-100">
                                            @csrf
                                            <button class="btn btn-success btn-sm action-btn w-100" onclick="return confirm('¿Estás seguro de que quieres aprobar este documento?')">
                                                <i class="fas fa-check me-1"></i>Aprobar
                                            </button>
                                        </form>
                                        <form action="{{ route('superadmin.listado_maestro.rechazar', $doc->id) }}" method="POST" class="d-inline w-100">
                                            @csrf
                                            <button class="btn btn-danger btn-sm action-btn w-100" onclick="return confirm('¿Estás seguro de que quieres rechazar este documento?')">
                                                <i class="fas fa-times me-1"></i>Rechazar
                                            </button>
                                        </form>
                                    @else
                                        <!-- Botones placeholder para mantener consistencia visual -->
                                        <div class="action-btn-placeholder"></div>
                                        <div class="action-btn-placeholder"></div>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
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
                <div class="col-md-6">
                    <label>Área</label>
                    <select name="area_id" class="form-control" required>
                        <option value="">Seleccione un área</option>
                        @php
                            $areasList = \App\Models\Area::where('activo', true)->orderBy('nombre')->get();
                        @endphp
                        @foreach($areasList as $area)
                            <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                        @endforeach
                    </select>
                </div>
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

<!-- Modal Editar Documento -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">✏️ Editar Documento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="formEditar" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6"><label>Tipo de Proceso</label><input type="text" name="tipo_proceso" class="form-control" id="edit_tipo_proceso" required></div>
                <div class="col-md-6"><label>Nombre del Proceso</label><input type="text" name="nombre_proceso" class="form-control" id="edit_nombre_proceso" required></div>
                <div class="col-md-6"><label>Subproceso/SIG/ Subsistema</label><input type="text" name="subproceso_sig_subsistema" class="form-control" id="edit_subproceso_sig_subsistema"></div>
                <div class="col-md-6"><label>Documentos</label><input type="file" name="documentos" class="form-control" id="edit_documentos" accept=".pdf,.doc,.docx"></div>
                <div class="col-md-6">
                    <label>Área</label>
                    <select name="area_id" class="form-control" id="edit_area_id" required>
                        <option value="">Seleccione un área</option>
                        @php
                            $areasList = \App\Models\Area::where('activo', true)->orderBy('nombre')->get();
                        @endphp
                        @foreach($areasList as $area)
                            <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3"><label>Nº</label><input type="text" name="numero_doc" class="form-control" id="edit_numero_doc"></div>
                <div class="col-md-3"><label>Responsable</label><input type="text" name="responsable" class="form-control" id="edit_responsable" required></div>
                <div class="col-md-3"><label>Tipo Documento</label><input type="text" name="tipo_documento" class="form-control" id="edit_tipo_documento" readonly></div>
                <div class="col-md-3"><label>Nombre Documento</label><input type="text" name="nombre_documento" class="form-control" id="edit_nombre_documento" readonly></div>
                <div class="col-md-3"><label>Código</label><input type="text" name="codigo" class="form-control" id="edit_codigo"></div>
                <div class="col-md-3"><label>Versión</label><input type="text" name="version" class="form-control" id="edit_version"></div>
                <div class="col-md-3"><label>Fecha de Creación</label><input type="date" name="fecha_creacion" class="form-control" id="edit_fecha_creacion"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .modal-backdrop.show {
  background-color: transparent !important;
} ....Quitar por completo el oscurecimiento
.stat-card {
    transition: transform 0.2s ease-in-out;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.icon-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-success-light {
    background-color: rgba(40, 167, 69, 0.1);
}

.bg-warning-light {
    background-color: rgba(255, 193, 7, 0.1);
}

.bg-danger-light {
    background-color: rgba(220, 53, 69, 0.1);
}

.bg-info-light {
    background-color: rgba(23, 162, 184, 0.1);
}

/* Estilos para tabla uniforme */
.table {
    margin-bottom: 0;
}

.table th,
.table td {
    padding: 0.75rem;
    vertical-align: middle;
    border-top: 1px solid #dee2e6;
    height: 60px; /* Altura fija para todas las filas */
}

.table tbody tr {
    height: 60px; /* Altura fija para todas las filas */
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

/* Contenedor para botones de documentos */
.documento-btn-container {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Botones de documentos uniformes */
.documento-btn {
    width: 100%;
    max-width: 200px;
    height: 35px;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: flex;
    align-items: center;
    justify-content: center;
}

.documento-text {
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Asegurar que los botones no rompan la altura */
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
}

/* Badges uniformes */
.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

/* Texto pequeño uniforme */
small {
    font-size: 0.875rem;
    line-height: 1.2;
}

/* Contenedor de acciones uniforme */
.actions-container {
    width: 100%;
    min-width: 120px;
}

/* Botones de acción uniformes */
.action-btn {
    width: 100% !important;
    margin-bottom: 2px;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.action-btn:last-child {
    margin-bottom: 0;
}

/* Placeholder para mantener consistencia visual */
.action-btn-placeholder {
    height: 32px;
    margin-bottom: 2px;
}

/* Asegurar que la columna de acciones tenga ancho fijo */
.table th:last-child,
.table td:last-child {
    width: 130px;
    min-width: 130px;
    max-width: 130px;
}

/* Responsive para botones en pantallas pequeñas */
@media (max-width: 768px) {
    .actions-container {
        min-width: 100px;
    }
    
    .table th:last-child,
    .table td:last-child {
        width: 110px;
        min-width: 110px;
        max-width: 110px;
    }
    
    .action-btn {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#tablaDocumentos').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50]
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

    // Función para editar documento
    window.editarDocumento = function(id) {
        // Actualizar la acción del formulario
        $('#formEditar').attr('action', '{{ route("superadmin.listado_maestro.update", "") }}/' + id);
        
        // Hacer llamada AJAX para obtener los datos del documento
        $.ajax({
            url: '{{ route("superadmin.listado_maestro.show", "") }}/' + id,
            method: 'GET',
            success: function(data) {
                // Llenar los campos del formulario con los datos del documento
                $('#edit_tipo_proceso').val(data.tipo_proceso);
                $('#edit_nombre_proceso').val(data.nombre_proceso);
                $('#edit_subproceso_sig_subsistema').val(data.subproceso_sig_subsistema);
                $('#edit_area_id').val(data.area_id);
                $('#edit_numero_doc').val(data.numero_doc);
                $('#edit_responsable').val(data.responsable);
                $('#edit_tipo_documento').val(data.tipo_documento);
                $('#edit_nombre_documento').val(data.nombre_documento);
                $('#edit_codigo').val(data.codigo);
                $('#edit_version').val(data.version);
                $('#edit_fecha_creacion').val(data.fecha_creacion);
                
                // Mostrar el modal
                $('#modalEditar').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Error al obtener datos del documento:', error);
                alert('Error al cargar los datos del documento. Por favor, inténtalo de nuevo.');
            }
        });
    }
});
</script>
@endpush
        
        