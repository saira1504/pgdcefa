@extends('layouts.superadmin')

@section('title', 'Unidades Productivas - Superadmin')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h1 class="display-5 fw-bold mb-2">Unidades Productivas</h1>
        <p class="text-muted fs-5">Gestiona todas las unidades productivas del sistema</p>
    </div>
    <div class="d-flex gap-3">
        <button class="btn btn-outline-success btn-lg btn-custom" data-bs-toggle="modal" data-bs-target="#createUnidadModal">
            <i class="fas fa-plus me-2"></i>Nueva Unidad
        </button>
        <button class="btn btn-success btn-lg btn-custom" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
            <i class="fas fa-upload me-2"></i>Subir Documento
        </button>
    </div>
</div>

<!-- Filtros y búsqueda -->
<div class="row mb-5">
    <div class="col-md-8">
        <div class="position-relative">
            <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" class="form-control form-control-lg search-box ps-5" 
                   placeholder="Buscar unidades productivas..." id="searchInput">
        </div>
    </div>
    <div class="col-md-4">
        <select class="form-select form-select-lg search-box" id="filterEstado">
            <option value="">Todos los estados</option>
            <option value="proceso">En proceso</option>
            <option value="iniciando">Iniciando</option>
            <option value="completado">Completado</option>
        </select>
    </div>
</div>

<!-- Estadísticas rápidas -->
<div class="row mb-5">
    <div class="col-md-3 mb-4">
        <div class="stats-card">
            <div class="stats-number text-success">{{ $totalUnidades ?? 12 }}</div>
            <div class="text-muted fw-semibold">Total Unidades</div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stats-card">
            <div class="stats-number text-primary">{{ $totalAprendices ?? 245 }}</div>
            <div class="text-muted fw-semibold">Total Aprendices</div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stats-card">
            <div class="stats-number text-info">{{ $totalDocumentos ?? 1234 }}</div>
            <div class="text-muted fw-semibold">Total Documentos</div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stats-card">
            <div class="stats-number text-warning">{{ $progresoPromedio ?? 78 }}%</div>
            <div class="text-muted fw-semibold">Progreso Promedio</div>
        </div>
    </div>
</div>

<!-- Tarjetas de Unidades Productivas -->
<div class="row mb-5">
    @forelse($unidadesProductivas as $unidad)
    <div class="col-lg-6 col-xl-4 mb-4">
        <div class="card unit-card card-hover h-100">
            <div class="card-header">
                <h4 class="card-title mb-2 fw-bold">{{ $unidad->nombre }}</h4>
                <p class="mb-0 opacity-90">{{ $unidad->proyecto }}</p>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-value">{{ $unidad->adminPrincipal->name ?? 'N/A' }}</div>
                        <div class="info-label">Gestor</div>
                    </div>
                    <div class="info-item">
                        <div class="info-value">{{ $unidad->aprendices_count }}</div>
                        <div class="info-label">Aprendices</div>
                    </div>
                    <div class="info-item">
                        <div class="info-value">{{ $unidad->documentos_count }}</div>
                        <div class="info-label">Documentos</div>
                    </div>
                    <div class="info-item">
                        <span class="badge bg-success fs-6 px-3 py-2">{{ $unidad->estado }}</span>
                        <div class="info-label mt-2">Estado</div>
                    </div>
                </div>
                
                <!-- Progreso -->
                <div class="progress-section">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-semibold">Progreso del Proyecto:</span>
                        <span class="fw-bold text-success fs-5">{{ $unidad->progreso }}%</span>
                    </div>
                    <div class="progress" style="height: 12px;">
                        <div class="progress-bar progress-bar-custom" 
                             style="width: {{ $unidad->progreso }}%"></div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-grid gap-2 d-md-flex">
                    <a href="{{ route('superadmin.unidades-productivas.show', $unidad->id) }}" class="btn btn-outline-success btn-custom flex-fill">
                        <i class="fas fa-eye me-2"></i>Ver detalles
                    </a>
                    <a href="{{ route('superadmin.unidades-productivas.edit', $unidad->id) }}" class="btn btn-success btn-custom flex-fill">
                        <i class="fas fa-cog me-2"></i>Gestionar
                    </a>
                    <form action="{{ route('superadmin.unidades-productivas.destroy', $unidad->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta unidad productiva? Esta acción es irreversible.');" class="flex-fill">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-custom w-100">
                            <i class="fas fa-trash-alt me-2"></i>Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <p class="text-muted text-center fs-5 py-5">No hay unidades productivas registradas. ¡Crea una nueva para empezar!</p>
    </div>
    @endforelse
</div>

<!-- Próximas Entregas -->
<div class="card table-custom">
    <div class="card-header bg-success text-white py-4">
        <h4 class="mb-0 fw-bold">
            <i class="fas fa-calendar me-3"></i>Próximas Entregas
        </h4>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="fs-6">Entrega</th>
                        <th class="fs-6">Unidad</th>
                        <th class="fs-6">Fecha Límite</th>
                        <th class="fs-6">Estado</th>
                        <th class="fs-6">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold">Informe Avance</td>
                        <td>Unidad 1 - Avícola</td>
                        <td>25/04/2025</td>
                        <td><span class="badge bg-warning fs-6 px-3 py-2"><i class="fas fa-clock me-2"></i>Pendiente</span></td>
                        <td><button class="btn btn-success btn-custom">Revisar</button></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Cronograma</td>
                        <td>Unidad 2 - Ganadería</td>
                        <td>27/04/2025</td>
                        <td><span class="badge bg-warning fs-6 px-3 py-2"><i class="fas fa-clock me-2"></i>Pendiente</span></td>
                        <td><button class="btn btn-success btn-custom">Revisar</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modals -->
@include('superadmin.unidades-productivas.modals.upload-document')
@include('superadmin.unidades-productivas.modals.create-unidad')
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Búsqueda en tiempo real
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.card-hover').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    
    // Filtro por estado
    $('#filterEstado').on('change', function() {
        var estado = $(this).val().toLowerCase();
        if (estado === '') {
            $('.card-hover').show();
        } else {
            $('.card-hover').each(function() {
                var cardEstado = $(this).find('.badge').text().toLowerCase();
                $(this).toggle(cardEstado.indexOf(estado) > -1);
            });
        }
    });
});
</script>
@endpush
