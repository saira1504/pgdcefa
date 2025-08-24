@extends('layouts.superadmin')

@section('title', 'Fases del Proyecto - Superadmin')

@section('content')
<!-- Header simple y elegante -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1 text-success">
            <i class="fas fa-layer-group me-2"></i>Fases del Proyecto
        </h1>
        <p class="text-muted small mb-0">Gestiona las fases del proyecto de forma sencilla</p>
    </div>
    <div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createPhaseModal">
            <i class="fas fa-plus me-1"></i>Nueva Fase
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

<!-- Buscador simple -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="position-relative">
            <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" class="form-control ps-5 py-2" 
                   placeholder="Buscar fases..." id="searchInput">
        </div>
    </div>
</div>

<!-- Estadística simple -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="height: 70px;">
            <div class="card-body p-2 d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-layer-group fa-2x text-success"></i>
                </div>
                <div class="flex-grow-1 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-0 small fw-semibold">Total Fases</h6>
                    </div>
                    <div>
                        <h1 class="mb-0 fw-bold text-success display-6">{{ $phases->count() }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grid fijo de fases -->
<div class="fixed-grid-container">
    @forelse($phases->sortBy('numero') as $phase)
    <div class="fixed-card-slot phase-card">
        <div class="card shadow-sm h-100">
            <!-- Header con gradiente -->
            <div class="card-header text-white py-3" 
                 style="background: linear-gradient(135deg, #28a745, #20c997);">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-layer-group me-2"></i>Fase {{ $phase->numero }}
                    </h6>
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-calendar me-1"></i>Cronograma
                    </span>
                </div>
            </div>
            
            <!-- Body -->
            <div class="card-body p-4">
                <!-- Fechas en grid -->
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="bg-light rounded p-3 text-center">
                            <div class="fw-bold text-success">
                                {{ $phase->fecha_inicio ? $phase->fecha_inicio->format('d/m/Y') : 'Sin definir' }}
                            </div>
                            <small class="text-muted">FECHA INICIO</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-3 text-center">
                            <div class="fw-bold text-primary">
                                {{ $phase->fecha_fin ? $phase->fecha_fin->format('d/m/Y') : 'Sin definir' }}
                            </div>
                            <small class="text-muted">FECHA FIN</small>
                        </div>
                    </div>
                </div>

                <!-- Duración -->
                @if($phase->fecha_inicio && $phase->fecha_fin)
                @php
                    $dias = $phase->fecha_inicio->diffInDays($phase->fecha_fin);
                @endphp
                <div class="text-center mb-3">
                    <div class="bg-light rounded p-3">
                        <div class="fw-bold text-info">{{ $dias }} días</div>
                        <small class="text-muted">DURACIÓN</small>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Footer con botones -->
            <div class="card-footer bg-transparent p-3">
                <div class="row g-2">
                    <div class="col-6">
                        <button class="btn btn-outline-success btn-sm w-100 edit-phase-btn" 
                                title="Editar"
                                data-phase-id="{{ $phase->id }}"
                                data-phase-numero="{{ $phase->numero }}"
                                data-phase-fecha-inicio="{{ $phase->fecha_inicio ? $phase->fecha_inicio->format('Y-m-d') : '' }}"
                                data-phase-fecha-fin="{{ $phase->fecha_fin ? $phase->fecha_fin->format('Y-m-d') : '' }}">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                    <div class="col-6">
                        <form action="{{ route('superadmin.phases.destroy', $phase) }}" 
                              method="POST" class="d-inline w-100"
                              onsubmit="return confirm('¿Estás seguro de eliminar esta fase?');">
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
    <div class="empty-state">
        <div class="text-center py-5">
            <i class="fas fa-layer-group fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No hay fases registradas</h4>
            <p class="text-muted">¡Crea una nueva fase para empezar!</p>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createPhaseModal">
                <i class="fas fa-plus me-2"></i>Crear Primera Fase
            </button>
        </div>
    </div>
    @endforelse
</div>

<!-- Modal Crear Fase -->
<div class="modal fade" id="createPhaseModal" tabindex="-1" aria-labelledby="createPhaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <!-- Header del modal -->
            <div class="modal-header text-white py-4 border-0" 
                 style="background: linear-gradient(135deg, #28a745, #20c997);">
                <div class="d-flex align-items-center">
                    <i class="fas fa-plus-circle fa-2x me-3"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="createPhaseModalLabel">Nueva Fase</h5>
                        <small class="opacity-75">Crea una nueva fase para el proyecto</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Body del modal -->
            <div class="modal-body p-4">
                <form id="createPhaseForm" action="{{ route('superadmin.phases.store') }}" method="POST">
                    @csrf
                    
                    <!-- Número de fase -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="create_numero" class="form-label fw-semibold">
                                <i class="fas fa-hashtag text-success me-2"></i>Número de la Fase
                            </label>
                            <input type="number" 
                                   class="form-control form-control-lg" 
                                   id="create_numero" 
                                   name="numero" 
                                   min="1" 
                                   max="100"
                                   placeholder="Ej: 1, 2, 3..."
                                   required>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>Número único para identificar la fase
                            </div>
                        </div>
                    </div>

                    <!-- Fechas -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="create_fecha_inicio" class="form-label fw-semibold">
                                <i class="fas fa-calendar-plus text-success me-2"></i>Fecha de Inicio
                            </label>
                            <input type="date" 
                                   class="form-control form-control-lg" 
                                   id="create_fecha_inicio" 
                                   name="fecha_inicio">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>Fecha cuando inicia la fase (opcional)
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="create_fecha_fin" class="form-label fw-semibold">
                                <i class="fas fa-calendar-check text-success me-2"></i>Fecha de Fin
                            </label>
                            <input type="date" 
                                   class="form-control form-control-lg" 
                                   id="create_fecha_fin" 
                                   name="fecha_fin">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>Fecha cuando termina la fase (opcional)
                            </div>
                        </div>
                    </div>

                    <!-- Duración calculada -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-success d-none" id="createDuracionAlert">
                                <i class="fas fa-clock me-2"></i>
                                <strong>Duración calculada:</strong> 
                                <span id="createDuracionTexto"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Footer del modal -->
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="submit" form="createPhaseForm" class="btn btn-success btn-lg">
                    <i class="fas fa-save me-2"></i>Guardar Fase
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Fase -->
<div class="modal fade" id="editPhaseModal" tabindex="-1" aria-labelledby="editPhaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <!-- Header del modal -->
            <div class="modal-header text-white py-4 border-0" 
                 style="background: linear-gradient(135deg, #28a745, #20c997);">
                <div class="d-flex align-items-center">
                    <i class="fas fa-edit fa-2x me-3"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="editPhaseModalLabel">Editar Fase</h5>
                        <small class="opacity-75">Modifica los datos de la fase</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Body del modal -->
            <div class="modal-body p-4">
                <form id="editPhaseForm" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Número de fase -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="edit_numero" class="form-label fw-semibold">
                                <i class="fas fa-hashtag text-success me-2"></i>Número de la Fase
                            </label>
                            <input type="number" 
                                   class="form-control form-control-lg" 
                                   id="edit_numero" 
                                   name="numero" 
                                   min="1" 
                                   max="100"
                                   required>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>Número único para identificar la fase
                            </div>
                        </div>
                    </div>

                    <!-- Fechas -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="edit_fecha_inicio" class="form-label fw-semibold">
                                <i class="fas fa-calendar-plus text-success me-2"></i>Fecha de Inicio
                            </label>
                            <input type="date" 
                                   class="form-control form-control-lg" 
                                   id="edit_fecha_inicio" 
                                   name="fecha_inicio">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>Fecha cuando inicia la fase (opcional)
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="edit_fecha_fin" class="form-label fw-semibold">
                                <i class="fas fa-calendar-check text-success me-2"></i>Fecha de Fin
                            </label>
                            <input type="date" 
                                   class="form-control form-control-lg" 
                                   id="edit_fecha_fin" 
                                   name="fecha_fin">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>Fecha cuando termina la fase (opcional)
                            </div>
                        </div>
                    </div>

                    <!-- Duración calculada -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-success d-none" id="editDuracionAlert">
                                <i class="fas fa-clock me-2"></i>
                                <strong>Duración calculada:</strong> 
                                <span id="editDuracionTexto"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Footer del modal -->
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="submit" form="editPhaseForm" class="btn btn-success btn-lg">
                    <i class="fas fa-save me-2"></i>Actualizar Fase
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Búsqueda simple
    $('#searchInput').on('input', function() {
        const searchValue = $(this).val().toLowerCase();
        
        $('.phase-card').each(function() {
            const $card = $(this);
            const cardText = $card.text().toLowerCase();
            
            if (searchValue === '' || cardText.includes(searchValue)) {
                $card.css('visibility', 'visible').css('opacity', '1');
            } else {
                $card.css('visibility', 'hidden').css('opacity', '0');
            }
        });

        // Mostrar mensaje si no hay resultados
        const visibleCards = $('.phase-card').filter(function() {
            return $(this).css('visibility') === 'visible';
        }).length;
        
        if (visibleCards === 0 && $('.phase-card').length > 0) {
            if ($('#no-results').length === 0) {
                $('.fixed-grid-container').append(`
                    <div class="no-results-overlay" id="no-results">
                        <div class="text-center py-4">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No se encontraron fases</h5>
                            <p class="text-muted">Intenta con otro término de búsqueda</p>
                        </div>
                    </div>
                `);
            }
        } else {
            $('#no-results').remove();
        }
    });

    // Función para calcular duración
    function calcularDuracion(prefijo) {
        const fechaInicio = $(`#${prefijo}_fecha_inicio`).val();
        const fechaFin = $(`#${prefijo}_fecha_fin`).val();
        
        if (fechaInicio && fechaFin) {
            const inicio = new Date(fechaInicio);
            const fin = new Date(fechaFin);
            
            if (fin >= inicio) {
                const diffTime = Math.abs(fin - inicio);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                $(`#${prefijo}DuracionTexto`).text(diffDays + ' días');
                $(`#${prefijo}DuracionAlert`).removeClass('d-none alert-danger').addClass('alert-success');
            } else {
                $(`#${prefijo}DuracionTexto`).text('La fecha de fin debe ser posterior a la fecha de inicio');
                $(`#${prefijo}DuracionAlert`).removeClass('d-none alert-success').addClass('alert-danger');
            }
        } else {
            $(`#${prefijo}DuracionAlert`).addClass('d-none');
        }
    }
    
    // Event listeners para crear
    $('#create_fecha_inicio, #create_fecha_fin').on('change', function() {
        calcularDuracion('create');
    });
    
    // Event listeners para editar
    $('#edit_fecha_inicio, #edit_fecha_fin').on('change', function() {
        calcularDuracion('edit');
    });

    // Manejar clic en botón editar
    $('.edit-phase-btn').on('click', function() {
        const phaseId = $(this).data('phase-id');
        const phaseNumero = $(this).data('phase-numero');
        const phaseFechaInicio = $(this).data('phase-fecha-inicio');
        const phaseFechaFin = $(this).data('phase-fecha-fin');
        
        // Llenar el formulario de editar
        $('#edit_numero').val(phaseNumero);
        $('#edit_fecha_inicio').val(phaseFechaInicio);
        $('#edit_fecha_fin').val(phaseFechaFin);
        
        // Configurar la acción del formulario
        $('#editPhaseForm').attr('action', `/superadmin/phases/${phaseId}`);
        
        // Calcular duración inicial
        calcularDuracion('edit');
        
        // Mostrar el modal
        $('#editPhaseModal').modal('show');
    });

    // Limpiar formulario al cerrar modal de crear
    $('#createPhaseModal').on('hidden.bs.modal', function() {
        $('#createPhaseForm')[0].reset();
        $('#createDuracionAlert').addClass('d-none');
    });

    // Validación antes de enviar
    $('#createPhaseForm, #editPhaseForm').on('submit', function(e) {
        const form = $(this);
        const prefijo = form.attr('id') === 'createPhaseForm' ? 'create' : 'edit';
        const fechaInicio = $(`#${prefijo}_fecha_inicio`).val();
        const fechaFin = $(`#${prefijo}_fecha_fin`).val();
        
        if (fechaInicio && fechaFin) {
            const inicio = new Date(fechaInicio);
            const fin = new Date(fechaFin);
            
            if (fin < inicio) {
                e.preventDefault();
                alert('La fecha de fin debe ser posterior a la fecha de inicio');
                return false;
            }
        }
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

/* Grid inteligente para fases que se adapta al ancho de pantalla */
.fixed-grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        max-width: 1800px;
    }
}

.fixed-card-slot {
    min-height: 350px;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.empty-state {
    grid-column: 1 / -1;
    width: 100%;
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

/* Estilos para modales */
.modal-dialog-centered {
    display: flex;
    align-items: center;
    min-height: calc(100% - 1rem);
}

.modal-content {
    border-radius: 15px;
    overflow: hidden;
}

.modal-header {
    border-radius: 15px 15px 0 0;
}

/* Mejorar la distribución del contenido principal */
.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
    padding-left: 2rem;
    padding-right: 2rem;
}

@media (min-width: 1400px) {
    .container-fluid {
        max-width: 1600px;
    }
}

@media (min-width: 1800px) {
    .container-fluid {
        max-width: 1800px;
    }
}

/* Ajustes para el header y estadísticas */
.d-flex.justify-content-between {
    max-width: 100%;
}

.col-md-3 {
    max-width: 100%;
}

/* Responsive mejorado */
@media (max-width: 1200px) {
    .fixed-grid-container {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    }
}

@media (max-width: 768px) {
    .fixed-grid-container {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
}

@media (max-width: 576px) {
    .fixed-grid-container {
        grid-template-columns: 1fr;
    }
}

.modal-backdrop.show {
  background-color: transparent !important;
} 
</style>
@endpush