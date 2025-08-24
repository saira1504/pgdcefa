@extends('layouts.aprendiz')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('aprendiz.phases.index') }}" class="text-decoration-none">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Fases
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Fase {{ $phase->numero }}
                            </li>
                        </ol>
                    </nav>
                    <h1 class="h3 mb-0 text-success mt-2">
                        <i class="fas fa-layer-group me-2"></i>
                        Fase {{ $phase->numero }}
                    </h1>
                </div>
                <div class="text-end">
                    @if($phase->fecha_inicio && $phase->fecha_fin)
                        @php
                            $now = now();
                            $start = $phase->fecha_inicio;
                            $end = $phase->fecha_fin;
                            
                            if ($now < $start) {
                                $status = 'pending';
                                $statusText = 'Pendiente';
                                $statusColor = 'warning';
                                $statusIcon = 'clock';
                            } elseif ($now > $end) {
                                $status = 'completed';
                                $statusText = 'Completada';
                                $statusColor = 'success';
                                $statusIcon = 'check-circle';
                            } else {
                                $status = 'active';
                                $statusText = 'En Progreso';
                                $statusColor = 'info';
                                $statusIcon = 'play-circle';
                            }
                        @endphp
                        <span class="badge bg-{{ $statusColor }} fs-6">
                            <i class="fas fa-{{ $statusIcon }} me-1"></i>
                            {{ $statusText }}
                        </span>
                    @else
                        <span class="badge bg-secondary fs-6">
                            <i class="fas fa-question-circle me-1"></i>
                            Sin Estado
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información Principal -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Información de la Fase
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="me-3">
                                    <i class="fas fa-calendar-plus fa-2x text-success"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Fecha de Inicio</small>
                                    <strong class="text-success fs-5">
                                        {{ $phase->fecha_inicio ? $phase->fecha_inicio->format('d/m/Y') : 'No definida' }}
                                    </strong>
                                    @if($phase->fecha_inicio)
                                        <small class="text-muted d-block">
                                            {{ $phase->fecha_inicio->diffForHumans() }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="me-3">
                                    <i class="fas fa-calendar-check fa-2x text-success"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Fecha de Fin</small>
                                    <strong class="text-success fs-5">
                                        {{ $phase->fecha_fin ? $phase->fecha_fin->format('d/m/Y') : 'No definida' }}
                                    </strong>
                                    @if($phase->fecha_fin)
                                        <small class="text-muted d-block">
                                            {{ $phase->fecha_fin->diffForHumans() }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($phase->fecha_inicio && $phase->fecha_fin)
                        @php
                            $total = $end->diffInDays($start);
                            $elapsed = $now->diffInDays($start);
                            $remaining = $end->diffInDays($now);
                            $progress = min(100, max(0, ($elapsed / $total) * 100));
                        @endphp
                        
                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Progreso de la Fase</h6>
                                <span class="badge bg-success">{{ number_format($progress, 1) }}%</span>
                            </div>
                            <div class="progress mb-3" style="height: 12px;">
                                <div class="progress-bar bg-success" 
                                     role="progressbar" 
                                     style="width: {{ $progress }}%" 
                                     aria-valuenow="{{ $progress }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                            
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded">
                                        <h5 class="text-success mb-1">{{ $total }}</h5>
                                        <small class="text-muted">Días Totales</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded">
                                        <h5 class="text-info mb-1">{{ $elapsed }}</h5>
                                        <small class="text-muted">Días Transcurridos</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded">
                                        <h5 class="text-warning mb-1">{{ max(0, $remaining) }}</h5>
                                        <small class="text-muted">Días Restantes</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actividades Sugeridas -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-success">
                        <i class="fas fa-tasks me-2"></i>
                        Actividades Sugeridas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Consejo:</strong> Para aprovechar al máximo esta fase, te recomendamos:
                    </div>
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-3"></i>
                            <div>
                                <strong>Revisar documentación</strong>
                                <small class="text-muted d-block">Consulta los materiales de apoyo disponibles</small>
                            </div>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-3"></i>
                            <div>
                                <strong>Planificar actividades</strong>
                                <small class="text-muted d-block">Organiza tu tiempo según las fechas establecidas</small>
                            </div>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-3"></i>
                            <div>
                                <strong>Mantener comunicación</strong>
                                <small class="text-muted d-block">Contacta a tu gestor si tienes dudas</small>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Resumen Rápido -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0 text-success">
                        <i class="fas fa-chart-pie me-2"></i>
                        Resumen
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Número de Fase</span>
                        <span class="badge bg-success fs-6">{{ $phase->numero }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Estado</span>
                        <span class="badge bg-{{ $statusColor ?? 'secondary' }}">
                            {{ $statusText ?? 'Sin Estado' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Duración</span>
                        <span class="text-success">
                            @if($phase->fecha_inicio && $phase->fecha_fin)
                                {{ $phase->fecha_inicio->diffInDays($phase->fecha_fin) }} días
                            @else
                                No definida
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0 text-success">
                        <i class="fas fa-cogs me-2"></i>
                        Acciones
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('aprendiz.phases.index') }}" 
                           class="btn btn-outline-success">
                            <i class="fas fa-list me-2"></i>
                            Ver Todas las Fases
                        </a>
                        <a href="{{ route('aprendiz.documentos') }}" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-file-alt me-2"></i>
                            Mis Documentos
                        </a>
                        <a href="{{ route('aprendiz.progreso') }}" 
                           class="btn btn-outline-info">
                            <i class="fas fa-chart-line me-2"></i>
                            Mi Progreso
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    
.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}
.progress {
    border-radius: 10px;
}
.progress-bar {
    border-radius: 10px;
}
.card {
    transition: transform 0.2s ease-in-out;
}
.card:hover {
    transform: translateY(-2px);
}
</style>
@endsection