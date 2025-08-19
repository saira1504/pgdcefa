@extends('layouts.app')

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
                    <li class="breadcrumb-item active">Documentos Requeridos</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Documentos Requeridos</h2>
                    <p class="text-muted mb-0">
                        @if($unidadAsignada && $unidadAsignada->id)
                            Sube los documentos requeridos por el superadministrador para {{ $unidadAsignada->nombre }} u otras unidades disponibles
                        @else
                            Sube los documentos requeridos por el superadministrador para cualquier unidad productiva
                        @endif
                    </p>
                </div>
                <div class="text-end">
                    <span class="badge bg-warning fs-6">{{ $documentosRequeridos->count() }} Requeridos</span>
                    <span class="badge bg-info fs-6 ms-2">{{ $todasLasUnidades->count() }} Unidades Disponibles</span>
                    <button class="btn btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#modalSubirDocumento">
                        <i class="fas fa-upload me-1"></i> Subir Documento
                    </button>
                </div>
            </div>

            <!-- Modal para subir documento -->
            <div class="modal fade" id="modalSubirDocumento" tabindex="-1" aria-labelledby="modalSubirDocumentoLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('aprendiz.documentos-requeridos.subir') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalSubirDocumentoLabel">Subir Documento Requerido</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="fase_id" class="form-label">Fase</label>
                                    <select class="form-select" id="fase_id" name="fase_id" required>
                                        @foreach($fases as $fase)
                                            <option value="{{ $fase->id }}">Fase {{ $fase->numero }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="unidad_id" class="form-label">Unidad Productiva</label>
                                    <select class="form-select" id="unidad_id" name="unidad_id" required>
                                        <option value="">Selecciona una unidad productiva</option>
                                        @foreach($todasLasUnidades as $unidad)
                                            <option value="{{ $unidad->id }}" 
                                                {{ $unidadAsignada && $unidadAsignada->id == $unidad->id ? 'selected' : '' }}>
                                                {{ $unidad->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">
                                        @if($unidadAsignada && $unidadAsignada->id)
                                            Tu unidad asignada está preseleccionada, pero puedes cambiar a otra si lo deseas
                                        @else
                                            Selecciona la unidad productiva para la cual es el documento
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción del Documento</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="2" maxlength="255" placeholder="Describe brevemente el documento" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="documento" class="form-label">Archivo</label>
                                    <input type="file" class="form-control" id="documento" name="documento" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Subir</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Información de la Unidad y Fase Actual -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-industry me-2"></i>Mi Unidad Productiva
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($unidadAsignada && $unidadAsignada->id)
                                <h5 class="text-success">{{ $unidadAsignada->nombre }}</h5>
                                <p class="text-muted mb-2">{{ $unidadAsignada->descripcion }}</p>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Gestor</small>
                                        <strong>{{ $unidadAsignada->gestor_nombre ?? 'Por asignar' }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Estado</small>
                                        <span class="badge bg-success">{{ $unidadAsignada->estado ?? 'Activa' }}</span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <small class="text-info">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Esta es tu unidad asignada, pero puedes subir documentos para otras unidades también
                                    </small>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-building fa-2x text-primary mb-2"></i>
                                    <h6 class="text-primary">Unidades Disponibles</h6>
                                    <p class="text-muted mb-2">Puedes seleccionar cualquier unidad productiva para subir documentos</p>
                                    <small class="text-muted">Los documentos serán revisados por el superadministrador de la unidad seleccionada</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-layer-group me-2"></i>Fase Actual
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($faseActual)
                                <h5 class="text-primary">Fase {{ $faseActual->numero }}: {{ $faseActual->nombre }}</h5>
                                <p class="text-muted mb-2">{{ $faseActual->descripcion }}</p>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Inicio</small>
                                        <strong>{{ $faseActual->fecha_inicio ? $faseActual->fecha_inicio->format('d/m/Y') : 'No definida' }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Fin</small>
                                        <strong>{{ $faseActual->fecha_fin ? $faseActual->fecha_fin->format('d/m/Y') : 'No definida' }}</strong>
                                    </div>
                                </div>
                                @if($faseActual->fecha_inicio && $faseActual->fecha_fin)
                                    @php
                                        $now = now();
                                        $start = $faseActual->fecha_inicio;
                                        $end = $faseActual->fecha_fin;
                                        $total = $end->diffInDays($start);
                                        $elapsed = $now->diffInDays($start);
                                        $progress = min(100, max(0, ($elapsed / $total) * 100));
                                        
                                        if ($now < $start) {
                                            $status = 'Pendiente';
                                            $statusColor = 'warning';
                                        } elseif ($now > $end) {
                                            $status = 'Completada';
                                            $statusColor = 'success';
                                        } else {
                                            $status = 'En Progreso';
                                            $statusColor = 'info';
                                        }
                                    @endphp
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <small class="text-muted">Progreso de la Fase</small>
                                            <span class="badge bg-{{ $statusColor }}">{{ $status }}</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-{{ $statusColor }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $progress }}%" 
                                                 aria-valuenow="{{ $progress }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ number_format($progress, 1) }}% completado</small>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                                    <p class="text-muted mb-0">No hay una fase activa actualmente</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
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

            <!-- Filtros -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-filter me-2"></i>Filtros
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Filtrar por Fase</label>
                            <select class="form-select" id="filtroFase">
                                <option value="">Todas las fases</option>
                                @foreach($fases as $fase)
                                    <option value="{{ $fase->id }}">Fase {{ $fase->numero }} - {{ $fase->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Filtrar por Unidad Productiva</label>
                            <select class="form-select" id="filtroUnidad">
                                <option value="">Todas las unidades</option>
                                <option value="{{ $unidadAsignada->id }}">{{ $unidadAsignada->nombre }}</option>
                                <option value="otras">Otras unidades</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Ordenar por</label>
                            <select class="form-select" id="ordenarPor">
                                <option value="fecha_limite">Fecha límite</option>
                                <option value="fase">Fase</option>
                                <option value="nombre">Nombre del documento</option>
                                <option value="estado">Estado</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Apartado de Documentos Enviados -->
            @if(!empty($documentosEnviados))
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Documentos Enviados en esta sesión</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Fase</th>
                                    <th>Unidad Productiva</th>
                                    <th>Descripción</th>
                                    <th>Archivo</th>
                                    <th>Fecha de Envío</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documentosEnviados as $doc)
                                    <tr>
                                        <td>
                                            @php
                                                $fase = $fases->firstWhere('id', $doc['fase_id']);
                                            @endphp
                                            {{ $fase ? 'Fase ' . $fase->numero : 'Fase ' . $doc['fase_id'] }}
                                        </td>
                                        <td>{{ $unidadAsignada->nombre }}</td>
                                        <td>{{ $doc['descripcion'] }}</td>
                                        <td>{{ $doc['archivo_original'] }}</td>
                                        <td>{{ $doc['fecha_subida'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Documentos Requeridos por Fase -->
            @foreach($fases as $fase)
                @php
                    $documentosFase = $documentosRequeridos->where('tipo_documento_id', $fase->id);
                    $esFaseActual = $faseActual && $faseActual->id == $fase->id;
                @endphp
                
                @if($documentosFase->count() > 0)
                <div class="card shadow-sm mb-4 fase-card {{ $esFaseActual ? 'border-primary border-3' : '' }}" data-fase="{{ $fase->id }}">
                    <div class="card-header {{ $esFaseActual ? 'bg-primary' : 'bg-secondary' }} text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-layer-group me-2"></i>Fase {{ $fase->numero }}: {{ $fase->nombre }}
                                @if($esFaseActual)
                                    <span class="badge bg-warning text-dark ms-2">
                                        <i class="fas fa-star me-1"></i>Fase Actual
                                    </span>
                                @endif
                            </h5>
                            <span class="badge bg-light {{ $esFaseActual ? 'text-primary' : 'text-secondary' }}">{{ $documentosFase->count() }} documentos</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($documentosFase as $doc)
                            <div class="col-md-6 col-lg-4 mb-3 documento-item" 
                                 data-estado="{{ $doc->estado ?? 'pendiente' }}" 
                                 data-fase="{{ $fase->id }}">
                                <div class="card h-100 border-{{ $doc->estado == 'aprobado' ? 'success' : ($doc->estado == 'rechazado' ? 'danger' : ($doc->estado == 'subido' ? 'info' : 'warning')) }}">
                                    <div class="card-header bg-{{ $doc->estado == 'aprobado' ? 'success' : ($doc->estado == 'rechazado' ? 'danger' : ($doc->estado == 'subido' ? 'info' : 'warning')) }} text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ $doc->titulo ?? $doc->nombre }}</h6>
                                            @if($doc->estado)
                                                <span class="badge bg-light text-dark">
                                                    @if($doc->estado == 'aprobado')
                                                        <i class="fas fa-check me-1"></i>Aprobado
                                                    @elseif($doc->estado == 'rechazado')
                                                        <i class="fas fa-times me-1"></i>Rechazado
                                                    @elseif($doc->estado == 'subido' || $doc->estado == 'en_revision')
                                                        <i class="fas fa-clock me-1"></i>En Revisión
                                                    @else
                                                        <i class="fas fa-exclamation me-1"></i>Pendiente
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text small">{{ $doc->descripcion ?? 'Sin descripción' }}</p>
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-layer-group me-1"></i>
                                                <strong>Fase:</strong> {{ $fase->numero }} - {{ $fase->nombre }}
                                                @if($esFaseActual)
                                                    <span class="badge bg-warning text-dark ms-1">Actual</span>
                                                @endif
                                            </small>
                                        </div>
                                        @if(isset($doc->fecha_limite))
                                            <p class="text-{{ now() > $doc->fecha_limite ? 'danger' : 'muted' }} small mb-2">
                                                <i class="fas fa-calendar me-1"></i>
                                                <strong>Fecha límite:</strong> {{ $doc->fecha_limite->format('d/m/Y') }}
                                                @if(now() > $doc->fecha_limite)
                                                    <span class="badge bg-danger ms-1">Vencido</span>
                                                @endif
                                            </p>
                                        @endif
                                        @if($doc->archivo_original)
                                            <p class="text-muted small mb-2">
                                                <i class="fas fa-file me-1"></i>
                                                <strong>Archivo:</strong> {{ $doc->archivo_original }}
                                            </p>
                                            <a href="{{ Storage::url($doc->archivo_path) }}" class="btn btn-outline-success btn-sm mb-2" target="_blank">
                                                <i class="fas fa-download me-1"></i>Descargar
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            @endforeach

            <!-- Sin documentos requeridos -->
            @if($documentosRequeridos->count() == 0)
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay documentos requeridos</h5>
                    <p class="text-muted">El superadministrador aún no ha asignado documentos requeridos para tu unidad.</p>
                </div>
            </div>
            @else
            <!-- Resumen de Documentos -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Resumen de Documentos Requeridos
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-primary mb-1">{{ $documentosRequeridos->where('estado', 'pendiente')->count() }}</h4>
                                <small class="text-muted">Pendientes</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-info mb-1">{{ $documentosRequeridos->where('estado', 'subido')->count() }}</h4>
                                <small class="text-muted">En Revisión</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-success mb-1">{{ $documentosRequeridos->where('estado', 'aprobado')->count() }}</h4>
                                <small class="text-muted">Aprobados</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-danger mb-1">{{ $documentosRequeridos->where('estado', 'rechazado')->count() }}</h4>
                                <small class="text-muted">Rechazados</small>
                            </div>
                        </div>
                    </div>
                    
                    @if($faseActual)
                    <div class="alert alert-primary mt-3">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Consejo:</strong> Enfócate en completar los documentos de la <strong>Fase {{ $faseActual->numero }}: {{ $faseActual->nombre }}</strong> 
                        para avanzar en tu proyecto productivo.
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para subir documento requerido -->
<div class="modal fade" id="modalSubidaDocumento" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-upload me-2"></i>Subir Documento Requerido
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('aprendiz.documentos-requeridos.subir') }}" method="POST" enctype="multipart/form-data" id="formSubidaDocumento">
                @csrf
                <input type="hidden" name="documento_requerido_id" id="documentoRequeridoId">
                <input type="hidden" name="fase_id" id="faseId">
                
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Documento:</strong> <span id="nombreDocumento"></span><br>
                        <strong>Fase:</strong> <span id="nombreFase"></span><br>
                        <strong>Unidad Productiva:</strong> {{ $unidadAsignada->nombre }}
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Archivo *</label>
                        <input type="file" name="archivo" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                        <div class="form-text">
                            Formatos permitidos: PDF, DOC, DOCX, XLS, XLSX. Tamaño máximo: 10MB
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Comentarios (Opcional)</label>
                        <textarea name="comentarios" class="form-control" rows="3" 
                                  placeholder="Agrega comentarios o descripción sobre el documento"></textarea>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Importante:</strong> Este documento será revisado por el superadministrador. 
                        Asegúrate de que cumpla con todos los requisitos especificados.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Subir Documento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver documento -->
<div class="modal fade" id="modalVerDocumento" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>Ver Documento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoDocumento">
                <!-- El contenido se cargará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a href="#" class="btn btn-success" id="btnDescargarDocumento">
                    <i class="fas fa-download me-2"></i>Descargar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Filtros
document.getElementById('filtroFase').addEventListener('change', function() {
    const faseSeleccionada = this.value;
    const faseCards = document.querySelectorAll('.fase-card');
    
    faseCards.forEach(card => {
        if (!faseSeleccionada || card.dataset.fase === faseSeleccionada) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

document.getElementById('filtroUnidad').addEventListener('change', function() {
    const unidadSeleccionada = this.value;
    const faseCards = document.querySelectorAll('.fase-card');
    
    faseCards.forEach(card => {
        if (!unidadSeleccionada || unidadSeleccionada === '{{ $unidadAsignada->id }}') {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Función para abrir modal de subida
function abrirModalSubida(documentoId, nombreDocumento, faseId, nombreFase) {
    document.getElementById('documentoRequeridoId').value = documentoId;
    document.getElementById('faseId').value = faseId;
    document.getElementById('nombreDocumento').textContent = nombreDocumento;
    document.getElementById('nombreFase').textContent = nombreFase;
    
    const modal = new bootstrap.Modal(document.getElementById('modalSubidaDocumento'));
    modal.show();
}

// Función para ver documento
function verDocumento(documentoId) {
    // Aquí puedes hacer una petición AJAX para cargar los detalles del documento
    // Por ahora, solo abrimos el modal
    const modal = new bootstrap.Modal(document.getElementById('modalVerDocumento'));
    modal.show();
    
    // Ejemplo de carga de contenido (ajustar según tu backend)
    document.getElementById('contenidoDocumento').innerHTML = `
        <div class="text-center">
            <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
            <h5>Vista previa del documento</h5>
            <p class="text-muted">La vista previa se cargará aquí</p>
        </div>
    `;
}

// Validación del formulario
document.getElementById('formSubidaDocumento').addEventListener('submit', function(e) {
    const archivo = document.querySelector('input[name="archivo"]');
    const maxSize = 10 * 1024 * 1024; // 10MB
    
    if (archivo.files[0] && archivo.files[0].size > maxSize) {
        e.preventDefault();
        alert('El archivo es demasiado grande. El tamaño máximo es 10MB.');
        return false;
    }
});
</script>
@endsection 