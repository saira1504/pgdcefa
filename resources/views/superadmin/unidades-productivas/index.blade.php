@extends('layouts.superadmin')

@section('title', 'Unidades Productivas - Superadmin')

@section('content')
<!-- Header con gradiente -->
<div class="d-flex justify-content-between align-items-center mb-5 animate-slide-up">
    <div>
        <h1 class="display-4 fw-bold mb-3" style="background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
            <i class="fas fa-seedling me-3"></i>Unidades Productivas
        </h1>
        <p class="text-muted fs-5 mb-0">Gestiona todas las unidades productivas del sistema con estilo moderno</p>
    </div>
    <br>
    <div class="d-flex gap-3">
        <button class="btn btn-outline-modern btn-modern" data-bs-toggle="modal" data-bs-target="#createUnidadModal">
            <i class="fas fa-plus me-2"></i>Nueva Unidad
        </button>
        <button class="btn btn-primary-modern btn-modern" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
            <i class="fas fa-cloud-upload-alt me-2"></i>Subir Documento
        </button>
    </div>
</div>

<!-- Alertas mejoradas -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show animate-slide-up" role="alert" style="border-radius: 16px; border: none; background: rgba(82, 183, 136, 0.1); color: var(--dark-green); backdrop-filter: blur(10px);">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show animate-slide-up" role="alert" style="border-radius: 16px; border: none; background: rgba(220, 53, 69, 0.1); backdrop-filter: blur(10px);">
        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Búsqueda y filtros mejorados -->
<div class="row mb-5 animate-slide-up">
    <div class="col-md-8">
        <div class="position-relative">
            <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-4 text-muted" style="z-index: 10;"></i>
            <input type="text" class="form-control form-control-modern ps-5" 
                   placeholder="Buscar unidades productivas..." id="searchInput"
                   style="height: 60px; font-size: 1.1rem;">
        </div>
    </div>
    <div class="col-md-4">
        <select class="form-select form-control-modern" id="filterEstado" style="height: 60px; font-size: 1.1rem;">
            <option value="">🌟 Todos los estados</option>
            <option value="proceso">🚀 En proceso</option>
            <option value="iniciando">🌱 Iniciando</option>
            <option value="completado">✅ Completado</option>
            <option value="pausado">⏸️ Pausado</option>
        </select>
    </div>
</div>

<!-- Estadísticas mejoradas -->
<div class="row mb-5">
    <div class="col-md-3 mb-4">
        <div class="stats-card animate-slide-up" style="animation-delay: 0.1s;">
            <div class="mb-3">
                <i class="fas fa-industry fa-2x" style="color: var(--secondary-color);"></i>
            </div>
            <div class="stats-number">{{ $totalUnidades ?? 12 }}</div>
            <div class="text-muted fw-semibold">Total Unidades</div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stats-card animate-slide-up" style="animation-delay: 0.2s;">
            <div class="mb-3">
                <i class="fas fa-users fa-2x" style="color: var(--accent-color);"></i>
            </div>
            <div class="stats-number">{{ $totalAprendices ?? 245 }}</div>
            <div class="text-muted fw-semibold">Total Aprendices</div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stats-card animate-slide-up" style="animation-delay: 0.3s;">
            <div class="mb-3">
                <i class="fas fa-file-alt fa-2x" style="color: var(--primary-color);"></i>
            </div>
            <div class="stats-number">{{ $totalDocumentos ?? 1234 }}</div>
            <div class="text-muted fw-semibold">Total Documentos</div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stats-card animate-slide-up" style="animation-delay: 0.4s;">
            <div class="mb-3">
                <i class="fas fa-chart-line fa-2x" style="color: var(--secondary-color);"></i>
            </div>
            <div class="stats-number">{{ $progresoPromedio ?? 78 }}%</div>
            <div class="text-muted fw-semibold">Progreso Promedio</div>
        </div>
    </div>
</div>

