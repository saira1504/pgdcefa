@extends('layouts.aprendiz')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Header -->
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>¡Bienvenido, {{ Auth::user()->name }}!</h2>
                    <p class="text-muted mb-0">Panel de Aprendiz - SENA Empresa</p>
                </div>
                <div class="text-end">
                    <small class="text-muted">Último acceso: {{ now()->format('d/m/Y H:i') }}</small>
                </div>
            </div>
        </div>

        @if(!$unidadAsignada)
            <!-- Sin unidad asignada -->
            <div class="col-12">
                <div class="alert alert-warning text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3 text-warning"></i>
                    <h4>No tienes una unidad productiva asignada</h4>
                    <p class="mb-0">Contacta con tu administrador para que te asigne una unidad productiva.</p>
                </div>
            </div>
        @else
            <!-- Cards de estadísticas -->
            <div class="col-12">
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
            </div>

            <div class="col-12">
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
                                    </div>
                                </div>

                                <!-- Acciones rápidas -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <a href="{{ route('aprendiz.mi-unidad') }}" class="btn btn-outline-success w-100">
                                            <i class="fas fa-eye me-2"></i>Ver Detalles
                                        </a>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <a href="{{ route('aprendiz.documentos-requeridos') }}" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-file-alt me-2"></i>Documentos
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Próximas Entregas -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-warning text-white">
                                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Próximas Entregas</h5>
                            </div>
                            <div class="card-body">
                                @if(count($proximasEntregas) > 0)
                                    @foreach($proximasEntregas as $entrega)
                                        <div class="d-flex align-items-center mb-3 p-2 border rounded">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-file-alt text-warning fa-lg"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">{{ $entrega->tarea->nombre ?? 'Tarea' }}</h6>
                                                <small class="text-muted">
                                                    Vence: {{ \Carbon\Carbon::parse($entrega->fecha_limite)->format('d/m/Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-muted py-3">
                                        <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                                        <p class="mb-0">No hay entregas pendientes</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
