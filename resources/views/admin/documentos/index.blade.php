@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 px-0">
            @include('partials.sidebar_admin')
        </div>
        
        <div class="col-md-9 col-lg-10 p-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Revisión de Documentos</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fas fa-file-alt me-2"></i>Revisión de Documentos</h2>
                    <p class="text-muted mb-0">Revisa y aprueba los documentos subidos por los aprendices</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-warning fs-6">{{ $estadisticas['pendientes'] }} Pendientes</span>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-md-2 col-6 mb-3">
                    <div class="card border-primary text-center">
                        <div class="card-body py-3">
                            <h4 class="text-primary mb-1">{{ $estadisticas['total'] }}</h4>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <div class="card border-warning text-center">
                        <div class="card-body py-3">
                            <h4 class="text-warning mb-1">{{ $estadisticas['pendientes'] }}</h4>
                            <small class="text-muted">Pendientes</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <div class="card border-info text-center">
                        <div class="card-body py-3">
                            <h4 class="text-info mb-1">{{ $estadisticas['en_revision'] }}</h4>
                            <small class="text-muted">En Revisión</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <div class="card border-success text-center">
                        <div class="card-body py-3">
                            <h4 class="text-success mb-1">{{ $estadisticas['aprobados'] }}</h4>
                            <small class="text-muted">Aprobados</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <div class="card border-danger text-center">
                        <div class="card-body py-3">
                            <h4 class="text-danger mb-1">{{ $estadisticas['rechazados'] }}</h4>
                            <small class="text-muted">Rechazados</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <div class="card border-secondary text-center">
                        <div class="card-body py-3">
                            <h4 class="text-secondary mb-1">{{ $documentos->count() }}</h4>
                            <small class="text-muted">Mostrados</small>
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
                    <form method="GET" action="{{ route('admin.documentos.index') }}" id="filtrosForm">
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
                                    @foreach($unidadesAdmin as $nombre => $id)
                                        <option value="{{ $id }}" {{ request('unidad_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Aprendiz</label>
                                <select class="form-select" name="aprendiz_id" onchange="this.form.submit()">
                                    <option value="">Todos los aprendices</option>
                                    @foreach($aprendicesAdmin as $id => $nombre)
                                        <option value="{{ $id }}" {{ request('aprendiz_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Ordenar por</label>
                                <select class="form-select" name="orden" onchange="this.form.submit()">
                                    <option value="fecha_subida" {{ request('orden') == 'fecha_subida' ? 'selected' : '' }}>Fecha de subida</option>
                                    <option value="aprendiz" {{ request('orden') == 'aprendiz' ? 'selected' : '' }}>Aprendiz</option>
                                    <option value="unidad" {{ request('orden') == 'unidad' ? 'selected' : '' }}>Unidad</option>
                                    <option value="estado" {{ request('orden') == 'estado' ? 'selected' : '' }}>Estado</option>
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

            <!-- Lista de Documentos -->
            @if($documentos->count() > 0)
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-list me-2"></i>Documentos para Revisión
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
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
                                    <td>
                                        <span class="badge bg-info">{{ $documento->unidad->nombre }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">Fase {{ $documento->tipoDocumento->numero ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $documento->estado_color }}">
                                            {{ $documento->estado_texto }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $documento->fecha_subida->format('d/m/Y H:i') }}
                                            <br>
                                            <span class="text-muted">{{ $documento->fecha_subida->diffForHumans() }}</span>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.documentos.show', $documento) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($documento->mime_type == 'application/pdf')
                                            <a href="{{ route('admin.documentos.preview', $documento) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="Vista previa"
                                               target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endif
                                            
                                            <a href="{{ route('admin.documentos.descargar', $documento) }}" 
                                               class="btn btn-sm btn-outline-success" 
                                               title="Descargar">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            
                                            @if($documento->estado == 'pendiente')
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-warning" 
                                                    title="Marcar en revisión"
                                                    onclick="marcarEnRevision({{ $documento->id }})">
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
    </div>
</div>

<!-- Formulario oculto para marcar en revisión -->
<form id="formEnRevision" method="POST" style="display: none;">
    @csrf
</form>

@endsection

@push('scripts')
<script>
function limpiarFiltros() {
    window.location.href = "{{ route('admin.documentos.index') }}";
}

function marcarEnRevision(documentoId) {
    if (confirm('¿Deseas marcar este documento como "En Revisión"?')) {
        const form = document.getElementById('formEnRevision');
        form.action = "{{ route('admin.documentos.index') }}/" + documentoId + "/en-revision";
        form.submit();
    }
}

// Auto-submit del formulario cuando cambien los filtros
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('select[name="estado"], select[name="unidad_id"], select[name="aprendiz_id"]');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
    font-weight: bold;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.75em;
}
</style>
@endpush
