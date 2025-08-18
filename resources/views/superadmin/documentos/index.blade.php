@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Documentos de Aprendices</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-file-alt text-primary"></i>
                Documentos de Aprendices
            </h1>
            <p class="text-muted">Revisa y gestiona los documentos subidos por los aprendices</p>
        </div>
        <div>
            <a href="{{ route('superadmin.documentos.estadisticas') }}" class="btn btn-info">
                <i class="fas fa-chart-bar"></i> Ver Estadísticas
            </a>
        </div>
    </div>

    <!-- Tarjetas de Estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Documentos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $documentos->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pendientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['pendientes'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                En Revisión</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['en_revision'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-search fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Aprobados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['aprobados'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter"></i> Filtros
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('superadmin.documentos.index') }}" id="filtrosForm">
                <div class="row">
                    <div class="col-md-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select name="estado" id="estado" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="en_revision" {{ request('estado') == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                            <option value="aprobado" {{ request('estado') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                            <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="unidad_id" class="form-label">Unidad Productiva</label>
                        <select name="unidad_id" id="unidad_id" class="form-select">
                            <option value="">Todas las unidades</option>
                            @foreach($unidadesAdmin as $unidad)
                                <option value="{{ $unidad->id }}" {{ request('unidad_id') == $unidad->id ? 'selected' : '' }}>
                                    {{ $unidad->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="aprendiz_id" class="form-label">Aprendiz</label>
                        <select name="aprendiz_id" id="aprendiz_id" class="form-select">
                            <option value="">Todos los aprendices</option>
                            @foreach($aprendicesAdmin as $aprendiz)
                                <option value="{{ $aprendiz->id }}" {{ request('aprendiz_id') == $aprendiz->id ? 'selected' : '' }}>
                                    {{ $aprendiz->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="orden" class="form-label">Ordenar por</label>
                        <select name="orden" id="orden" class="form-select">
                            <option value="fecha_desc" {{ request('orden') == 'fecha_desc' ? 'selected' : '' }}>Más recientes</option>
                            <option value="fecha_asc" {{ request('orden') == 'fecha_asc' ? 'selected' : '' }}>Más antiguos</option>
                            <option value="aprendiz" {{ request('orden') == 'aprendiz' ? 'selected' : '' }}>Por aprendiz</option>
                            <option value="unidad" {{ request('orden') == 'unidad' ? 'selected' : '' }}>Por unidad</option>
                            <option value="estado" {{ request('orden') == 'estado' ? 'selected' : '' }}>Por estado</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                        <a href="{{ route('superadmin.documentos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
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

    <!-- Tabla de Documentos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Lista de Documentos
            </h6>
            <span class="badge bg-primary">{{ $documentos->total() }} documentos</span>
        </div>
        <div class="card-body">
            @if($documentos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Documento</th>
                                <th>Aprendiz</th>
                                <th>Unidad</th>
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
                                            <i class="{{ $documento->icono }} fa-2x me-3 text-primary"></i>
                                            <div>
                                                <strong>{{ $documento->titulo }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $documento->tipoDocumento->nombre }}</small>
                                                <br>
                                                <small class="text-muted">{{ $documento->tamaño_humano }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user fa-lg me-2 text-info"></i>
                                            <div>
                                                <strong>{{ $documento->aprendiz->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $documento->aprendiz->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-industry fa-lg me-2 text-warning"></i>
                                            <div>
                                                <strong>{{ $documento->unidad->nombre }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $documento->unidad->ubicacion }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $documento->estado_color }}">
                                            {{ $documento->estado_texto }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <div class="fw-bold">{{ $documento->fecha_subida->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $documento->fecha_subida->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('superadmin.documentos.show', $documento) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($documento->mime_type == 'application/pdf')
                                                <a href="{{ route('superadmin.documentos.preview', $documento) }}" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   title="Vista previa PDF"
                                                   target="_blank">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            @endif
                                            
                                            <a href="{{ route('superadmin.documentos.descargar', $documento) }}" 
                                               class="btn btn-sm btn-outline-success" 
                                               title="Descargar">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            
                                            @if($documento->estado == 'pendiente')
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-warning" 
                                                        title="Marcar en revisión"
                                                        onclick="marcarEnRevision({{ $documento->id }})">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center">
                    {{ $documentos->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No se encontraron documentos</h5>
                    <p class="text-muted">Intenta ajustar los filtros o no hay documentos que cumplan con los criterios.</p>
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
document.addEventListener('DOMContentLoaded', function() {
    // Aplicar filtros automáticamente al cambiar
    const filtros = ['estado', 'unidad_id', 'aprendiz_id', 'orden'];
    filtros.forEach(filtro => {
        const elemento = document.getElementById(filtro);
        if (elemento) {
            elemento.addEventListener('change', function() {
                document.getElementById('filtrosForm').submit();
            });
        }
    });
});

function marcarEnRevision(documentoId) {
    if (confirm('¿Estás seguro de que quieres marcar este documento como "En Revisión"?')) {
        const form = document.getElementById('formEnRevision');
        form.action = `/superadmin/documentos/${documentoId}/en-revision`;
        form.submit();
    }
}
</script>
@endpush
