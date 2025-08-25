@extends('layouts.aprendiz')

@section('content')
<div class="container-fluid p-4">
    <div class="row">
        <div class="col-md-3 col-lg-2 px-0">
            @include('partials.sidebar_aprendiz')
        </div>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1 text-success">
                        <i class="fas fa-building me-2"></i>Unidades Productivas Disponibles
                    </h1>
                    <p class="text-muted small mb-0">Explora todas las unidades productivas del sistema</p>
                </div>
                @if($unidadAsignada && $unidadAsignada->id)
                    <div class="d-flex gap-2">
                        <a href="{{ route('aprendiz.mi-unidad') }}" class="btn btn-success text-white">
                            <i class="fas fa-eye me-1"></i>Mi Unidad Asignada
                        </a>
                    </div>
                @endif
            </div>

            <!-- Alertas -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm stat-card">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="me-3 icon-container">
                                <div class="icon-circle bg-success-light">
                                    <i class="fas fa-industry fa-2x text-success"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-0 small fw-semibold">Total Unidades</h6>
                                    <small class="text-muted">Disponibles</small>
                                </div>
                                <div class="text-end">
                                    <h2 class="mb-0 fw-bold text-success">{{ $totalUnidades ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm stat-card">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="me-3 icon-container">
                                <div class="icon-circle bg-primary-light">
                                    <i class="fas fa-users fa-2x text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-0 small fw-semibold">Unidades Activas</h6>
                                    <small class="text-muted">Con aprendices</small>
                                </div>
                                <div class="text-end">
                                    <h2 class="mb-0 fw-bold text-primary">{{ $unidadesConAprendices ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm stat-card">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="me-3 icon-container">
                                <div class="icon-circle bg-info-light">
                                    <i class="fas fa-chart-line fa-2x text-info"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-0 small fw-semibold">Total Fases</h6>
                                    <small class="text-muted">Definidas en el sistema</small>
                                </div>
                                <div class="text-end">
                                    <h2 class="mb-0 fw-bold text-info">
                                        {{ isset($totalFases) ? $totalFases : (\App\Models\Phase::count()) }}
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="row mb-3">
                <div class="col-md-5">
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" class="form-control form-control-sm ps-5 py-2" 
                               placeholder="Buscar unidades productivas..." id="searchInput">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select form-select-sm py-2" id="filterEstado">
                        <option value="">☀ Todos los estados</option>
                        <option value="en_proceso">🚀 En proceso</option>
                        <option value="iniciando">🌱 Iniciando</option>
                        <option value="completado">✅ Completado</option>
                        <option value="pausado">⏸ Pausado</option>
                        <option value="planificacion">📋 Planificación</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-sm py-2" id="filterTipo">
                        <option value="">📋 Todos los sectores</option>
                        @if(isset($areas))
                            @foreach($areas as $area)
                                <option value="{{ $area->nombre }}">{{ $area->nombre }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select form-select-sm py-2" id="filterGestor">
                        <option value="">👤 Todos los Encargados</option>
                        @foreach($unidadesDisponibles as $unidad)
                            @if($unidad->adminPrincipal)
                                <option value="{{ $unidad->adminPrincipal->name }}">{{ $unidad->adminPrincipal->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Grid de Unidades -->
            <div class="fixed-grid-container">
                @forelse($unidadesDisponibles as $unidad)
                <div class="fixed-card-slot unidad-card" 
                     data-estado="{{ strtolower($unidad->estado ?? '') }}" 
                     data-tipo="{{ strtolower($unidad->tipo ?? '') }}"
                     data-gestor="{{ $unidad->adminPrincipal->name ?? '' }}">
                    <div class="card shadow-sm h-100">
                        <!-- Header -->
                        <div class="card-header text-white py-3" 
                             style="background: linear-gradient(135deg, #28a745, #20c997);">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-leaf me-2"></i>{{ $unidad->nombre }}
                                </h6>
                                <span class="badge bg-light text-dark">
                                    {{ strtoupper($unidad->tipo ?? 'GENERAL') }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Body -->
                        <div class="card-body p-4">
                            <!-- Descripción -->
                            <p class="text-muted mb-3">
                                {{ $unidad->proyecto ? Str::limit($unidad->proyecto, 85) : 'Sin descripción disponible' }}
                            </p>
                            
                            <!-- Información en grid -->
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <div class="bg-light rounded p-3 text-center">
                                        <div class="fw-bold text-success">{{ Str::limit($unidad->adminPrincipal->name ?? 'Sin asignar', 15) }}</div>
                                        <small class="text-muted">Encargado</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded p-3 text-center">
                                        <div class="fw-bold text-warning">{{ Str::limit($unidad->instructor_encargado ?? 'Sin asignar', 15) }}</div>
                                        <small class="text-muted">Instructor</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <div class="bg-light rounded p-3 text-center">
                                        <div class="fw-bold text-primary">{{ $unidad->aprendices_count ?? 0 }}</div>
                                        <small class="text-muted">Aprendices</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded p-3 text-center">
                                        <div class="fw-bold text-info">{{ $unidad->documentos_aprendiz_count ?? 0 }}</div>
                                        <small class="text-muted">Documentos</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12">
                                    <div class="bg-light rounded p-3 text-center">
                                        @php
                                            $estadoConfig = match($unidad->estado ?? 'iniciando') {
                                                'iniciando' => ['class' => 'bg-warning text-dark', 'text' => 'INICIANDO'],
                                                'en_proceso' => ['class' => 'bg-primary text-white', 'text' => 'EN PROCESO'],
                                                'pausado' => ['class' => 'bg-secondary text-white', 'text' => 'PAUSADO'],
                                                'completado' => ['class' => 'bg-success text-white', 'text' => 'COMPLETADO'],
                                                'planificacion' => ['class' => 'bg-info text-white', 'text' => 'PLANIFICACIÓN'],
                                                default => ['class' => 'bg-secondary text-white', 'text' => 'SIN ESTADO']
                                            };
                                        @endphp
                                        <span class="badge {{ $estadoConfig['class'] }}">{{ $estadoConfig['text'] }}</span>
                                        <div><small class="text-muted mt-1 d-block">Estado</small></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Información adicional -->
                            <div class="mt-3 small text-muted text-center">
                                <div class="d-flex justify-content-between">
                                    <span>Aprobados: <strong class="text-success">{{ $unidad->documentos_aprobados_count ?? 0 }}</strong></span>
                                    <span>Pendientes: <strong class="text-warning">{{ $unidad->documentos_pendientes_count ?? 0 }}</strong></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Footer con botones -->
                        <div class="card-footer bg-transparent p-3">
                            <div class="row g-2">
                                <div class="col-6">
                                   
                                </div>
                                <div class="col-6">
                                    @if($unidadAsignada && $unidadAsignada->id == $unidad->id)
                                        <button class="btn btn-success btn-sm w-100" title="Mi unidad asignada" disabled>
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @else
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-building fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay unidades productivas disponibles</h4>
                        <p class="text-muted">¡Contacta al administrador para crear nuevas unidades!</p>
                    </div>
                </div>
                @endforelse
        </div>
    </div>
</div>

<!-- Modal para detalles de unidad -->
<div class="modal fade" id="detallesUnidadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Detalles de la Unidad
                </h5>
               
            </div>
            <div class="modal-body" id="detallesUnidadContent">
                <!-- Contenido dinámico -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Función para aplicar todos los filtros
    function applyFilters() {
        const searchValue = $('#searchInput').val().toLowerCase();
        const estadoValue = $('#filterEstado').val().toLowerCase();
        const tipoValue = $('#filterTipo').val().toLowerCase();
        const gestorValue = $('#filterGestor').val().toLowerCase();

        let anyVisible = false;

        $('.unidad-card').each(function() {
            const $card = $(this);
            const cardText = $card.text().toLowerCase();
            const cardEstado = $card.data('estado') || '';
            const cardTipo = $card.data('tipo') || '';
            const cardGestor = ($card.data('gestor') || '').toLowerCase();

            const matchesSearch = searchValue === '' || cardText.includes(searchValue);
            const matchesEstado = estadoValue === '' || cardEstado === estadoValue;
            const matchesTipo = tipoValue === '' || cardTipo === tipoValue;
            const matchesGestor = gestorValue === '' || cardGestor.includes(gestorValue);

            const shouldShow = matchesSearch && matchesEstado && matchesTipo && matchesGestor;

            if (shouldShow) {
                $card.css('visibility', 'visible').css('opacity', '1');
                anyVisible = true;
            } else {
                $card.css('visibility', 'hidden').css('opacity', '0');
            }
        });

        // Mostrar u ocultar el mensaje de no resultados
        $('#no-results').remove();
        if (!anyVisible) {
            $('.fixed-grid-container').append(`
                <div class="no-results-overlay" id="no-results">
                    <div class="text-center py-4">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No se encontraron resultados</h5>
                        <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                    </div>
                </div>
            `);
        }
    }

    // Event listeners para todos los filtros
    $('#searchInput').on('input', applyFilters);
    $('#filterEstado').on('change', applyFilters);
    $('#filterTipo').on('change', applyFilters);
    $('#filterGestor').on('change', applyFilters);
});

// Función para ver detalles de unidad
function verDetallesUnidad(unidadId) {
    // Aquí podrías hacer una llamada AJAX para obtener los detalles
    // Por ahora mostraremos un mensaje
    $('#detallesUnidadContent').html(`
        <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin fa-2x text-success mb-3"></i>
            <p>Cargando detalles de la unidad...</p>
        </div>
    `);
    $('#detallesUnidadModal').modal('show');
}

// Función para solicitar asignación
function solicitarAsignacion(unidadId) {
    if (confirm('¿Deseas solicitar ser asignado a esta unidad productiva?')) {
        // Aquí podrías hacer una llamada AJAX para solicitar la asignación
        alert('Solicitud enviada. El administrador revisará tu solicitud.');
    }
}
</script>

<style>
/* Estilos para las estadísticas */
.stat-card {
    transition: all 0.3s ease;
    border-radius: 12px;
    min-height: 90px;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.icon-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.bg-success-light {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
    border: 2px solid rgba(40, 167, 69, 0.2);
}

.bg-primary-light {
    background: linear-gradient(135deg, rgba(0, 123, 255, 0.1), rgba(102, 16, 242, 0.1));
    border: 2px solid rgba(0, 123, 255, 0.2);
}

.bg-info-light {
    background: linear-gradient(135deg, rgba(23, 162, 184, 0.1), rgba(32, 201, 151, 0.1));
    border: 2px solid rgba(23, 162, 184, 0.2);
}

/* Grid inteligente - Se adapta al ancho de pantalla */
.fixed-grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
    position: relative;
    max-width: 100%;
}

/* Ajustes para pantallas muy anchas */
@media (min-width: 1400px) {
    .fixed-grid-container {
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        max-width: 1400px;
        margin-left: auto;
        margin-right: auto;
    }
}

@media (min-width: 1800px) {
    .fixed-grid-container {
        grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
        max-width: 1800px;
    }
}

.fixed-card-slot {
    min-height: 400px;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.no-results-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

/* Responsive mejorado - se adapta mejor */
@media (max-width: 1200px) {
    .fixed-grid-container {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }
}

@media (max-width: 768px) {
    .fixed-grid-container {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    }
    
    .stat-card .card-body {
        padding: 1rem !important;
    }
    
    .icon-circle {
        width: 50px;
        height: 50px;
    }
    
    .icon-circle i {
        font-size: 1.5rem !important;
    }
}

@media (max-width: 576px) {
    .fixed-grid-container {
        grid-template-columns: 1fr;
    }
}

/* Animaciones */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stat-card {
    animation: fadeInUp 0.6s ease-out forwards;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }

/* Números grandes */
.stat-card h2 {
    font-size: 2.5rem;
    line-height: 1;
}

@media (max-width: 768px) {
    .stat-card h2 {
        font-size: 2rem;
    }
}
</style>
@endpush
