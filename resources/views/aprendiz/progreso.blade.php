@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 px-0">
            @include('partials.sidebar_aprendiz')
        </div>
        
        <div class="col-md-9 col-lg-10 p-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('aprendiz.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Mi Progreso</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Mi Progreso</h2>
                    <p class="text-muted mb-0">Seguimiento detallado de tu avance en {{ $unidadAsignada->nombre }}</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-success fs-6">{{ $progreso['general'] }}% Completado</span>
                </div>
            </div>

            <!-- Progreso General -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-line me-2"></i>Progreso General
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="progress mb-3" style="height: 30px;">
                                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                     role="progressbar" 
                                     style="width: {{ $progreso['general'] }}%" 
                                     aria-valuenow="{{ $progreso['general'] }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ $progreso['general'] }}%
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="col-3">
                                    <h4 class="text-success mb-1">{{ $progreso['documentos_aprobados'] }}</h4>
                                    <small class="text-muted">Aprobados</small>
                                </div>
                                <div class="col-3">
                                    <h4 class="text-warning mb-1">{{ $progreso['documentos_pendientes'] }}</h4>
                                    <small class="text-muted">Pendientes</small>
                                </div>
                                <div class="col-3">
                                    <h4 class="text-info mb-1">{{ $progreso['documentos_en_revision'] }}</h4>
                                    <small class="text-muted">En Revisión</small>
                                </div>
                                <div class="col-3">
                                    <h4 class="text-danger mb-1">{{ $progreso['documentos_rechazados'] }}</h4>
                                    <small class="text-muted">Rechazados</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Información de la Unidad
                            </h6>
                        </div>
                        <div class="card-body">
                            <h6>{{ $unidadAsignada->nombre }}</h6>
                            <p class="text-muted small">{{ $unidadAsignada->descripcion }}</p>
                            <hr>
                            <div class="row text-center">
                                <div class="col-6">
                                    <h5 class="text-primary mb-1">{{ $unidadAsignada->aprendices_count ?? 0 }}</h5>
                                    <small class="text-muted">Aprendices</small>
                                </div>
                                <div class="col-6">
                                    <h5 class="text-success mb-1">{{ $unidadAsignada->progreso ?? 0 }}%</h5>
                                    <small class="text-muted">Progreso Unidad</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalle por Categorías -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Progreso por Categorías
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card border-success">
                                <div class="card-body">
                                    <h6 class="card-title text-success">
                                        <i class="fas fa-project-diagram me-2"></i>Proyecto
                                    </h6>
                                    <div class="progress mb-2" style="height: 20px;">
                                        <div class="progress-bar bg-success" style="width: 75%">75%</div>
                                    </div>
                                    <small class="text-muted">3 de 4 documentos completados</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="card border-info">
                                <div class="card-body">
                                    <h6 class="card-title text-info">
                                        <i class="fas fa-search me-2"></i>Investigación
                                    </h6>
                                    <div class="progress mb-2" style="height: 20px;">
                                        <div class="progress-bar bg-info" style="width: 50%">50%</div>
                                    </div>
                                    <small class="text-muted">1 de 2 documentos completados</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h6 class="card-title text-warning">
                                        <i class="fas fa-clipboard-check me-2"></i>Evaluación
                                    </h6>
                                    <div class="progress mb-2" style="height: 20px;">
                                        <div class="progress-bar bg-warning" style="width: 0%">0%</div>
                                    </div>
                                    <small class="text-muted">0 de 1 documentos completados</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="card border-secondary">
                                <div class="card-body">
                                    <h6 class="card-title text-secondary">
                                        <i class="fas fa-cogs me-2"></i>Operación
                                    </h6>
                                    <div class="progress mb-2" style="height: 20px;">
                                        <div class="progress-bar bg-secondary" style="width: 0%">0%</div>
                                    </div>
                                    <small class="text-muted">0 de 1 documentos completados</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Próximos Pasos -->
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Próximos Pasos Recomendados
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-warning">Documentos Prioritarios</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                    <strong>Informe de Avance</strong> - Documento obligatorio
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                    <strong>Cronograma de Actividades</strong> - Documento obligatorio
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-info-circle text-info me-2"></i>
                                    <strong>Plan de Negocio</strong> - Documento obligatorio
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">Consejos para Mejorar</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Revisa los comentarios de documentos rechazados
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Mantén comunicación constante con tu gestor
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Sube documentos con anticipación a las fechas límite
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        <a href="{{ route('aprendiz.documentos') }}" class="btn btn-success me-2">
                            <i class="fas fa-upload me-2"></i>Subir Documentos
                        </a>
                        <a href="{{ route('aprendiz.mi-unidad') }}" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-2"></i>Ver Mi Unidad
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animación de barras de progreso
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.transition = 'width 1.5s ease-in-out';
            bar.style.width = width;
        }, 500);
    });
});
</script>
@endsection 