<!-- Tarjetas de Unidades Productivas mejoradas -->
<div class="row mb-5">
    @forelse($unidadesProductivas as $index => $unidad)
    <div class="col-lg-6 col-xl-4 mb-4">
        <div class="card card-modern h-100" style="animation-delay: {{ $index * 0.1 }}s;">
            <div class="card-header text-white position-relative" style="background: var(--gradient-primary); padding: 2rem;">
                <div class="position-absolute top-0 end-0 p-3">
                    <span class="badge" style="background: rgba(255,255,255,0.2); font-size: 0.8rem;">
                        {{ strtoupper($unidad->tipo ?? 'General') }}
                    </span>
                </div>
                <h4 class="card-title mb-3 fw-bold">
                    <i class="fas fa-leaf me-2"></i>{{ $unidad->nombre }}
                </h4>
                <p class="mb-0 opacity-90">{{ Str::limit($unidad->proyecto, 80) }}</p>
            </div>
            
            <div class="card-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="text-center p-3 rounded-3" style="background: rgba(82, 183, 136, 0.1);">
                            <div class="fw-bold fs-4" style="color: var(--secondary-color);">
                                {{ $unidad->adminPrincipal->name ?? 'N/A' }}
                            </div>
                            <small class="text-muted fw-semibold">GESTOR</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 rounded-3" style="background: rgba(116, 198, 157, 0.1);">
                            <div class="fw-bold fs-4" style="color: var(--accent-color);">
                                {{ $unidad->aprendices_count }}
                            </div>
                            <small class="text-muted fw-semibold">APRENDICES</small>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="text-center p-3 rounded-3" style="background: rgba(45, 90, 39, 0.1);">
                            <div class="fw-bold fs-4" style="color: var(--primary-color);">
                                {{ $unidad->documentos_count }}
                            </div>
                            <small class="text-muted fw-semibold">DOCUMENTOS</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 rounded-3">
                            <span class="badge fs-6 px-3 py-2" style="background: var(--gradient-secondary); color: var(--dark-green);">
                                {{ strtoupper($unidad->estado) }}
                            </span>
                            <div><small class="text-muted fw-semibold mt-2 d-block">ESTADO</small></div>
                        </div>
                    </div>
                </div>
                
                <!-- Progreso mejorado -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold" style="color: var(--dark-green);">Progreso del Proyecto</span>
                        <span class="fw-bold fs-5" style="color: var(--secondary-color);">{{ $unidad->progreso }}%</span>
                    </div>
                    <div class="progress progress-modern">
                        <div class="progress-bar progress-bar-modern" 
                             style="width: {{ $unidad->progreso }}%"
                             role="progressbar" 
                             aria-valuenow="{{ $unidad->progreso }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer p-4" style="background: rgba(248, 250, 252, 0.5);">
                <div class="d-grid gap-2">
                    <div class="row g-2">
                        <div class="col-4">
                            <a href="{{ route('superadmin.unidades-productivas.show', $unidad->id) }}" 
                               class="btn btn-outline-modern btn-modern w-100">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('superadmin.unidades-productivas.edit', $unidad->id) }}" 
                               class="btn btn-success-modern btn-modern w-100">
                                <i class="fas fa-cog"></i>
                            </a>
                        </div>
                        <div class="col-4">
                            <form action="{{ route('superadmin.unidades-productivas.destroy', $unidad->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar esta unidad?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-modern w-100" 
                                        style="background: linear-gradient(135deg, #dc3545, #c82333); color: white;">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-seedling fa-4x" style="color: var(--accent-color); opacity: 0.5;"></i>
            </div>
            <h3 class="text-muted mb-3">No hay unidades productivas registradas</h3>
            <p class="text-muted fs-5">¡Crea una nueva unidad para empezar tu gestión!</p>
            <button class="btn btn-primary-modern btn-modern mt-3" data-bs-toggle="modal" data-bs-target="#createUnidadModal">
                <i class="fas fa-plus me-2"></i>Crear Primera Unidad
            </button>
        </div>
    </div>
    @endforelse
</div>

<!-- Modals -->
@include('superadmin.unidades-productivas.modals.upload-document')
@include('superadmin.unidades-productivas.modals.create-unidad')
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Búsqueda mejorada con debounce
    let searchTimeout;
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        const value = $(this).val().toLowerCase();
        
        searchTimeout = setTimeout(() => {
            $('.card-modern').each(function() {
                const text = $(this).text().toLowerCase();
                const shouldShow = text.indexOf(value) > -1;
                
                if (shouldShow) {
                    $(this).parent().fadeIn(300);
                } else {
                    $(this).parent().fadeOut(300);
                }
            });
        }, 300);
    });
    
    // Filtro por estado mejorado
    $('#filterEstado').on('change', function() {
        const estado = $(this).val().toLowerCase();
        
        $('.card-modern').each(function() {
            const cardEstado = $(this).find('.badge').text().toLowerCase();
            const shouldShow = estado === '' || cardEstado.indexOf(estado) > -1;
            
            if (shouldShow) {
                $(this).parent().fadeIn(300);
            } else {
                $(this).parent().fadeOut(300);
            }
        });
    });

    // AJAX para formulario de creación
    $('#createUnidadForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        // Mostrar loading
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Creando...');
        submitBtn.prop('disabled', true);
        
        // Limpiar errores previos
        $('.invalid-feedback').remove();
        $('.form-control').removeClass('is-invalid');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#createUnidadModal').modal('hide');
                
                // Mostrar notificación de éxito
                const successAlert = `
                    <div class="alert alert-success alert-dismissible fade show animate-slide-up" role="alert" style="border-radius: 16px; border: none; background: rgba(82, 183, 136, 0.1); color: var(--dark-green); backdrop-filter: blur(10px);">
                        <i class="fas fa-check-circle me-2"></i>Unidad productiva creada exitosamente
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('.content-wrapper').prepend(successAlert);
                
                // Recargar después de un momento
                setTimeout(() => location.reload(), 1500);
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        const field = $('#' + key);
                        field.addClass('is-invalid');
                        field.after(`<div class="invalid-feedback d-block animate-slide-up">${value[0]}</div>`);
                    });
                } else {
                    alert('Error inesperado. Por favor, inténtalo de nuevo.');
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
@endpush