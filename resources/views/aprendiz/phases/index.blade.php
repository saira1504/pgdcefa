@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <!-- CONTENIDO PRINCIPAL OCUPA TODO EL ANCHO -->
    <div class="row">
        <div class="col-12 p-4">
            <!-- Header moderno -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1 text-success">
                        <i class="fas fa-project-diagram me-2"></i>Fases del Proyecto
                    </h1>
                    <p class="text-muted small mb-0">Gestiona y visualiza las fases de tu proyecto productivo</p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                        </div>
                        <small class="text-muted" id="currentDate" style="font-size: 0.8rem;">{{ now()->format('d/m/Y') }}</small>
                    </div>
                    <span class="badge bg-success fs-6">
                        <i class="fas fa-layer-group me-1"></i>{{ $phases->count() }} Fases
                    </span>
                </div>
            </div>

         <!-- 📊 ESTADÍSTICAS ARRIBA -->
            @if($phases->count() > 0)
            <div class="row mb-4">
                <div class="col-xl-3 col-lg-6 col-md-6 mb-2">
                    <div class="card border-0 shadow-sm" style="height: 80px; background: linear-gradient(135deg, #ffc107, #ffeb3b);">
                        <div class="card-body p-3 d-flex align-items-center text-white">
                            <div class="me-3">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-0 fw-bold">
                                    {{ $phases->filter(function($phase) { 
                                        return $phase->fecha_inicio && $phase->fecha_inicio > now(); 
                                    })->count() }}
                                </h3>
                                <small class="opacity-75">Pendientes</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 mb-2">
                    <div class="card border-0 shadow-sm" style="height: 80px; background: linear-gradient(135deg, #17a2b8, #20c997);">
                        <div class="card-body p-3 d-flex align-items-center text-white">
                            <div class="me-3">
                                <i class="fas fa-play fa-2x"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-0 fw-bold">
                                    {{ $phases->filter(function($phase) { 
                                        return $phase->fecha_inicio && $phase->fecha_fin && 
                                               now()->between($phase->fecha_inicio, $phase->fecha_fin); 
                                    })->count() }}
                                </h3>
                                <small class="opacity-75">En Progreso</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 mb-2">
                    <div class="card border-0 shadow-sm" style="height: 80px; background: linear-gradient(135deg, #28a745, #20c997);">
                        <div class="card-body p-3 d-flex align-items-center text-white">
                            <div class="me-3">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-0 fw-bold">
                                    {{ $phases->filter(function($phase) { 
                                        return $phase->fecha_fin && $phase->fecha_fin < now(); 
                                    })->count() }}
                                </h3>
                                <small class="opacity-75">Completadas</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 mb-2">
                    <div class="card border-0 shadow-sm" style="height: 80px; background: linear-gradient(135deg, #6f42c1, #e83e8c);">
                        <div class="card-body p-3 d-flex align-items-center text-white">
                            <div class="me-3">
                                <i class="fas fa-calendar-alt fa-2x"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="mb-0 fw-bold">{{ $phases->whereNotNull('fecha_inicio')->count() }}</h3>
                                <small class="opacity-75">Con Fechas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- 📋 TABLA DE FASES -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-table me-2 text-success"></i>Fases del Proyecto
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($phases->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th class="fw-semibold">
                                        <i class="fas fa-hashtag me-1"></i>Fase
                                    </th>
                                    <th class="fw-semibold">
                                        <i class="fas fa-calendar-plus me-1"></i>Fecha de Inicio
                                    </th>
                                    <th class="fw-semibold">
                                        <i class="fas fa-calendar-minus me-1"></i>Fecha de Fin
                                    </th>
                                    
                                    <th class="fw-semibold">
                                        <i class="fas fa-info-circle me-1"></i>Estado
                                    </th>
                                    <th class="fw-semibold text-center">
                                        <i class="fas fa-cogs me-1"></i>Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($phases->sortBy('numero') as $phase)
                                <tr>
                                    <!-- NÚMERO DE FASE -->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 35px; height: 35px; font-weight: bold;">
                                                {{ $phase->numero }}
                                            </div>
                                            <div>
                                                <strong class="text-success">Fase {{ $phase->numero }}</strong>
                                                @if($phase->nombre)
                                                    <br><small class="text-muted">{{ $phase->nombre }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- FECHA INICIO - TRADUCIDA AL ESPAÑOL -->
                                    <td>
                                        @if($phase->fecha_inicio)
                                            <div class="text-success fw-bold">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $phase->fecha_inicio->format('d/m/Y') }}
                                            </div>
                                            <small class="text-muted">
                                                @php
                                                    $diff = $phase->fecha_inicio->diffForHumans();
                                                    // 🇪🇸 TRADUCIR FECHAS AL ESPAÑOL
                                                    $translations = [
                                                        'ago' => 'hace',
                                                        'from now' => 'en',
                                                        'day' => 'día',
                                                        'days' => 'días',
                                                        'week' => 'semana',
                                                        'weeks' => 'semanas',
                                                        'month' => 'mes',
                                                        'months' => 'meses',
                                                        'year' => 'año',
                                                        'years' => 'años',
                                                        'hour' => 'hora',
                                                        'hours' => 'horas',
                                                        'minute' => 'minuto',
                                                        'minutes' => 'minutos',
                                                        'second' => 'segundo',
                                                        'seconds' => 'segundos',
                                                        'before' => 'antes',
                                                        'after' => 'después'
                                                    ];
                                                    foreach ($translations as $en => $es) {
                                                        $diff = str_replace($en, $es, $diff);
                                                    }
                                                @endphp
                                                {{ $diff }}
                                            </small>
                                        @else
                                            <span class="text-muted">
                                                <i class="fas fa-question-circle me-1"></i>No definida
                                            </span>
                                        @endif
                                    </td>

                                    <!-- FECHA FIN - TRADUCIDA AL ESPAÑOL -->
                                    <td>
                                        @if($phase->fecha_fin)
                                            <div class="text-danger fw-bold">
                                                <i class="fas fa-calendar-times me-1"></i>
                                                {{ $phase->fecha_fin->format('d/m/Y') }}
                                            </div>
                                            <small class="text-muted">
                                                @php
                                                    $diff = $phase->fecha_fin->diffForHumans();
                                                    foreach ($translations as $en => $es) {
                                                        $diff = str_replace($en, $es, $diff);
                                                    }
                                                @endphp
                                                {{ $diff }}
                                            </small>
                                        @else
                                            <span class="text-muted">
                                                <i class="fas fa-question-circle me-1"></i>No definida
                                            </span>
                                        @endif
                                    </td>

                                    

                                    <!-- ESTADO -->
                                    <td>
                                        @if($phase->fecha_inicio && $phase->fecha_fin)
                                            @php
                                                $now = now();
                                                if ($now < $phase->fecha_inicio) {
                                                    $status = 'Pendiente';
                                                    $color = 'warning';
                                                    $icon = 'clock';
                                                } elseif ($now > $phase->fecha_fin) {
                                                    $status = 'Completada';
                                                    $color = 'success';
                                                    $icon = 'check-circle';
                                                } else {
                                                    $status = 'En Progreso';
                                                    $color = 'info';
                                                    $icon = 'play-circle';
                                                }
                                            @endphp
                                            <span class="badge bg-{{ $color }} fs-6">
                                                <i class="fas fa-{{ $icon }} me-1"></i>{{ $status }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary fs-6">
                                                <i class="fas fa-question me-1"></i>Sin definir
                                            </span>
                                        @endif
                                    </td>

                                    <!-- ACCIONES -->
                                    <td class="text-center">
                                        <button type="button" 
                                                class="btn btn-outline-success btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#phaseModal{{ $phase->id }}">
                                            <i class="fas fa-eye me-1"></i>Ver Detalles
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <!-- SIN FASES -->
                    <div class="text-center py-5">
                        <i class="fas fa-project-diagram fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted mb-3">No hay fases disponibles</h4>
                        <p class="text-muted">Aún no se han configurado las fases para tu proyecto.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 🔍 MODALES PARA DETALLES - TODO EN ESPAÑOL -->
@foreach($phases as $phase)
<div class="modal fade" id="phaseModal{{ $phase->id }}" tabindex="-1" aria-labelledby="phaseModalLabel{{ $phase->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white py-4 border-0" 
                 style="background: linear-gradient(135deg, #28a745, #20c997);">
                <div class="d-flex align-items-center">
                    <i class="fas fa-layer-group fa-2x me-3"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="phaseModalLabel{{ $phase->id }}">
                            Detalles de la Fase {{ $phase->numero }}
                        </h5>
                        <small class="opacity-75">Información completa de la fase</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-success">
                            <i class="fas fa-hashtag me-1"></i>Número de Fase
                        </label>
                        <div class="p-2 bg-light rounded">
                            <strong class="text-success fs-4">Fase {{ $phase->numero }}</strong>
                        </div>
                    </div>
                    
                    @if($phase->nombre)
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-success">
                            <i class="fas fa-tag me-1"></i>Nombre
                        </label>
                        <div class="p-2 bg-light rounded">
                            {{ $phase->nombre }}
                        </div>
                    </div>
                    @endif

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-success">
                            <i class="fas fa-calendar-plus me-1"></i>Fecha de Inicio
                        </label>
                        <div class="p-2 bg-light rounded">
                            @if($phase->fecha_inicio)
                                <strong class="text-success">{{ $phase->fecha_inicio->format('d/m/Y') }}</strong>
                                <br><small class="text-muted">
                                    @php
                                        $diff = $phase->fecha_inicio->diffForHumans();
                                        $translations = [
                                            'ago' => 'hace', 'from now' => 'en', 'day' => 'día', 'days' => 'días',
                                            'week' => 'semana', 'weeks' => 'semanas', 'month' => 'mes', 'months' => 'meses',
                                            'year' => 'año', 'years' => 'años'
                                        ];
                                        foreach ($translations as $en => $es) {
                                            $diff = str_replace($en, $es, $diff);
                                        }
                                    @endphp
                                    {{ $diff }}
                                </small>
                            @else
                                <span class="text-muted">No definida</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-success">
                            <i class="fas fa-calendar-minus me-1"></i>Fecha de Fin
                        </label>
                        <div class="p-2 bg-light rounded">
                            @if($phase->fecha_fin)
                                <strong class="text-danger">{{ $phase->fecha_fin->format('d/m/Y') }}</strong>
                                <br><small class="text-muted">
                                    @php
                                        $diff = $phase->fecha_fin->diffForHumans();
                                        foreach ($translations as $en => $es) {
                                            $diff = str_replace($en, $es, $diff);
                                        }
                                    @endphp
                                    {{ $diff }}
                                </small>
                            @else
                                <span class="text-muted">No definida</span>
                            @endif
                        </div>
                    </div>

                    @if($phase->fecha_inicio && $phase->fecha_fin)
                    <div class="col-12">
                        <label class="form-label fw-semibold text-success">
                            <i class="fas fa-chart-line me-1"></i>Progreso y Estado
                        </label>
                        <div class="p-3 bg-light rounded">
                            @php
                                $now = now();
                                $start = $phase->fecha_inicio;
                                $end = $phase->fecha_fin;
                                $total = $end->diffInDays($start);
                                $elapsed = max(0, $now->diffInDays($start));
                                $progress = $total > 0 ? min(100, max(0, ($elapsed / $total) * 100)) : 0;

                                if ($now < $start) {
                                    $status = 'Pendiente';
                                    $color = 'warning';
                                    $icon = 'clock';
                                } elseif ($now > $end) {
                                    $status = 'Completada';
                                    $color = 'success';
                                    $icon = 'check-circle';
                                } else {
                                    $status = 'En Progreso';
                                    $color = 'info';
                                    $icon = 'play-circle';
                                }
                            @endphp

                            <div class="mb-2">
                                <span class="badge bg-{{ $color }} fs-6">
                                    <i class="fas fa-{{ $icon }} me-1"></i>{{ $status }}
                                </span>
                            </div>
                            <div class="row mt-3 text-center">
                                <div class="col-4">
                                    <small class="text-muted d-block">Duración Total</small>
                                    <strong>{{ $total }} días</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Días Transcurridos</small>
                                    <strong>{{ $elapsed }} días</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Días Restantes</small>
                                    <strong>{{ max(0, $total - $elapsed) }} días</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- 🎨 ESTILOS ADICIONALES -->
<style>
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(40, 167, 69, 0.05);
}

.progress {
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
}

.modal-content {
    border-radius: 15px;
    overflow: hidden;
}

.badge {
    border-radius: 8px;
}

.btn {
    border-radius: 8px;
}

/* 📱 RESPONSIVE */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .col-12 {
        padding: 1rem !important;
    }
}

.navbar {
 display: none !important;
    visibility: hidden !important;
    width: 0 !important;
    height: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
    opacity: 0 !important;
}
</style>
@endsection