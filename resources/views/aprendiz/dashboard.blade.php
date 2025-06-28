@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Tu sidebar original -->
        <div class="col-md-3 col-lg-2 px-0">
            @include('partials.sidebar_aprendiz')
        </div>
        
        <!-- Contenido principal -->
        <div class="col-md-9 col-lg-10 p-4">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>¡Bienvenido, {{ Auth::user()->name }}!</h2>
                    <p class="text-muted mb-0">Panel de Aprendiz - SENA Empresa</p>
                </div>
                <div class="text-end">
                    <small class="text-muted">Último acceso: {{ now()->format('d/m/Y H:i') }}</small>
                </div>
            </div>

            @if(!$unidadAsignada)
                <!-- Sin unidad asignada -->
                <div class="alert alert-warning text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3 text-warning"></i>
                    <h4>No tienes una unidad productiva asignada</h4>
                    <p class="mb-0">Contacta con tu administrador para que te asigne una unidad productiva.</p>
                </div>
            @else
                <!-- Cards de estadísticas -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-success">
                            <div class="card-body text-center">
                                <i class="fas fa-building fa-2x mb-2"></i>
                                <h6>{{ $unidadAsignada->nombre }}</h6>
                                <p class="mb-0 small">Mi Unidad</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-file-alt fa-2x mb-2"></i>
                                <h4>{{ $documentosPendientes }}</h4>
                                <p class="mb-0">Documentos Pendientes</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-info">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-line fa-2x mb-2"></i>
                                <h4>{{ $progresoReal }}%</h4>
                                <p class="mb-0">Mi Progreso</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-danger">
                            <div class="card-body text-center">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                                <h4>{{ count($proximasEntregas) }}</h4>
                                <p class="mb-0">Entregas Próximas</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Mi Unidad Productiva -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-industry me-2"></i>Mi Unidad Productiva</h5>
                            </div>
                            <div class="card-body">
                                <!-- Información de la unidad -->
                                <div class="card border-start border-success border-4 mb-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h5 class="card-title text-success">{{ $unidadAsignada->nombre }}</h5>
                                                <p class="text-muted mb-2">{{ $unidadAsignada->descripcion }}</p>
                                                <p class="mb-1"><strong>Gestor:</strong> {{ $unidadAsignada->gestor_nombre ?? 'Por asignar' }}</p>
                                                <p class="mb-0"><strong>Estado:</strong> 
                                                    <span class="badge bg-success">{{ $unidadAsignada->estado ?? 'Activa' }}</span>
                                                </p>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="mb-3">
                                                    <div class="progress mb-2" style="height: 20px;">
                                                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                                             style="width: {{ $progresoReal }}%">
                                                            {{ $progresoReal }}%
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">Mi progreso general</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Estadísticas de la unidad -->
                                        <div class="row text-center mt-3 pt-3 border-top">
                                            <div class="col-4">
                                                <h4 class="text-primary mb-1">{{ $unidadAsignada->total_aprendices ?? 21 }}</h4>
                                                <small class="text-muted">Aprendices</small>
                                            </div>
                                            <div class="col-4">
                                                <h4 class="text-warning mb-1">{{ $unidadAsignada->total_documentos ?? 11 }}</h4>
                                                <small class="text-muted">Documentos</small>
                                            </div>
                                            <div class="col-4">
                                                <h4 class="text-success mb-1">{{ $progresoReal }}%</h4>
                                                <small class="text-muted">Completado</small>
                                            </div>
                                        </div>

                                        <!-- Botones de acción -->
                                        <div class="mt-4 d-flex gap-2">
                                            <a href="{{ route('aprendiz.mi-unidad') }}" class="btn btn-primary">
                                                <i class="fas fa-eye me-1"></i>Ver detalles
                                            </a>
                                            <a href="{{ route('aprendiz.documentos') }}" class="btn btn-success">
                                                <i class="fas fa-upload me-1"></i>Subir documentos
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Documentos pendientes -->
                                @if(count($documentosPendientesList) > 0)
                                <div class="alert alert-warning">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-exclamation-triangle me-2"></i>Documentos pendientes por subir:
                                    </h6>
                                    <div class="row">
                                        @foreach($documentosPendientesList as $doc)
                                        <div class="col-md-6 mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-alt text-warning me-2"></i>
                                                <span>{{ $doc->nombre }}</span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <hr>
                                    <a href="{{ route('aprendiz.documentos') }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-upload me-1"></i>Subir documentos ahora
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar derecho -->
                    <div class="col-lg-4">
                        <!-- Próximas entregas -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-clock me-2"></i>Próximas Entregas
                                </h6>
                            </div>
                            <div class="card-body">
                                @if(count($proximasEntregas) > 0)
                                    @foreach($proximasEntregas as $entrega)
                                    <div class="border-start border-danger border-4 ps-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0">{{ $entrega->documento }}</h6>
                                            <span class="badge bg-danger">¡Urgente!</span>
                                        </div>
                                        <p class="text-muted small mb-1">{{ $unidadAsignada->nombre }}</p>
                                        <p class="text-danger small mb-2">
                                            <i class="fas fa-calendar me-1"></i>
                                            Vence: {{ \Carbon\Carbon::parse($entrega->fecha_limite)->format('d/m/Y') }}
                                        </p>
                                        <a href="{{ route('aprendiz.documentos') }}" class="btn btn-danger btn-sm w-100">
                                            <i class="fas fa-upload me-1"></i>Subir ahora
                                        </a>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-success py-3">
                                        <i class="fas fa-check-circle fa-3x mb-2"></i>
                                        <p class="mb-0">¡Todas las entregas están al día!</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Accesos rápidos -->
                        <div class="card shadow-sm">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">Accesos Rápidos</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('aprendiz.mi-unidad') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-building me-2"></i>Mi Unidad Productiva
                                    </a>
                                    <a href="{{ route('aprendiz.documentos') }}" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-file-upload me-2"></i>Gestionar Documentos
                                    </a>
                                    <a href="{{ route('aprendiz.progreso') }}" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-chart-line me-2"></i>Ver Mi Progreso
                                    </a>
                                    <button class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-question-circle me-2"></i>Ayuda y Soporte
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
