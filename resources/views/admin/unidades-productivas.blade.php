@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 px-0">
            @include('partials.sidebar_admin')
        </div>
        
        <div class="col-md-9 col-lg-10 p-4">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Gestión de Unidades Productivas</h2>
                    <p class="text-muted mb-0">Administra las unidades productivas de tu área</p>
                </div>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearUnidadModal">
                    <i class="fas fa-plus me-2"></i>Nueva Unidad
                </button>
            </div>

            <!-- Alertas -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Estadísticas generales -->
            <div class="row mb-4">
                <div class="col-md-2 mb-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body text-center py-3">
                            <h4 class="mb-1">{{ $estadisticas['total_unidades'] }}</h4>
                            <small>Total Unidades</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="card text-white bg-success">
                        <div class="card-body text-center py-3">
                            <h4 class="mb-1">{{ $estadisticas['unidades_activas'] }}</h4>
                            <small>Activas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="card text-white bg-info">
                        <div class="card-body text-center py-3">
                            <h4 class="mb-1">{{ $estadisticas['total_aprendices'] }}</h4>
                            <small>Aprendices</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body text-center py-3">
                            <h4 class="mb-1">{{ $estadisticas['documentos_pendientes'] }}</h4>
                            <small>Pendientes</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body text-center py-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">Progreso General:</small>
                                <strong>{{ $estadisticas['progreso_general'] }}%</strong>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: {{ $estadisticas['progreso_general'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Unidades -->
            <div class="row">
                @foreach($unidades as $unidad)
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-{{ $unidad->estado == 'Activa' ? 'success' : ($unidad->estado == 'Completada' ? 'primary' : 'secondary') }} text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">{{ $unidad->nombre }}</h6>
                                <span class="badge bg-light text-dark">{{ $unidad->estado }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">{{ $unidad->descripcion }}</p>
                            
                            <!-- Información básica -->
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">Gestor:</small>
                                    <p class="mb-1 fw-bold">{{ $unidad->gestor_nombre }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Tipo:</small>
                                    <p class="mb-1 fw-bold">{{ $unidad->tipo }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">Inicio:</small>
                                    <p class="mb-1">{{ \Carbon\Carbon::parse($unidad->fecha_inicio)->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Fin:</small>
                                    <p class="mb-1">{{ \Carbon\Carbon::parse($unidad->fecha_fin)->format('d/m/Y') }}</p>
                                </div>
                            </div>

                            <!-- Estadísticas -->
                            <div class="row text-center mb-3">
                                <div class="col-3">
                                    <h6 class="text-primary mb-0">{{ $unidad->total_aprendices }}</h6>
                                    <small class="text-muted">Aprendices</small>
                                </div>
                                <div class="col-3">
                                    <h6 class="text-success mb-0">{{ $unidad->documentos_aprobados }}</h6>
                                    <small class="text-muted">Aprobados</small>
                                </div>
                                <div class="col-3">
                                    <h6 class="text-warning mb-0">{{ $unidad->documentos_pendientes }}</h6>
                                    <small class="text-muted">Pendientes</small>
                                </div>
                                <div class="col-3">
                                    <h6 class="text-info mb-0">{{ $unidad->progreso_promedio }}%</h6>
                                    <small class="text-muted">Progreso</small>
                                </div>
                            </div>

                            <!-- Barra de progreso -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small>Progreso promedio:</small>
                                    <small>{{ $unidad->progreso_promedio }}%</small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: {{ $unidad->progreso_promedio }}%"></div>
                                </div>
                            </div>

                            <!-- Objetivo -->
                            @if($unidad->objetivo)
                            <div class="mb-3">
                                <small class="text-muted">Objetivo:</small>
                                <p class="small mb-0">{{ $unidad->objetivo }}</p>
                            </div>
                            @endif

                            <!-- Acciones -->
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('admin.unidad-detalles', $unidad->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Ver
                                </a>
                                <button class="btn btn-info btn-sm" onclick="asignarAprendices({{ $unidad->id }}, '{{ $unidad->nombre }}')">
                                    <i class="fas fa-user-plus me-1"></i>Asignar
                                </button>
                                <button class="btn btn-warning btn-sm" onclick="editarUnidad({{ $unidad->id }})">
                                    <i class="fas fa-edit me-1"></i>Editar
                                </button>
                                @if($unidad->documentos_pendientes > 0)
                                <button class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-clock me-1"></i>{{ $unidad->documentos_pendientes }} pendientes
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Aprendices sin asignar -->
            @if(count($aprendicesSinAsignar) > 0)
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>Aprendices Sin Asignar ({{ count($aprendicesSinAsignar) }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($aprendicesSinAsignar as $aprendiz)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border-warning">
                                <div class="card-body py-3">
                                    <h6 class="card-title mb-1">{{ $aprendiz->name }}</h6>
                                    <p class="text-muted small mb-1">{{ $aprendiz->email }}</p>
                                    <p class="text-muted small mb-2">{{ $aprendiz->programa }}</p>
                                    <small class="text-muted">Ingreso: {{ \Carbon\Carbon::parse($aprendiz->fecha_ingreso)->format('d/m/Y') }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para crear nueva unidad -->
<div class="modal fade" id="crearUnidadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>Nueva Unidad Productiva
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.crear-unidad') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Nombre de la Unidad *</label>
                            <input type="text" name="nombre" class="form-control" required 
                                   placeholder="Ej: Unidad 4 - Piscicultura">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tipo *</label>
                            <select name="tipo" class="form-select" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="Producción">Producción</option>
                                <option value="Comercialización">Comercialización</option>
                                <option value="Servicios">Servicios</option>
                                <option value="Mixto">Mixto</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Descripción *</label>
                        <textarea name="descripcion" class="form-control" rows="3" required
                                  placeholder="Describe los objetivos y actividades principales de la unidad"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Objetivo (Opcional)</label>
                        <textarea name="objetivo" class="form-control" rows="2"
                                  placeholder="Objetivo específico de aprendizaje de esta unidad"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre del Gestor *</label>
                            <input type="text" name="gestor_nombre" class="form-control" required
                                   placeholder="Nombre completo del gestor">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Fecha de Inicio *</label>
                            <input type="date" name="fecha_inicio" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Fecha de Fin</label>
                            <input type="date" name="fecha_fin" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Crear Unidad
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para asignar aprendices -->
<div class="modal fade" id="asignarAprendicesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>Asignar Aprendiz
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.asignar-aprendiz') }}" method="POST">
                @csrf
                <input type="hidden" name="unidad_id" id="unidadIdAsignar">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Unidad Seleccionada:</label>
                        <p class="fw-bold text-primary" id="nombreUnidadAsignar"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Seleccionar Aprendiz</label>
                        <select name="aprendiz_id" class="form-select" required>
                            <option value="">Seleccionar aprendiz</option>
                            @if(isset($aprendicesSinAsignar))
                                @foreach($aprendicesSinAsignar as $aprendiz)
                                    <option value="{{ $aprendiz->id }}">
                                        {{ $aprendiz->name }} - {{ $aprendiz->email }}</option>
                                @endforeach
                            @endif
                        </select>
                        <div class="form-text">
                            Solo se muestran aprendices que no están asignados a ninguna unidad
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-user-plus me-2"></i>Asignar Aprendiz
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function asignarAprendices(unidadId, nombreUnidad) {
    document.getElementById('unidadIdAsignar').value = unidadId;
    document.getElementById('nombreUnidadAsignar').textContent = nombreUnidad;
    const modal = new bootstrap.Modal(document.getElementById('asignarAprendicesModal'));
    modal.show();
}

function editarUnidad(unidadId) {
    // Implementar modal de edición
    alert('Función de editar unidad ' + unidadId + ' - Por implementar');
}

// Animación de barras de progreso
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.transition = 'width 1s ease-in-out';
            bar.style.width = width;
        }, 300);
    });
});
</script>
@endsection
