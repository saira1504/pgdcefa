@extends('layouts.app')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-success">
                        <i class="fas fa-project-diagram me-2"></i>
                        Fases del Proyecto
                    </h1>
                    <p class="text-muted mb-0">Gestiona y visualiza las fases de tu proyecto productivo</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-success fs-6">{{ $phases->count() }} Fases</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Fases Grid -->
    <div class="row">
        @forelse($phases->sortBy('numero') as $phase)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-gradient-success text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-layer-group me-2"></i>
                                Fase {{ $phase->numero }}
                            </h5>
                            <span class="badge bg-white text-success fs-6">{{ $phase->numero }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Fecha de Inicio</small>
                                <strong class="text-success">
                                    {{ $phase->fecha_inicio ? $phase->fecha_inicio->format('d/m/Y') : 'No definida' }}
                                </strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Fecha de Fin</small>
                                <strong class="text-success">
                                    {{ $phase->fecha_fin ? $phase->fecha_fin->format('d/m/Y') : 'No definida' }}
                                </strong>
                            </div>
                        </div>
                        
                        @if($phase->fecha_inicio && $phase->fecha_fin)
                            @php
                                $now = now();
                                $start = $phase->fecha_inicio;
                                $end = $phase->fecha_fin;
                                $total = $end->diffInDays($start);
                                $elapsed = $now->diffInDays($start);
                                $progress = min(100, max(0, ($elapsed / $total) * 100));
                                
                                if ($now < $start) {
                                    $status = 'pending';
                                    $statusText = 'Pendiente';
                                    $statusColor = 'warning';
                                } elseif ($now > $end) {
                                    $status = 'completed';
                                    $statusText = 'Completada';
                                    $statusColor = 'success';
                                } else {
                                    $status = 'active';
                                    $statusText = 'En Progreso';
                                    $statusColor = 'info';
                                }
                            @endphp
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">Progreso</small>
                                    <span class="badge bg-{{ $statusColor }}">{{ $statusText }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $statusColor }}" 
                                         role="progressbar" 
                                         style="width: {{ $progress }}%" 
                                         aria-valuenow="{{ $progress }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted">{{ number_format($progress, 1) }}% completado</small>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#phaseModal{{ $phase->id }}">
                                <i class="fas fa-eye me-1"></i>
                                Ver Detalles
                            </button>

                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $phase->fecha_inicio ? $phase->fecha_inicio->diffForHumans() : 'Sin fecha' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay fases disponibles</h4>
                    <p class="text-muted">Aún no se han configurado las fases para tu proyecto.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Información adicional -->
    @if($phases->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                    <h5 class="mb-1">{{ $phases->where('fecha_inicio', '>', now())->count() }}</h5>
                                    <small class="text-muted">Pendientes</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-play fa-2x text-info mb-2"></i>
                                    <h5 class="mb-1">{{ $phases->filter(function($phase) { 
                                        return $phase->fecha_inicio && $phase->fecha_fin && 
                                               now()->between($phase->fecha_inicio, $phase->fecha_fin); 
                                    })->count() }}</h5>
                                    <small class="text-muted">En Progreso</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                    <h5 class="mb-1">{{ $phases->where('fecha_fin', '<', now())->count() }}</h5>
                                    <small class="text-muted">Completadas</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                                    <h5 class="mb-1">{{ $phases->whereNotNull('fecha_inicio')->count() }}</h5>
                                    <small class="text-muted">Con Fechas</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal Detalles Fase -->
<div class="modal fade" id="phaseModal{{ $phase->id }}" tabindex="-1" aria-labelledby="phaseModalLabel{{ $phase->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="phaseModalLabel{{ $phase->id }}">
            Detalles de la Fase {{ $phase->numero }}
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p><strong>Fase:</strong> {{ $phase->numero }}</p>
        <p><strong>Fecha de Inicio:</strong> {{ $phase->fecha_inicio ? $phase->fecha_inicio->format('d/m/Y') : 'No definida' }}</p>
        <p><strong>Fecha de Fin:</strong> {{ $phase->fecha_fin ? $phase->fecha_fin->format('d/m/Y') : 'No definida' }}</p>

        @if($phase->fecha_inicio && $phase->fecha_fin)
            @php
                $now = now();
                $start = $phase->fecha_inicio;
                $end = $phase->fecha_fin;
                $total = $end->diffInDays($start);
                $elapsed = $now->diffInDays($start);
                $progress = min(100, max(0, ($elapsed / $total) * 100));

                if ($now < $start) {
                    $status = 'Pendiente';
                    $color = 'warning';
                } elseif ($now > $end) {
                    $status = 'Completada';
                    $color = 'success';
                } else {
                    $status = 'En Progreso';
                    $color = 'info';
                }
            @endphp

            <p><strong>Estado:</strong> <span class="badge bg-{{ $color }}">{{ $status }}</span></p>

            <div class="mb-3">
                <small class="text-muted d-block mb-1">Progreso</small>
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar bg-{{ $color }}" 
                         style="width: {{ $progress }}%;" 
                         role="progressbar" 
                         aria-valuenow="{{ $progress }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                    </div>
                </div>
                <small class="text-muted">{{ number_format($progress, 1) }}% completado</small>
            </div>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<style>
.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}
.card {
    transition: transform 0.2s ease-in-out;
}
.card:hover {
    transform: translateY(-5px);
}
.progress {
    border-radius: 10px;
}
.progress-bar {
    border-radius: 10px;
}
</style>
@endsection
