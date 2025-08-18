@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('superadmin.documentos.index') }}">Documentos de Aprendices</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detalle del Documento</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-file-alt text-primary"></i>
                {{ $documento->titulo }}
            </h1>
            <p class="text-muted">Revisa y gestiona este documento del aprendiz</p>
        </div>
        <div>
            <a href="{{ route('superadmin.documentos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Información del Documento -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Información del Documento
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary">{{ $documento->titulo }}</h5>
                            <p class="text-muted">{{ $documento->descripcion ?: 'Sin descripción' }}</p>
                            
                            <div class="mb-3">
                                <strong>Tipo de Documento:</strong>
                                <span class="badge bg-info">{{ $documento->tipoDocumento->nombre }}</span>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Estado Actual:</strong>
                                <span class="badge {{ $documento->estado_color }}">{{ $documento->estado_texto }}</span>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Archivo:</strong>
                                <div class="d-flex align-items-center mt-2">
                                    <i class="{{ $documento->icono }} fa-2x me-3 text-primary"></i>
                                    <div>
                                        <div>{{ $documento->archivo_original }}</div>
                                        <small class="text-muted">{{ $documento->tamaño_humano }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Fecha de Subida:</strong>
                                <div class="text-muted">{{ $documento->fecha_subida->format('d/m/Y H:i') }}</div>
                            </div>
                            
                            @if($documento->fecha_revision)
                                <div class="mb-3">
                                    <strong>Última Revisión:</strong>
                                    <div class="text-muted">{{ $documento->fecha_revision->format('d/m/Y H:i') }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Aprendiz -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user"></i> Información del Aprendiz
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Nombre:</strong>
                                <div class="text-muted">{{ $documento->aprendiz->name }}</div>
                            </div>
                            <div class="mb-3">
                                <strong>Email:</strong>
                                <div class="text-muted">{{ $documento->aprendiz->email }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>ID:</strong>
                                <div class="text-muted">{{ $documento->aprendiz->id }}</div>
                            </div>
                            <div class="mb-3">
                                <strong>Fecha de Registro:</strong>
                                <div class="text-muted">{{ $documento->aprendiz->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de la Unidad -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-industry"></i> Información de la Unidad Productiva
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Nombre:</strong>
                                <div class="text-muted">{{ $documento->unidad->nombre }}</div>
                            </div>
                            <div class="mb-3">
                                <strong>Ubicación:</strong>
                                <div class="text-muted">{{ $documento->unidad->ubicacion }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Descripción:</strong>
                                <div class="text-muted">{{ $documento->unidad->descripcion ?: 'Sin descripción' }}</div>
                            </div>
                            <div class="mb-3">
                                <strong>Estado:</strong>
                                <span class="badge bg-{{ $documento->unidad->estado == 'activa' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($documento->unidad->estado) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Acciones -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tools"></i> Acciones de Revisión
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Acciones según el estado -->
                    @if($documento->estado == 'pendiente')
                        <div class="mb-3">
                            <button type="button" class="btn btn-warning btn-block mb-2" 
                                    onclick="marcarEnRevision()">
                                <i class="fas fa-search"></i> Marcar en Revisión
                            </button>
                        </div>
                    @endif

                    @if(in_array($documento->estado, ['pendiente', 'en_revision']))
                        <div class="mb-3">
                            <button type="button" class="btn btn-success btn-block mb-2" 
                                    data-bs-toggle="modal" data-bs-target="#aprobarModal">
                                <i class="fas fa-check"></i> Aprobar Documento
                            </button>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-danger btn-block mb-2" 
                                    data-bs-toggle="modal" data-bs-target="#rechazarModal">
                                <i class="fas fa-times"></i> Rechazar Documento
                            </button>
                        </div>
                    @endif

                    <!-- Acciones de archivo -->
                    <div class="mb-3">
                        <a href="{{ route('superadmin.documentos.descargar', $documento) }}" 
                           class="btn btn-outline-primary btn-block mb-2">
                            <i class="fas fa-download"></i> Descargar Archivo
                        </a>
                    </div>

                    @if($documento->mime_type == 'application/pdf')
                        <div class="mb-3">
                            <a href="{{ route('superadmin.documentos.preview', $documento) }}" 
                               class="btn btn-outline-info btn-block mb-2" target="_blank">
                                <i class="fas fa-eye"></i> Vista Previa PDF
                            </a>
                        </div>
                    @endif

                    <!-- Información del revisor -->
                    @if($documento->revisor)
                        <hr>
                        <div class="text-center">
                            <small class="text-muted">
                                <i class="fas fa-user-check"></i>
                                Revisado por: {{ $documento->revisor->name }}
                            </small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Historial del Documento -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history"></i> Historial
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary">
                                <i class="fas fa-upload"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Documento Subido</h6>
                                <p class="timeline-text">{{ $documento->fecha_subida->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        @if($documento->estado == 'en_revision')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info">
                                    <i class="fas fa-search"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Marcado en Revisión</h6>
                                    <p class="timeline-text">{{ $documento->fecha_revision->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($documento->estado == 'aprobado')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Documento Aprobado</h6>
                                    <p class="timeline-text">{{ $documento->fecha_revision->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($documento->estado == 'rechazado')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-danger">
                                    <i class="fas fa-times"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Documento Rechazado</h6>
                                    <p class="timeline-text">{{ $documento->fecha_revision->format('d/m/Y H:i') }}</p>
                                    @if($documento->comentarios_rechazo)
                                        <small class="text-muted">{{ $documento->comentarios_rechazo }}</small>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Aprobar -->
<div class="modal fade" id="aprobarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-check text-success"></i> Aprobar Documento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.documentos.aprobar', $documento) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>¿Estás seguro de que quieres aprobar este documento?</p>
                    <div class="mb-3">
                        <label for="comentarios" class="form-label">Comentarios (opcional):</label>
                        <textarea name="comentarios" id="comentarios" class="form-control" rows="3" 
                                  placeholder="Comentarios adicionales sobre la aprobación..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Aprobar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Rechazar -->
<div class="modal fade" id="rechazarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-times text-danger"></i> Rechazar Documento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.documentos.rechazar', $documento) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>¿Estás seguro de que quieres rechazar este documento?</p>
                    <div class="mb-3">
                        <label for="comentarios_rechazo" class="form-label">Motivo del rechazo: <span class="text-danger">*</span></label>
                        <textarea name="comentarios_rechazo" id="comentarios_rechazo" class="form-control" rows="3" 
                                  placeholder="Explica el motivo del rechazo..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Rechazar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Formulario oculto para marcar en revisión -->
<form id="formEnRevision" method="POST" style="display: none;">
    @csrf
</form>

@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 10px;
}

.timeline-content {
    margin-left: 10px;
}

.timeline-title {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
}

.timeline-text {
    margin: 5px 0 0 0;
    font-size: 12px;
    color: #6c757d;
}
</style>
@endpush

@push('scripts')
<script>
function marcarEnRevision() {
    if (confirm('¿Estás seguro de que quieres marcar este documento como "En Revisión"?')) {
        const form = document.getElementById('formEnRevision');
        form.action = "{{ route('superadmin.documentos.en-revision', $documento) }}";
        form.submit();
    }
}
</script>
@endpush
