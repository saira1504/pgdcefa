@extends('layouts.superadmin')

@section('title', 'Unidades Productivas - Superadmin')

@section('content')
<!-- Header compacto -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1 text-success">
            <i class="fas fa-seedling me-2"></i>Unidades Productivas
        </h1>
        <p class="text-muted small mb-0">Gestiona todas las unidades productivas del sistema con estilo moderno</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#createUnidadModal">
            <i class="fas fa-plus me-1"></i>Nueva Unidad
        </button>
    </div>
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

<!-- Filtros más delgados -->
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
            <option value="proceso">🚀 En proceso</option>
            <option value="iniciando">🌱 Iniciando</option>
            <option value="completado">✅ Completado</option>
            <option value="pausado">⏸ Pausado</option>
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
            @if(isset($gestores))
                @foreach($gestores as $gestor)
                    <option value="{{ $gestor->name }}">{{ $gestor->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>

<!-- 📊 ESTADÍSTICAS HORIZONTALES SIN PROGRESO -->
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
                        <small class="text-muted">Productivas</small>
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
                        <h6 class="text-muted mb-0 small fw-semibold">Total Aprendices</h6>
                        <small class="text-muted">Del sistema</small>
                    </div>
                    <div class="text-end">
                        <h2 class="mb-0 fw-bold text-primary">{{ $totalAprendices ?? 0 }}</h2>
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
                        <i class="fas fa-file-alt fa-2x text-info"></i>
                    </div>
                </div>
                <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-0 small fw-semibold">Total Documentos</h6>
                        <small class="text-muted">Subidos</small>
                    </div>
                    <div class="text-end">
                        <h2 class="mb-0 fw-bold text-info">{{ $totalDocumentos ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- GRID FIJO de 4 columnas - SIN PROGRESO -->
<div class="fixed-grid-container">
    @forelse($unidadesProductivas as $unidad)
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
            
            <!-- Body SIN PROGRESO -->
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
                            <div class="fw-bold text-primary">{{ $unidad->aprendices_con_documentos_count ?? 0 }}</div>
                            <small class="text-muted">Aprendiz</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-3 text-center">
                            <div class="fw-bold text-info">{{ $unidad->documentos_aprendiz_count ?? 0 }}</div>
                            <small class="text-muted">Documentos</small>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-15">
                        <div class="bg-light rounded p-3 text-center">
                            @php
                                $estadoConfig = match($unidad->estado ?? 'iniciando') {
                                    'iniciando' => ['class' => 'bg-warning text-dark', 'text' => 'INICIANDO'],
                                    'proceso' => ['class' => 'bg-primary text-white', 'text' => 'EN PROCESO'],
                                    'pausado' => ['class' => 'bg-secondary text-white', 'text' => 'PAUSADO'],
                                    'completado' => ['class' => 'bg-success text-white', 'text' => 'COMPLETADO'],
                                    default => ['class' => 'bg-secondary text-white', 'text' => 'SIN ESTADO']
                                };
                            @endphp
                            <span class="badge {{ $estadoConfig['class'] }}">{{ $estadoConfig['text'] }}</span>
                            <div><small class="text-muted mt-1 d-block">Estado</small></div>
                        </div>
                    </div>
                </div>

                <!-- Información adicional SIN progreso -->
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
                    <div class="col-4">
                        <a href="{{ route('superadmin.unidades-productivas.show', $unidad->id) }}" 
                           class="btn btn-outline-primary btn-sm w-100" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="{{ route('superadmin.unidades-productivas.edit', $unidad->id) }}" 
                           class="btn btn-outline-success btn-sm w-100" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                    <div class="col-4">
                        <form action="{{ route('superadmin.unidades-productivas.destroy', $unidad->id) }}" 
                              method="POST" class="d-inline"
                              onsubmit="return confirm('¿Estás seguro de eliminar esta unidad?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100" title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5">
            <i class="fas fa-seedling fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No hay unidades productivas registradas</h4>
            <p class="text-muted">¡Crea una nueva unidad para empezar tu gestión!</p>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createUnidadModal">
                <i class="fas fa-plus me-2"></i>Crear Primera Unidad
            </button>
        </div>
    </div>
    @endforelse
</div>

<!-- Modals -->
@include('superadmin.unidades-productivas.modals.create-unidad')
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

    // AJAX para formulario de creación
    $('#createUnidadForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Creando...');
        submitBtn.prop('disabled', true);
        
        $('.invalid-feedback').remove();
        $('.form-control, .form-select').removeClass('is-invalid');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#createUnidadModal').modal('hide');
                
                // Mostrar alerta de éxito con animación
                const alert = `
                    <div class="alert alert-success alert-dismissible fade show animate-slide-up" role="alert" 
                         style="border-radius: 16px; border: none; background: rgba(82, 183, 136, 0.1); color: #1b4332; backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle me-2"></i>¡Unidad productiva "${response.unidad?.nombre || 'nueva'}" creada exitosamente!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('main').prepend(alert);
                
                // Auto-dismiss después de 5 segundos
                setTimeout(() => {
                    $('.alert-success').fadeOut();
                }, 5000);
                
                // Recargar página después de 2 segundos
                setTimeout(() => location.reload(), 2000);
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        const field = $('#' + key);
                        field.addClass('is-invalid');
                        field.after(<div class="invalid-feedback d-block">${value[0]}</div>);
                    });
                } else {
                    // Mostrar alerta de error
                    const errorAlert = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>Error inesperado. Por favor, inténtalo de nuevo.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    $('main').prepend(errorAlert);
                }
            },
            complete: function() {
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });
});
</script>

<style>
/* 🎨 ESTILOS PARA LAS ESTADÍSTICAS MEJORADAS */
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

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slide-up {
    animation: slideUp 0.6s ease-out forwards;
}

/* GRID INTELIGENTE - Se adapta al ancho de pantalla */
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
    min-height: 350px; /* Reducido sin progreso */
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

/* 🎯 ANIMACIONES */
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

/* 🔢 NÚMEROS GRANDES */
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