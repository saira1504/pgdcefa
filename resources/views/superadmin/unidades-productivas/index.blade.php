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
            <option value="">☀️ Todos los estados</option>
            <option value="proceso">🚀 En proceso</option>
            <option value="iniciando">🌱 Iniciando</option>
            <option value="completado">✅ Completado</option>
            <option value="pausado">⏸️ Pausado</option>
        </select>
    </div>
    <div class="col-md-3">
        <select class="form-select form-select-sm py-2" id="filterTipo">
            <option value="">📋 Todos los sectores</option>
            <option value="administrativa">📋 Administrativa</option>
            <option value="investigacion">🔬 Investigación</option>
            <option value="comercializacion">💼 Comercialización</option>
            <option value="produccion">🏭 Producción</option>
            <option value="pecuaria">🐄 Pecuaria</option>
            <option value="agricola">🌾 Agrícola</option>
            <option value="ambiental">🌿 Ambiental</option>
            <option value="ventas">💰 Ventas</option>
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

<!-- Estadísticas HORIZONTALES - Largas hacia los lados pero bajas -->
<div class="row mb-4">
    <div class="col-md-3 mb-2">
        <div class="card border-0 shadow-sm" style="height: 70px;">
            <div class="card-body p-2 d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-industry fa-2x text-success"></i>
                </div>
                <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-0 small fw-semibold">Total Unidades</h6>
                    </div>
                    <div>
                        <h1 class="mb-0 fw-bold text-success display-6">{{ $totalUnidades ?? 3 }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="card border-0 shadow-sm" style="height: 70px;">
            <div class="card-body p-2 d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-users fa-2x text-primary"></i>
                </div>
                <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-0 small fw-semibold">Total Aprendices</h6>
                    </div>
                    <div>
                        <h1 class="mb-0 fw-bold text-primary display-6">{{ $totalAprendices ?? 2 }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="card border-0 shadow-sm" style="height: 70px;">
            <div class="card-body p-2 d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-file-alt fa-2x text-info"></i>
                </div>
                <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-0 small fw-semibold">Total Documentos</h6>
                    </div>
                    <div>
                        <h1 class="mb-0 fw-bold text-info display-6">{{ $totalDocumentos ?? 0 }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="card border-0 shadow-sm" style="height: 70px;">
            <div class="card-body p-2 d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-chart-line fa-2x text-warning"></i>
                </div>
                <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-0 small fw-semibold">Progreso Promedio</h6>
                    </div>
                    <div>
                        <h1 class="mb-0 fw-bold text-warning display-6">{{ $progresoPromedio ?? 0 }}%</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- GRID FIJO de 4 columnas - NUNCA cambia de tamaño -->
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
                            <div class="fw-bold text-primary">{{ $unidad->aprendices_count ?? 0 }}</div>
                            <small class="text-muted">Aprendices</small>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="bg-light rounded p-3 text-center">
                            <div class="fw-bold text-info">{{ $unidad->documentos_count ?? 0 }}</div>
                            <small class="text-muted">Documentos</small>
                        </div>
                    </div>
                    <div class="col-6">
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
                
                <!-- Progreso -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold text-dark">Progreso del Proyecto</span>
                        <span class="fw-bold text-success">{{ $unidad->progreso ?? 0 }}%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" 
                             style="width: {{ $unidad->progreso ?? 0 }}%">
                        </div>
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
                        field.after(`<div class="invalid-feedback d-block">${value[0]}</div>`);
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

/* GRID FIJO - Siempre 4 columnas exactas */
.fixed-grid-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr); /* SIEMPRE 4 columnas iguales */
    gap: 1.5rem;
    margin-bottom: 2rem;
    position: relative;
}

.fixed-card-slot {
    /* Cada tarjeta ocupa exactamente 1/4 del ancho */
    min-height: 450px;
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

/* Responsive - mantiene proporciones */
@media (max-width: 1200px) {
    .fixed-grid-container {
        grid-template-columns: repeat(3, 1fr); /* 3 columnas en pantallas medianas */
    }
}

@media (max-width: 768px) {
    .fixed-grid-container {
        grid-template-columns: repeat(2, 1fr); /* 2 columnas en tablets */
    }
}

@media (max-width: 576px) {
    .fixed-grid-container {
        grid-template-columns: 1fr; /* 1 columna en móviles */
    }
}
</style>
@endpush