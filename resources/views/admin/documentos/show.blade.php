@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.documentos.index') }}">Documentos</a></li>
                    <li class="breadcrumb-item active">Revisar Documento</li>
                </ol>
            </nav>

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

            <!-- Header del Documento -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i>{{ $documento->titulo }}
                        </h4>
                        <span class="badge bg-light text-dark fs-6">
                            {{ $documento->estado_texto }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>Descripción</h5>
                            <p class="text-muted">{{ $documento->descripcion ?: 'Sin descripción' }}</p>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><i class="fas fa-user me-2"></i>Información del Aprendiz</h6>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                            {{ strtoupper(substr($documento->aprendiz->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $documento->aprendiz->name }}</strong>
                                            <br><small class="text-muted">{{ $documento->aprendiz->email }}</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6><i class="fas fa-industry me-2"></i>Información de la Unidad</h6>
                                    <p class="mb-1"><strong>Unidad:</strong> {{ $documento->unidad->nombre }}</p>
                                    <p class="mb-1"><strong>Fase:</strong> Fase {{ $documento->tipoDocumento->numero ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Proyecto:</strong> {{ $documento->unidad->proyecto ?: 'No especificado' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Detalles del Archivo</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <i class="{{ $documento->icono }} fa-2x mb-2"></i>
                                        <p class="mb-1"><strong>Archivo:</strong> {{ $documento->archivo_original }}</p>
                                        <p class="mb-1"><strong>Tamaño:</strong> {{ $documento->tamaño_humano }}</p>
                                        <p class="mb-1"><strong>Tipo:</strong> {{ $documento->mime_type }}</p>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.documentos.descargar', $documento) }}" 
                                           class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-download me-1"></i>Descargar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de Revisión -->
            @if($documento->estado != 'pendiente')
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-{{ $documento->estado == 'aprobado' ? 'success' : ($documento->estado == 'rechazado' ? 'danger' : 'info') }} text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-clipboard-check me-2"></i>Información de Revisión
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Estado:</strong> 
                                <span class="badge bg-{{ $documento->estado_color }}">{{ $documento->estado_texto }}</span>
                            </p>
                            <p><strong>Revisado por:</strong> {{ $documento->revisor->name ?? 'No especificado' }}</p>
                            <p><strong>Fecha de revisión:</strong> {{ $documento->fecha_revision ? $documento->fecha_revision->format('d/m/Y H:i') : 'No especificada' }}</p>
                        </div>
                        
                        @if($documento->estado == 'rechazado' && $documento->comentarios_rechazo)
                        <div class="col-md-6">
                            <h6>Comentarios de Rechazo:</h6>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ $documento->comentarios_rechazo }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Acciones de Revisión -->
            @if($documento->estado == 'pendiente' || $documento->estado == 'en_revision')
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="fas fa-tasks me-2"></i>Acciones de Revisión
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-success"><i class="fas fa-check-circle me-2"></i>Aprobar Documento</h6>
                            <form method="POST" action="{{ route('admin.documentos.aprobar', $documento) }}" class="mb-3">
                                @csrf
                                <div class="mb-3">
                                    <label for="comentarios" class="form-label">Comentarios de Aprobación (Opcional)</label>
                                    <textarea class="form-control" id="comentarios" name="comentarios" rows="3" 
                                              placeholder="Agrega comentarios positivos o sugerencias de mejora..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i>Aprobar Documento
                                </button>
                            </form>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-danger"><i class="fas fa-times-circle me-2"></i>Rechazar Documento</h6>
                            <form method="POST" action="{{ route('admin.documentos.rechazar', $documento) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="comentarios_rechazo" class="form-label">Comentarios de Rechazo <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="comentarios_rechazo" name="comentarios_rechazo" rows="3" 
                                              placeholder="Explica por qué se rechaza el documento y qué debe corregir el aprendiz..." required></textarea>
                                    <div class="form-text">Es obligatorio explicar el motivo del rechazo para que el aprendiz pueda corregir el documento.</div>
                                </div>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-times me-1"></i>Rechazar Documento
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    @if($documento->estado == 'pendiente')
                    <hr>
                    <div class="text-center">
                        <form method="POST" action="{{ route('admin.documentos.en-revision', $documento) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-clock me-1"></i>Marcar como "En Revisión"
                            </button>
                        </form>
                        <small class="text-muted d-block mt-2">Usa esta opción si necesitas más tiempo para revisar el documento</small>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Historial de Cambios -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>Historial del Documento
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary">
                                <i class="fas fa-upload text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Documento Subido</h6>
                                <p class="text-muted mb-1">{{ $documento->aprendiz->name }} subió el documento</p>
                                <small class="text-muted">{{ $documento->fecha_subida->format('d/m/Y H:i') }} ({{ $documento->fecha_subida->diffForHumans() }})</small>
                            </div>
                        </div>
                        
                        @if($documento->estado != 'pendiente')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $documento->estado == 'aprobado' ? 'success' : ($documento->estado == 'rechazado' ? 'danger' : 'info') }}">
                                <i class="fas fa-{{ $documento->estado == 'aprobado' ? 'check' : ($documento->estado == 'rechazado' ? 'times' : 'clock') }} text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Documento {{ $documento->estado_texto }}</h6>
                                <p class="text-muted mb-1">
                                    @if($documento->revisor)
                                        Revisado por {{ $documento->revisor->name }}
                                    @else
                                        Estado cambiado a {{ $documento->estado_texto }}
                                    @endif
                                </p>
                                @if($documento->fecha_revision)
                                <small class="text-muted">{{ $documento->fecha_revision->format('d/m/Y H:i') }} ({{ $documento->fecha_revision->diffForHumans() }})</small>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="mt-4 text-center">
                <a href="{{ route('admin.documentos.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left me-1"></i>Volver a la Lista
                </a>
                
                @if($documento->estado == 'pendiente')
                <button type="button" class="btn btn-outline-warning me-2" onclick="marcarEnRevision()">
                    <i class="fas fa-clock me-1"></i>Marcar en Revisión
                </button>
                @endif
                
                <a href="{{ route('admin.documentos.descargar', $documento) }}" class="btn btn-primary">
                    <i class="fas fa-download me-1"></i>Descargar Documento
                </a>
            </div>
        </div>
</div>

<!-- Formulario oculto para marcar en revisión -->
<form id="formEnRevision" method="POST" action="{{ route('admin.documentos.en-revision', $documento) }}" style="display: none;">
    @csrf
</form>

@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}

.avatar-lg {
    width: 60px;
    height: 60px;
    font-size: 24px;
    font-weight: bold;
}

.card-header h6 {
    margin-bottom: 0;
}

.form-text {
    font-size: 0.875em;
    color: #6c757d;
}
</style>
@endpush

@push('scripts')
<script>
function marcarEnRevision() {
    if (confirm('¿Deseas marcar este documento como "En Revisión"?')) {
        document.getElementById('formEnRevision').submit();
    }
}

// Validación del formulario de rechazo
document.addEventListener('DOMContentLoaded', function() {
    const formRechazo = document.querySelector('form[action*="rechazar"]');
    if (formRechazo) {
        formRechazo.addEventListener('submit', function(e) {
            const comentarios = document.getElementById('comentarios_rechazo').value.trim();
            if (!comentarios) {
                e.preventDefault();
                alert('Debes agregar comentarios de rechazo para continuar.');
                document.getElementById('comentarios_rechazo').focus();
            }
        });
    }
});
</script>
@endpush
