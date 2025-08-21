@extends('layouts.superadmin')

@section('content')
<div class="container-fluid p-4">
    <!-- Header con gradiente -->
    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="card-header text-white py-4" style="background: linear-gradient(135deg, #28a745, #20c997);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 fw-bold mb-1"><i class="fas fa-file-alt me-2"></i>Revisión de Documentos</h2>
                    <p class="mb-0 opacity-75">Revisa y aprueba los documentos subidos por los aprendices</p>
                </div>
                <span class="badge bg-warning text-dark fs-6">{{ $estadisticas['pendientes'] }} Pendientes</span>
            </div>
        </div>
    </div>

    <!-- Estadísticas compactas -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-2">
            <div class="card border-0 shadow-sm stat-card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Total</div>
                        <div class="h4 fw-bold text-primary mb-0">{{ $estadisticas['total'] }}</div>
                    </div>
                    <i class="fas fa-database text-primary"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border-0 shadow-sm stat-card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Pendientes</div>
                        <div class="h4 fw-bold text-warning mb-0">{{ $estadisticas['pendientes'] }}</div>
                    </div>
                    <i class="fas fa-hourglass-half text-warning"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border-0 shadow-sm stat-card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">En revisión</div>
                        <div class="h4 fw-bold text-info mb-0">{{ $estadisticas['en_revision'] }}</div>
                    </div>
                    <i class="fas fa-search text-info"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border-0 shadow-sm stat-card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Aprobados</div>
                        <div class="h4 fw-bold text-success mb-0">{{ $estadisticas['aprobados'] }}</div>
                    </div>
                    <i class="fas fa-check-circle text-success"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border-0 shadow-sm stat-card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Rechazados</div>
                        <div class="h4 fw-bold text-danger mb-0">{{ $estadisticas['rechazados'] }}</div>
                    </div>
                    <i class="fas fa-times-circle text-danger"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card border-0 shadow-sm stat-card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Mostrados</div>
                        <div class="h4 fw-bold text-secondary mb-0">{{ $documentos->count() }}</div>
                    </div>
                    <i class="fas fa-list text-secondary"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="fas fa-filter me-2"></i>Filtros de Búsqueda
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('superadmin.documentos.index') }}" id="filtrosForm">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Estado</label>
                        <select class="form-select" name="estado" onchange="this.form.submit()">
                            <option value="">Todos los estados</option>
                            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="en_revision" {{ request('estado') == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                            <option value="aprobado" {{ request('estado') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                            <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Unidad Productiva</label>
                        <select class="form-select" name="unidad_id" onchange="this.form.submit()">
                            <option value="">Todas las unidades</option>
                            @foreach($unidadesAdmin as $unidad)
                                <option value="{{ $unidad->id }}" {{ request('unidad_id') == $unidad->id ? 'selected' : '' }}>
                                    {{ $unidad->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Aprendiz</label>
                        <select class="form-select" name="aprendiz_id" onchange="this.form.submit()">
                            <option value="">Todos los aprendices</option>
                            @foreach($aprendicesAdmin as $aprendiz)
                                <option value="{{ $aprendiz->id }}" {{ request('aprendiz_id') == $aprendiz->id ? 'selected' : '' }}>
                                    {{ $aprendiz->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Ordenar por</label>
                        <select class="form-select" name="orden" onchange="this.form.submit()">
                            <option value="fecha_subida" {{ request('orden') == 'fecha_subida' ? 'selected' : '' }}>Fecha de subida</option>
                            <option value="titulo" {{ request('orden') == 'titulo' ? 'selected' : '' }}>Título</option>
                            <option value="aprendiz" {{ request('orden') == 'aprendiz' ? 'selected' : '' }}>Aprendiz</option>
                            <option value="unidad" {{ request('orden') == 'unidad' ? 'selected' : '' }}>Unidad</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Dirección</label>
                        <select class="form-select" name="direccion" onchange="this.form.submit()">
                            <option value="desc" {{ request('direccion') == 'desc' ? 'selected' : '' }}>Descendente</option>
                            <option value="asc" {{ request('direccion') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                        </select>
                    </div>
                    <div class="col-md-9 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-secondary me-2" onclick="limpiarFiltros()">
                            <i class="fas fa-eraser me-1"></i>Limpiar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Filtrar
                        </button>
                    </div>
                </div>
            </form>
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

    <!-- Tabla de Documentos (misma estructura que administrador) -->
    @if($documentos->count() > 0)
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-list me-2"></i>Documentos para Revisión</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Documento</th>
                            <th>Aprendiz</th>
                            <th>Unidad</th>
                            <th>Fase</th>
                            <th>Estado</th>
                            <th>Fecha Subida</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documentos as $documento)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="{{ $documento->icono }} me-2"></i>
                                    <div>
                                        <strong>{{ $documento->titulo }}</strong>
                                        @if($documento->descripcion)
                                            <br><small class="text-muted">{{ Str::limit($documento->descripcion, 50) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        {{ strtoupper(substr($documento->aprendiz->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $documento->aprendiz->name }}</strong>
                                        <br><small class="text-muted">{{ $documento->aprendiz->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-info">{{ $documento->unidad->nombre }}</span></td>
                            <td><span class="badge bg-secondary">Fase {{ $documento->tipoDocumento->numero ?? 'N/A' }}</span></td>
                            <td><span class="badge bg-{{ $documento->estado_color }}">{{ $documento->estado_texto }}</span></td>
                            <td>
                                <small class="text-muted">
                                    {{ $documento->fecha_subida->format('d/m/Y H:i') }}
                                    <br>
                                    <span class="text-muted">{{ $documento->fecha_subida->diffForHumans() }}</span>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('superadmin.documentos.show', $documento) }}" class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($documento->mime_type == 'application/pdf')
                                    
                                    @endif
                                    <a href="{{ route('superadmin.documentos.descargar', $documento) }}" class="btn btn-sm btn-outline-success" title="Descargar">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    @if($documento->estado == 'pendiente')
                                    <button type="button" class="btn btn-sm btn-outline-warning" title="Marcar en revisión" onclick="marcarEnRevision({{ $documento->id }})">
                                        <i class="fas fa-clock"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    Mostrando {{ $documentos->firstItem() }} a {{ $documentos->lastItem() }} de {{ $documentos->total() }} documentos
                </div>
                <div>
                    {{ $documentos->links() }}
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No hay documentos para revisar</h5>
            <p class="text-muted">No se encontraron documentos que coincidan con los filtros aplicados.</p>
            <button type="button" class="btn btn-outline-primary" onclick="limpiarFiltros()">
                <i class="fas fa-eraser me-1"></i>Limpiar filtros
            </button>
        </div>
    </div>
    @endif
</div>

<!-- Formulario oculto para marcar en revisión -->
<form id="formEnRevision" method="POST" style="display: none;">
    @csrf
</form>

<script>
function limpiarFiltros() {
    window.location.href = "{{ route('superadmin.documentos.index') }}";
}

function marcarEnRevision(documentoId) {
    if (confirm('¿Estás seguro de que quieres marcar este documento como "En Revisión"?')) {
        const form = document.getElementById('formEnRevision');
        form.action = "{{ route('superadmin.documentos.en-revision', ':id') }}".replace(':id', documentoId);
        form.submit();
    }
}
</script>
@endsection

<style>
@keyframes slideUp { from { opacity: 0; transform: translateY(30px);} to { opacity: 1; transform: translateY(0);} }
.animate-slide-up { animation: slideUp 0.6s ease-out forwards; }
.fixed-grid-container { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 1rem; position: relative; }
.fixed-card-slot { min-height: 320px; transition: opacity 0.3s ease, visibility 0.3s ease; }
@media (max-width: 1200px) { .fixed-grid-container { grid-template-columns: repeat(3, 1fr);} }
@media (max-width: 768px) { .fixed-grid-container { grid-template-columns: repeat(2, 1fr);} }
@media (max-width: 576px) { .fixed-grid-container { grid-template-columns: 1fr;} }
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
    font-weight: bold;
}

.btn-group .btn { margin-right: 2px; }
.btn-group .btn:last-child { margin-right: 0; }
.badge { font-size: 0.75em; padding: 0.375em 0.75em; border-radius: 8px; }
</style>

